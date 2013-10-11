// jQuery equivalent of Prototype's down() function
(function($) {
  $.fn.down = function() {
    var el = this[0] && this[0].firstChild;
    while (el && el.nodeType != 1)
      el = el.nextSibling;
    return $(el);
  };
})(jQuery);


function showFlashMessages() {
	var messages = $('#flash_messages');
	if (! messages.is(':visible')) {
		messages.fadeIn(500);
	}
}

function hideFlashMessages() {
	var messages = $('#flash_messages');
	if (messages.is(':visible')) {
		messages.fadeOut(500, function() {
			$('#flash_messages ul').empty();
		});
	}
}

function insertFlashMessage(message, classname) {
	var msgLi = $(document.createElement('li'))
		.addClass(classname)
		.append('<p>'+message+'</p>')
		.hide()
		.fadeIn(500);
	$('#flash_messages ul').append(msgLi);
	if (! $('#flash_messages').is(':visible')) {
		showFlashMessages();
	}
}

function selectTag(tagId, hasChildren) {
	var availTagLi = $('#tag_' + tagId + '_li');
	
	// If available tag is not found
	if (availTagLi.length == 0) {
		if (! window.selected_tag_loading_error) {
			alert('There was an error loading the contents of the "selected tags" box.');
			window.selected_tag_loading_error = true;
		}
		console.log('#tag_' + tagId + '_li not found');
		return;
	}
	
	// Create the <li> for the selected tags list
	var tag_name = $('#tag_' + tagId + '_li span.tag_name').first().html();
	var selectedTagLi = $('<li id="tag_'+tagId+'_li_selected">'+
		'<div>'+
			'<a href="#" class="add_remove" id="tag_'+tagId+'_unselector" title="Click to remove" alt="Remove"></a>'+
			'<img class="leaf" src="/data_center/img/icons/menu-leaf.png">'+
			'<span class="tag_name">'+tag_name+'</span>'+
			'<input type="hidden" value="'+tagId+'" name="data[Tag][]">'+
		'</div>'+
	'</li>');
	
	// If it's unknown if this tag has children, check
	if (typeof hasChildren == 'undefined') {
		hasChildren = $('#tag_' + tagId + '_submenu').length != 0;
	}
	
	// For submenu headers, the tag won't be removed from the list, because the
	// user needs to click on it to expand/collapse its list of children. We'll
	// just remove its add button instead.
	if (hasChildren) {
		$('#tag_'+tagId+'_li .add_remove').first().hide();
		
	// Otherwise, the tag will just be hidden in the available tags list
	} else {
		availTagLi.hide();
	}
	
	// Place tag in 'selected' list
	$('#selected_tags').down('ul').append(selectedTagLi);
	
	// Add listener for remove button
	$('#tag_'+tagId+'_unselector').click(function(event) {
		event.preventDefault();
		unselectTag(tagId);
	});
}

// If an unlisted tag needs to be placed
function selectUnlistedTag(tagId, tag_name) {
	var selectedTagLi = $('<li id="tag_'+tagId+'_li_selected">'+
		'<div>'+
			'<a href="#" class="add_remove" id="tag_'+tagId+'_unselector" title="Click to remove" alt="Remove"></a>'+
			'<img class="leaf" src="/data_center/img/icons/menu-leaf.png">'+
			'<span class="tag_name">'+tag_name+'</span>'+
			'<input type="hidden" value="'+tagId+'" name="data[Tag][]">'+
		'</div>'+
	'</li>');
	
	// Place tag in 'selected' list
	$('#selected_tags').down('ul').append(selectedTagLi);
	
	// Add listener for remove button
	$('#tag_'+tagId+'_unselector').click(function(event) {
		event.preventDefault();
		unselectTag(tagId);
	});
}

function unselectTag(tagId) {
	var selectedTagLi = $('#tag_'+tagId+'_li_selected');

	// Remove tag from 'selected' list
	selectedTagLi.remove();

	var availTagLi = $('#tag_'+tagId+'_li');
	
	// If the tag is not found in the 'available tags' list,
	// then it was (probably) an unlisted tag that should not
	// appear in that list after being un-selected
	if (availTagLi.length == 0) {
		return;
	}
	
	// For tags with children, the tag is still visible, but needs its add button re-visible-ified
	if ($('#tag_'+tagId+'_submenu').length != 0) {
		$('#tag_'+tagId+'_li .add_remove').first().show();
		
	// Otherwise, the tag in the available list is hidden and just needs to be shown
	} else {
		availTagLi.show();
	}
}

function toggleTagBranch(toggleThisId, childCount, handleLiId) {
	var handle_li = $('#'+handleLiId);
	var toggle_this = $('#'+toggleThisId);
	if (toggle_this.length > 0) {
		var duration = 300;
		if (toggle_this.is(':visible')) {
			toggle_this.slideUp(duration);
			handle_li.down('img.expand_collapse').src = '/data_center/img/icons/menu-collapsed.png';
			handle_li.down('span').title = 'Click to expand';
		} else {
			toggle_this.slideDown(duration);
			handle_li.down('img.expand_collapse').src = '/data_center/img/icons/menu-expanded.png';
			handle_li.down('span').title = 'Click to collapse';
		}
	}
}