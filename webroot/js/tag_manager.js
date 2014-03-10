var TagManager = {
	// A tree-shaped object provided to init()
	tags: [],
	
	// A one-dimensional array populated by createTagList()
	tag_list: [],
	
	// An object with tag_name: tag_id pairs populated by createTagList()
	tags_ids: {},

	// Used by preselectTags()
	selected_tags: [],
	
	container: null,

	init: function (options) {
		if (options.hasOwnProperty('container')) {
			this.container = $(options.container);
		} else {
			this.container = $('#available_tags');
		}
		
		if (options.hasOwnProperty('tags')) {
			this.tags = options.tags;
		}
		
		var tree_container = $('<div id="available_tags_tree"></div>');
		this.container.append(tree_container);
		this.createTagTree(this.tags, tree_container);
		
		this.createTagList();
		
		if (options.hasOwnProperty('selected_tags')) {
			this.selected_tags = options.selected_tags;
		}
		if (this.selected_tags.length > 0) {
			TagManager.preselectTags(this.selected_tags);
		}
		
		$('#new_tag_rules_toggler').click(function (event) {
			event.preventDefault();
			$('#new_tag_rules').slideToggle(200);
		});
		
		$('#example_selectable_tag').click(function (event) {
			event.preventDefault();
		});
		
		this.checkRequirements();
	},
	
	checkRequirements: function () {
		if (! window.jQuery) { 
			this.showError('Error: The tag manager requires jQuery.');
		} else {
			if (! $.effects || ! $.effects.effect['transfer']) {
				this.showError('Error: The jQuery UI transfer effect is required for the tag manager but has not been loaded.');
			}
			if (! $.isFunction($.fn.autocomplete)) {
				this.showError('Error: The jQuery UI autocomplete widget is required for the tag manager but has not been loaded.');
			}
		}
	},
	
	showError: function (message) {
		this.container.prepend('<p style="color: red;">'+message+'</p>');
	},
	
	/**
	 * @param data An array of tag objects
	 * @param container $('#container_id')
	 * @returns
	 */
	createTagTree: function(data, container) {
		var list = $('<ul></ul>');
		for (var i = 0; i < data.length; i++) {
			var tag_id = data[i].id;
			var tag_name = data[i].name;
			var children = data[i].children;
			var has_children = (children.length > 0);
			var is_selectable = data[i].selectable;
			var list_item = $('<li data-tag-id="'+tag_id+'"></li>');
			var row = $('<div class="single_row"></div>');
			list_item.append(row);
			list.append(list_item);
			
			if (is_selectable) {
				var tag_link = $('<a href="#" class="available_tag" title="Click to select" data-tag-id="'+tag_id+'"></a>');
				tag_link.append(tag_name);
				(function(tag_id) {
					tag_link.click(function (event) {
						event.preventDefault();
						var link = $(this);
						var tag_name = link.html();
						var list_item = link.closest('li');
						TagManager.selectTag(tag_id, tag_name, list_item);
					});
				})(tag_id);
				tag_name = tag_link;
			}
			
			// Bullet point
			if (has_children) {
				var collapsed_icon = $('<a href="#" title="Click to expand/collapse"></a>');
				collapsed_icon.append('<img src="/data_center/img/icons/menu-collapsed.png" class="expand_collapse" />');
				(function(children) {
					collapsed_icon.click(function(event) {
						event.preventDefault();
						var icon = $(this);
						var icon_container = icon.parent('div');
						// var children = data[i].children;
						var children_container = icon_container.next('.children');
						var row = icon_container.parent('li');
						
						// Populate list if it is empty
						if (children_container.is(':empty')) {
							TagManager.createTagTree(children, children_container);
						}
						
						// Open/close
						children_container.slideToggle(200, function() {
							var icon_image = icon.children('img.expand_collapse');
							if (children_container.is(':visible')) {
								icon_image.prop('src', '/data_center/img/icons/menu-expanded.png');
							} else {
								icon_image.prop('src', '/data_center/img/icons/menu-collapsed.png');
							}
						});
					});
				})(children);
				
				row.append(collapsed_icon);
			} else {
				row.append('<img src="/data_center/img/icons/menu-leaf.png" class="leaf" />');
			}
			
			row.append(tag_name);
			
			// Tag and submenu
			if (has_children) {
				var children_container = $('<div style="display: none;" class="children"></div>');
				row.after(children_container);
			}
			
			// If tag has been selected
			if (is_selectable && this.tagIsSelected(tag_id)) {
				tag_name.addClass('selected');
				if (! has_children) {
					list_item.hide();
				}
			}
		}
		container.append(list);
	},
	
	createTagList: function () {
		this.container.append(list_container);
		this.processTagList(this.tags);
		this.tag_list.sort();
		var list = $('<ul></ul>');
		for (var i = 0; i < this.tag_list.length; i++) {
			var tag_name = this.tag_list[i];
			var tag_id = this.tags_ids[tag_name];
			var list_item = $('<li data-tag-id="'+tag_id+'"></li>');
			
			var tag_link = $('<a href="#" class="available_tag" title="Click to select" data-tag-id="'+tag_id+'"></a>');
			tag_link.append(tag_name);
			(function(tag_id) {
				tag_link.click(function (event) {
					event.preventDefault();
					var link = $(this);
					var tag_name = link.html();
					var list_item = link.closest('li');
					TagManager.selectTag(tag_id, tag_name, list_item);
				});
			})(tag_id);
			list_item.append(tag_link);
			list.append(list_item);
		}
		
		var tabs = $('<ul></ul>');
		tabs.append($('<li><a href="#available_tags_tree">Tree</a></li>'));
		tabs.append($('<li><a href="#available_tags_list">List</a></li>'));
		this.container.prepend(tabs);
		
		var list_container = $('<div id="available_tags_list"></div>');
		list_container.append(list);
		this.container.append(list_container);
		
		this.container.tabs();
	},
	
	processTagList: function (data) {
		for (var i = 0; i < data.length; i++) {
			var tag_id = data[i].id;
			var tag_name = data[i].name;
			var children = data[i].children;
			var has_children = (children.length > 0);
			var is_selectable = data[i].selectable;
			if (is_selectable) {
				this.tag_list.push(tag_name);
				this.tags_ids[tag_name] = tag_id;
			}
			if (has_children) {
				this.processTagList(children);
			}
		}
	},

	tagIsSelected: function(tag_id) {
		var selected_tags = $('#selected_tags a');
		for (var i = 0; i < selected_tags.length; i++) {
			var tag = $(selected_tags[i]);
			if (tag.data('tagId') == tag_id) {
				return true;
			}
		}
		return false;
	},

	preselectTags: function(selected_tags) {
		if (selected_tags.length == 0) {
			return;
		}
		$('#selected_tags_container').show();
		for (var i = 0; i < selected_tags.length; i++) {
			TagManager.selectTag(selected_tags[i].id, selected_tags[i].name);
		}
	},

	unselectTag: function(tag_id, unselect_link) {
		var available_tag_list_item = this.container.find('li[data-tag-id="'+tag_id+'"]');
		
		// If available tag has not yet been loaded, then simply remove the selected tag
		if (available_tag_list_item.length == 0) {
			unselect_link.remove();
			if ($('#selected_tags').children().length == 0) {
				$('#selected_tags_container').slideUp(200);
			}
			return;
		}
		
		available_tag_list_item.each(function () {
			var li = $(this);
			var link = $(this).find('a[data-tag-id="'+tag_id+'"]');
			link.removeClass('selected');
			
			// If the available tag will be immediately visible (not in a closed tab or closed tree branch)
			if (li.parent().is(':visible')) {
				var transfer_effect = function () {
					var options = {
						to: li.children('a').first(),
						className: 'ui-effects-transfer'
					};
					var remove_link = function () {
						TagManager.removeUnselectLink(unselect_link);
					};
					unselect_link.effect('transfer', options, 200, remove_link);
				};
				li.slideDown(200, transfer_effect);
			
			// If the available tag will not be immediately visible
			} else {
				li.show();
				TagManager.removeUnselectLink(unselect_link);
			}
		});
	},

	removeUnselectLink: function (unselect_link) {
		unselect_link.fadeOut(200, function () {
			unselect_link.remove();
			if ($('#selected_tags').children().length == 0) {
				$('#selected_tags_container').slideUp(200);
			}
		});
	},
	
	selectTag: function(tag_id, tag_name, available_tag_list_item) {
		var selected_container = $('#selected_tags_container');
		if (! selected_container.is(':visible')) {
			selected_container.slideDown(200);
		}
		
		// Do not add tag if it is already selected
		if (this.tagIsSelected(tag_id)) {
			return;
		}
		
		// Add tag
		var list_item = $('<a href="#" title="Click to remove" data-tag-id="'+tag_id+'"></a>');
		list_item.append(tag_name);
		list_item.append('<input type="hidden" name="data[Tag][]" value="'+tag_id+'" />');
		list_item.click(function (event) {
			event.preventDefault();
			var unselect_link = $(this);
			var tag_id = unselect_link.data('tagId');
			TagManager.unselectTag(tag_id, unselect_link);
		});
		list_item.hide();
		$('#selected_tags').append(list_item);
		list_item.fadeIn(200);
		
		// If available tag has not yet been loaded, then there's no need to mess with its link
		if ($('li[data-tag-id="'+tag_id+'"]').length == 0) {
			return;
		}
		
		// Hide/update links to add tag
		var links = this.container.find('a[data-tag-id="'+tag_id+'"]');
		links.each(function () {
			var link = $(this);
			var callback = function() {
				link.addClass('selected');
				var parent_li = link.closest('li');
				var children = parent_li.children('.children');
				if (children.length == 0) {
					if (parent_li.is(':visible')) {
						parent_li.slideUp(200);
					} else {
						parent_li.hide();
					}
				}
			};
			if (link.is(':visible')) {
				var options = {
					to: '#selected_tags a[data-tag-id="'+tag_id+'"]',
					className: 'ui-effects-transfer'
				};
				link.effect('transfer', options, 200, callback);
			} else {
				callback();
			}
		});
	},

	setupAutosuggest: function(selector) {
		$(selector).bind('keydown', function (event) {
			if (event.keyCode === $.ui.keyCode.TAB && $(this).data('autocomplete').menu.active) {
				event.preventDefault();
			}
		}).autocomplete({
			source: function(request, response) {
				$.getJSON('/tags/auto_complete', {
					term: TagManager.extractLast(request.term)
				}, response);
			},
			delay: 0,
			search: function() {
				var term = TagManager.extractLast(this.value);
				if (term.length < 2) {
					return false;
				}
				$(selector).siblings('img.loading').show();
			},
			response: function() {
				$(selector).siblings('img.loading').hide();
			},
			focus: function() {
				return false;
			},
			select: function(event, ui) {
				var tag_name = ui.item.label;
				var terms = TagManager.split(this.value);
				terms.pop();
				terms.push(tag_name);
				// Add placeholder to get the comma-and-space at the end
				terms.push('');
				this.value = terms.join(', ');
				return false;
			}
		});
	},
	
	setupCustomTagInput: function(selector) {
		if (! selector) {
			selector = '#custom_tag_input';
		}
		$(selector).bind('keydown', function (event) {
			// don't navigate away from the field on tab when selecting an item
			if (event.keyCode === $.ui.keyCode.TAB && $(this).data('autocomplete').menu.active) {
				event.preventDefault();
			}
		}).autocomplete({
			source: function(request, response) {
				$.getJSON('/tags/auto_complete', {
					term: TagManager.extractLast(request.term)
				}, response);
			},
			delay: 0,
			search: function() {
				// custom minLength
				var term = TagManager.extractLast(this.value);
				if (term.length < 2) {
				//	return false;
				}
				$('#tag_autosuggest_loading').show();
			},
			response: function() {
				$('#tag_autosuggest_loading').hide();
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function(event, ui) {
				// Add the selected term to 'selected tags'
				var tag_name = ui.item.label;
				var tag_id = ui.item.value;
				TagManager.selectTag(tag_id, tag_name);
				
				var terms = TagManager.split(this.value);
				// Remove the term being typed from the input field
				terms.pop();
				if (terms.length > 0) {
					// Add placeholder to get the comma-and-space at the end
					terms.push('');
				}
				this.value = terms.join(', ');
				
				return false;
			}
		});
	},
	
	split: function (val) {
		return val.split(/,\s*/);
	},

	extractLast: function (term) {
		return this.split(term).pop();
	}
};