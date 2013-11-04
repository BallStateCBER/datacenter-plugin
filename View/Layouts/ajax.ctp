<?php
	if (! empty($flash_messages)) {
		foreach ($flash_messages as $msg) {
			$msg['message'] = str_replace('"', '\"', $msg['message']);
			$this->Js->buffer('insertFlashMessage("'.str_replace("\n", "\\n", $msg['message']).'", "'.$msg['class'].'");');
		}
	}
	
	// Only invoke Google Analytics if an ID is found and the page is not being served from the development server 
	$google_analytics_id = Configure::read('google_analytics_id');
	$not_localhost = isset($_SERVER['SERVER_NAME']) && stripos($_SERVER['SERVER_NAME'], 'localhost') === false;
	if ($google_analytics_id && $not_localhost) {
		if (Configure::check('using_universal_analytics')) {
			$this->Js->buffer("ga('send', 'pageview', {'page': '".$this->request->here."','title': '".(isset($title_for_layout) ? $title_for_layout : "''")."'});");
		} else {
			$this->Js->buffer("_gaq.push(['_trackPageview', '".$this->request->here."']);");
		}
	}
	echo $this->fetch('content');
	echo $this->Js->writeBuffer();