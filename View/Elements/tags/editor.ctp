<div class="input">
	<table id="tag_editing">
		<thead>
			<tr>
				<th>
					<label style="float: left;">
						Tags
					</label>
					<span>Available</span>
				</th>
				<td class="tween_spacer">
					&nbsp;
				</td>
				<th>
					<span>Selected</span>
				</th>
			</tr>
		</thead>
		<tfoot></tfoot>
		<tbody>
			<tr>
				<td id="available_tags" class="fake_input">
					<?php echo $this->Tag->show('Tag/name', $available_tags); ?>
				</td>
				<td class="tween_spacer">
					<?php echo $this->Html->image('/data_center/img/icons/arrow.png', array('title' => "Selected tags appear over here.")); ?>
				</td>
				<td id="selected_tags" class="fake_input">
					<ul class="tag_editing"></ul>
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;<br />
					<label style="height: 20px;">
						Additional Tags
						<span id="tag_autosuggest_loading" style="display: none;">
							<img src="/data_center/img/loading_small.gif" alt="Working..." title="Working..." style="vertical-align:top;" />
						</span>
					</label>
					<?php
						echo $this->Form->input('custom_tags', array(
							'label' => false, 
							'style' => 'margin-right: 5px; width: 100%; display: block;', 
							'between' => '<div class="footnote">Write out tags, separated by commas</div>',
							'after' => '<span class="footnote"><a href="#" id="new_tag_rules_toggler">Rules for creating new tags</a></span>',
							'id' => 'custom_tag_input'
						)); 
					?>
					<div id="new_tag_rules" style="display: none;">
						<p>
							Before entering new tags, please search for existing tags that meet your needs.
							You can start typing into the <em>additional tags</em> field and a list of 
							matching tags will be suggested for you.
						</p>
						
						<p>
							New tags must:
						</p>
						<ul>
							<li>
								be short, general descriptions that people might search for
							</li>
							<li>
								be general enough to apply to multiple posts
							</li>
						</ul>
						
						<p>
							Must not:
						</p>
						<ul>
							<li>
								include punctuation, such as dashes, commas, slashes, periods, etc.
							</li>
							<li>
								be so specific that it applies to most posts
							</li>
						</ul>
					</div>
					<?php $this->Js->buffer("
						$('#new_tag_rules_toggler').click(function(event) {
							event.preventDefault();
							$('#new_tag_rules').slideToggle(200);
						});
					"); ?>
				</td>
				<td class="tween_spacer">
					&nbsp;
				</td>
				<td style="vertical-align: top;">
					<img src="/data_center/img/icons/question.png" alt="Help" title="Help" id="tag_editor_help_toggler" class="help_toggler" style="float: right; margin-left: 5px; cursor: help;" />
					<div id="tag_editor_help" class="footnote" style="display: none;">
						<ul style="margin: 0px; padding-left: 15px;">
							<li>Move your cursor over tags that you want to add and click the [+] button that pops up to add them.</li>
							<li>After a tag has been added, you can click on the [-] button next to it to remove it.</li> 
							<li>Some tags can be clicked on to expand them and see related tags.</li>
							<li>Some tags, like the ones that are just headers for larger categories, can't be selected.</li>
						</ul>
					</div>
					<?php $this->Js->buffer("
						$('#tag_editor_help_toggler').click(function (event) {
							$('#tag_editor_help').toggle();
						});
					"); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<?php 
	$this->Html->css('/data_center/jquery_ui/css/smoothness/jquery-ui-1.10.0.custom.min.css', null, array('inline' => false));
	$this->Html->script('/data_center/jquery_ui/js/jquery-ui-1.10.0.custom.js', array('inline' => false));
	$this->Html->script('/data_center/js/admin.js', array('inline' => false));
	$this->Js->buffer("
		setupCustomTagInput();
	");
?>

<?php
	if (isset($this->data['Tag']) && ! empty($this->data['Tag'])) {
		foreach($this->data['Tag'] as $tag) {
			$tag_id = is_array($tag) ? $tag['id'] : $tag;
			if (isset($unlisted_tags[$tag_id])) {
				$this->Js->buffer("selectUnlistedTag($tag_id, \"".str_replace('"', '&quot;', $unlisted_tags[$tag_id])."\");");
			} else {
				$this->Js->buffer("selectTag($tag_id);");
			}
		}
	}
?>