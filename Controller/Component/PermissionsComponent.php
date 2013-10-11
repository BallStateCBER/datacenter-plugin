<?php
class PermissionsComponent extends Component {
	public $components = array('Session', 'Acl');
	
	public function storePermissions($foreign_key) {
		$aro = $this->Acl->Aro->find('first', array(
	        'conditions' => array(
	            'Aro.model' => 'Group',
	            'Aro.foreign_key' => $foreign_key,
	        ),
	    ));
	    $acos = $this->Acl->Aco->children();
	    foreach ($acos as $aco) {
		    $permission = $this->Acl->Aro->Permission->find('first', array(
		        'conditions' => array(
		            'Permission.aro_id' => $aro['Aro']['id'],
		            'Permission.aco_id' => $aco['Aco']['id'],
		        )
		    ));
	    	if (isset($permission['Permission']['id'])) {
	        	if ($permission['Permission']['_create'] == 1 ||
	            	$permission['Permission']['_read'] == 1 ||
	            	$permission['Permission']['_update'] == 1 ||
	            	$permission['Permission']['_delete'] == 1) {
	            	$this->Session->write(
	                    'Auth.Permissions.'.$permission['Aco']['alias'],
	                     true
	                );
	            	if(! empty($permission['Aco']['parent_id'])){
	            		$parentAco = $this->Acl->Aco->find('first', array(
	                        'conditions' => array(
	                            'id' => $permission['Aco']['parent_id']
	                        )
	                    ));
	            		$this->Session->write(
	                        'Auth.Permissions.'.$permission['Aco']['alias']
	                        .'.'.$parentAco['Aco']['alias'], 
	                        true
	                    );
	                }
	            }
	        }
	    }	
	}
	
	public function forgetPermissions() {
		$this->Session->delete('Auth.Permissions');	
	}
}