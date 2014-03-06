<?php
App::uses('AppHelper', 'View/Helper');
class TagHelper extends AppHelper {
	public $helpers = array('Html', 'Js');

	private function availableTagsForJs($available_tags) {
		$array_for_json = array();
		foreach ($available_tags as $tag) {
			$array_for_json[] = array(
				'id' => $tag['Tag']['id'],
				'name' => $tag['Tag']['name'],
				'selectable' => $tag['Tag']['selectable'],
				'children' => $this->availableTagsForJs($tag['children'])
			);
		}
		return $array_for_json;
	}

	private function selectedTagsForJs($selected_tags) {
		$array_for_json = array();
		foreach ($selected_tags as $tag) {
			$array_for_json[] = array(
				'id' => $tag['id'],
				'name' => $tag['name']
			);
		}
		return $array_for_json;
	}


	/**
	 * If necessary, convert selected_tags from an array of IDs to a full array of tag info
	 * @param array $selected_tags
	 * @return array
	 */
	private function formatSelectedTags($selected_tags) {
		if (empty($selected_tags)) {
			return array();
		}
		if (is_array($selected_tags[0])) {
			return $selected_tags;
		}
		App::uses('Tag', 'Model');
		$Tag = new Tag();
		$retval = array();
		foreach ($selected_tags as $tag_id) {
			$result = $Tag->find('first', array(
				'conditions' => array('id' => $tag_id),
				'fields' => array('id', 'name', 'parent_id', 'listed', 'selectable'),
				'contain' => false
			));
			$retval[] = $result['Tag'];
		}
		return $retval;
	}

	public function setup($available_tags, $container_id, $selected_tags = array()) {
		if (! empty($selected_tags)) {
			$selected_tags = $this->formatSelectedTags($selected_tags);
			$this->Js->buffer("
				TagManager.selected_tags = ".$this->Js->object($this->selectedTagsForJs($selected_tags)).";
				TagManager.preselectTags(TagManager.selected_tags);
			");
		}
		$this->Html->script('/data_center/js/tag_manager.js', array('inline' => false));
		$this->Html->css('/data_center/css/tag_editor.css', array('inline' => false));
		$this->Js->buffer("
			TagManager.tags = ".$this->Js->object($this->availableTagsForJs($available_tags)).";
			TagManager.init('#$container_id');
		");
	}
}