<?php
App::uses('AppController', 'Controller');
class PagesController extends AppController {
	public $name = 'Pages';

	public function phpinfo() {
		$this->layout = 'ajax';
	}
}