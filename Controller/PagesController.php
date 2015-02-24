<?php
App::uses('AppController', 'Controller');
class PagesController extends AppController {
	public $name = 'Pages';

	public function phpinfo() {
		$this->layout = 'ajax';
	}

	public function clear_cache() {
		$this->set(array(
			'result' => Cache::clear() && clearCache()
		));
		$this->layout = 'simple';
	}
}