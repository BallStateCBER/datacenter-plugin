<?php 
	/* This element should be included in any view where textarea fields are intended
	 * to be turned into rich text editors. When the rich text editor gets updated or
	 * replaced, the only changes necessary will be within the DataCenter plugin. 
	 * 
	 * Include this element in views like so: 
	 * <?php echo $this->element('rich_text_editor_init', array(), array('plugin' => 'DataCenter')); ?>
	 * 
	 * To customize: http://docs.ckeditor.com
	 */	

	$this->Html->script('/DataCenter/ckeditor/ckeditor.js', array('inline' => false));
	$this->Html->script('/DataCenter/ckeditor/adapters/jquery.js', array('inline' => false));
	if (! isset($customConfig)) {
		$customConfig = '';
	}
	$this->Js->buffer("
		$('textarea').ckeditor({
			toolbar: 'Basic',
			customConfig: '$customConfig'
		});
	");