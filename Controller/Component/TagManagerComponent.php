<?php
App::uses('Tag', 'Model');
class TagManagerComponent extends Component {		
	public function __construct($controller) {
		$this->controller = $controller;
	}
	
	public function beforeRender(Controller $controller) {
		$controller->helpers[] = 'DataCenter.Tag';
	}
	
	function getTags($model = null, $id = null) {
		if (! $model) {
			$model = $this->modelClass;
		}
		$Tag = new Tag();
		return $Tag->find('threaded', array( 
			'recursive' => 0,
			'fields' => array('Tag.name', 'Tag.id', 'Tag.parent_id', 'Tag.selectable'),
			'order' => array('Tag.name ASC')
		));
	}
	
	// Returns the top $limit most used tags associated with $model
	function getTop($model, $limit = 5) {
		$plural_model = strtolower(Inflector::pluralize(strtolower($model)));
		$table = "{$plural_model}_tags";
		$Tag = new Tag();
		return $Tag->query("			
			SELECT $table.tag_id, tags.name, COUNT($table.tag_id) 
			AS occurrences 
			FROM $table, tags
			WHERE tags.id = $table.tag_id
			GROUP BY $table.tag_id
			ORDER BY occurrences DESC
			LIMIT $limit
		");
	}
	
	// In a controller, this should be called like this: $this->TagManager->processTagInput($this->request->data);
	function processTagInput(&$data) {
		$Tag = new Tag();
		
		// Find the location (varies by model) of the custom_tags field in the $data array
		foreach (array_keys($data) as $model) {
			if (isset($data[$model]['custom_tags']) && $data[$model]['custom_tags'] !== '') {
				$model_with_custom_tags = $model;
			}
		}
		
		// Translate the custom tags field into tag IDs and put them into $data['Tag'][]
		if (isset($model_with_custom_tags)) {
			// Split the input string into an array of unique lowercase strings
			$custom_tags = explode(',', $data[$model_with_custom_tags]['custom_tags']);
			foreach ($custom_tags as &$custom_tag) {
				$custom_tag = strtolower(trim($custom_tag));
			}
			$custom_tags = array_unique($custom_tags);
			
			foreach ($custom_tags as $ct) {
				// Attempt to find existing tag
				$tag_id = $Tag->field('id', array('name' => $ct));
				
				// Create the custom tag if it does not already exist
				if (! $tag_id) {
					$Tag->create();
					$Tag->save(array('name' => $ct));
					$tag_id = $Tag->id;
				}
				
				// Add the tag to the list
				$data['Tag'][] = $tag_id;
			}
			
			// Clear the 'custom tags' field
			$data[$model_with_custom_tags]['custom_tags'] = '';
		}
		
		// Elminate duplicates
		if (isset($data['Tag']) && ! empty($data['Tag'])) {
			$data['Tag'] = array_unique($data['Tag']);
		}
	}
	
	// The $model parameter is a leftover from the tagging system developed for TheMuncieScene.com,
	// which separated tags into separate lists based on  
	function getList($model, $id = null) {
		$Tag = new Tag();
		return $Tag->find('threaded', array( 
			'recursive' => 0,
			'fields' => array('Tag.name', 'Tag.id', 'Tag.parent_id', 'Tag.selectable'),
			'order' => array('Tag.name ASC')
		));
	}
	
	function getCloud($model) {
		$tag_cloud = array();
		$plural_model = strtolower(Inflector::pluralize(strtolower($model)));
		$join_table = "{$plural_model}_tags";
		$Tag = new Tag();
		$result = $Tag->query("			
			SELECT $join_table.tag_id, tags.name, COUNT($join_table.tag_id) 
			AS occurrences 
			FROM $join_table, tags
			WHERE tags.id = $join_table.tag_id
			GROUP BY $join_table.tag_id
			ORDER BY tags.name ASC
		");
		foreach ($result as $row) {
			$name = $row['tags']['name'];
			$id = $row[$join_table]['tag_id'];
			$occurrences = $row[0]['occurrences'];
			$tag_cloud[] = compact('name', 'id', 'occurrences');
			continue;
			if (isset($tag_cloud[$tag_name])) {
				$tag_cloud[$tag_name]['count']++;
			} else {
				$tag_cloud[$tag_name] = array(
					'id' => $row[$table]['tag_id'],
					'count' => 1
				);
			}
		}
		return $tag_cloud;	
	}
	
	function prepareEditor($controller) {
		// Provide the full list of available tags to the tag editor in the view  
		$controller->set('available_tags', $this->getTags());
		
		// Check and see if these tags have a 'listed' field
		// (Listed tags show up under 'available tags' in the tag editor, unlisted do not) 
		$Tag = new Tag();
		if (isset($Tag->_schema['listed'])) {
			$unlisted_tags = array();
			
			// Find any unlisted tags associated with this form 
			if (isset($controller->request->data['Tag'])) {
				foreach ($controller->request->data['Tag'] as $tag) {
					$Tag->id = is_array($tag) ? $tag['id'] : $tag;
					$listed = isset($tag['listed']) ? $tag['listed'] : $Tag->field('listed');
					if (! $listed) {
						$unlisted_tags[$Tag->id] = isset($tag['name']) ? $tag['name'] : $Tag->field('name');
					}
				}
			}
			
			/* Since the tag editor normally auto-populates the 'selected tags' field with a list of tag IDs
			 * and pulls the names of those tags from the 'available tags' field, the names of unlisted tags
			 * will need to be provided to it with this variable. */ 
			$controller->set(compact('unlisted_tags'));
		}
	}
}