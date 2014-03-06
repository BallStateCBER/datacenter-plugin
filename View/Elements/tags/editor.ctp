<?php 
	if (! isset($hide_label)) {
		$hide_label = false;
	}
?>

<div class="input" id="tag_editing">
	<div id="available_tags_container">
		<div id="available_tags"></div>
	</div>
	<div class="footnote">
		Click <img src="/data_center/img/icons/menu-collapsed.png" /> to expand groups. 
		Click 
		<a href="#" title="Selectable tags will appear in blue" id="example_selectable_tag">
			selectable tags
		</a>
		to select them.
	</div>
	
	<div id="selected_tags_container" style="display: none;">
		<span class="label">
			Selected tags:
		</span>
		<span id="selected_tags"></span>
		<div class="footnote">
			Click on a tag to unselect it.
		</div>
	</div>
	
	<?php if ($allow_custom): ?>
		<div id="custom_tag_input_wrapper">
			<label for="custom_tag_input">
				Additional Tags
				<span id="tag_autosuggest_loading" style="display: none;">
					<img src="/data_center/img/loading_small.gif" alt="Working..." title="Working..." style="vertical-align: top;" />
				</span>
			</label>
			<?php 
				echo $this->Form->input('custom_tags', array(
					'label' => false, 
					'style' => 'margin-right: 5px; width: 100%; display: block;', 
					'after' => '<div class="footnote">Write out tags, separated by commas. <a href="#" id="new_tag_rules_toggler">Rules for creating new tags</a></div>',
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
		</div>
	<?php endif; ?>	
</div>

<?php
	echo $this->Tag->setup($available_tags, $selected_tags);
	if ($allow_custom) {
		$this->Js->buffer("TagManager.setupCustomTagInput();");
	}
?>