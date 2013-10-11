<?php
App::uses('AppHelper', 'View/Helper');
class PermissionHelper extends AppHelper {
	public $helpers = array('Session');
    
    public function check($controller, $action){
    	$controller = ucwords($controller);
    	
        // Assuming that allow('controllers') grands access to all actions
        if ($this->Session->check('Auth.Permissions.controllers') && $this->Session->read('Auth.Permissions.controllers') === true) {
            return true;
        }

    	if ($this->Session->check("Auth.Permissions.$controller.controllers") && $this->Session->read("Auth.Permissions.$controller.controllers") === true) {
            return true;
        }        
        
        if($this->Session->check("Auth.Permissions.$controller.$action") && $this->Session->read("Auth.Permissions.$controller.$action") === true) {
            return true;
        }
        
        return false;
    }
}