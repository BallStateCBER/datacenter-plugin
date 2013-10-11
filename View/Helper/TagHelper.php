<?php
/*
 * This helper is used with the tags/editor element.
 */

App::uses('AppHelper', 'View/Helper');
class TagHelper extends AppHelper {
	public $helpers = array('Js' => array('Jquery'));
	
	/* Shows a list of selectable tags for the tags/editor element
	 * 
	 * $name is a string with the format "$modelName/$fieldName";
	 * 		$modelName is typically 'Tag'
	 * 		$fieldName is the name of the field to be displayed, typically 'name'
	 * $available_tags is an array of tags, each member having this format:
	 * 		Array(
            	[Tag] => Array(name, id, parent_id, selectable),
                [children] => Array(...)
        	)
	 */
	function show($name, $available_tags) {
		list($modelName, $fieldName) = explode('/', $name);
		//$available_tags = $this->addDummyBranch($available_tags);
		return $this->list_element($available_tags, $modelName, $fieldName, 0);
	}

	function list_element($available_tags, $modelName, $fieldName, $level) {
		$output = '';
		
		foreach ($available_tags as $key => $val) {
			$tag_id = $val[$modelName]['id'];
			$has_children = isset($val['children'][0]);
			
			// Create the add button and accompanying listener if appropriate
			if ($val[$modelName]['selectable']) {
				$add_button = '<a href="#" class="add_remove" id="tag_'.$tag_id.'_selector" title="Click to add" alt="Add"></a>';
				$this->Js->buffer("$('#tag_{$tag_id}_selector').click(function (event) {
					event.preventDefault();
					selectTag($tag_id, ".($has_children ? 'true' : 'false').");
				});");
			} else {
				$add_button = '';
			}
			
			$li_id = 'tag_'.$tag_id.'_li';
			$tag_name = '<span class="tag_name">'.ucfirst($val[$modelName][$fieldName]).'</span>';
			
			// Create <li> contents for a tag with children and listener for expanding/collapsing
			if ($has_children) {
				$submenu_id = 'tag_'.$tag_id.'_submenu';
				$row = 
					'<div>'.
						$add_button.
						'<a href="#" class="submenu_handle" id="tag_'.$tag_id.'_branchhandle" title="Click to expand">'.
							'<img src="/data_center/img/icons/menu-collapsed.png" class="expand_collapse" />'.
							$tag_name.
						'</a>'.
					'</div>'.
					'<div id="'.$submenu_id.'" style="display: none;">'.
						$this->list_element($val['children'], $modelName, $fieldName, $level+1).
					'</div>';
				$this->Js->buffer("
					$('#tag_{$tag_id}_branchhandle').click(function (event) {
						event.preventDefault();
						toggleTagBranch('$submenu_id', ".count($val['children']).", '$li_id');
					});
				");
			
			// Create <li> contents for a tag with no children
			} else {				
				$row =
					'<div>'.
						$add_button.
						'<img src="/data_center/img/icons/menu-leaf.png" class="leaf" />'.
						$tag_name.
					'</div>';
			}
			$output .= '<li id="'.$li_id.'">'.$row.'</li>';
		}
		 
		return '<ul class="tag_editing">'.$output.'</ul>';
	}
	
	// Used for testing
	public function addDummyBranch($available_tags) {
		for ($n = 0; $n < 5; $n++) {
			$available_tags[0]['children'][] = array(
				'Tag' => array(
					'name' => 'Dummy tag '.$n,
					'id' => 10000+$n,
					'parent_id' => $available_tags[0]['Tag']['id'],
					'selectable' => 1
				),
                'children' => array()
			);
		}
		return $available_tags;
	}
}