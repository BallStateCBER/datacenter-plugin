<?php
$on_localhost = stripos($_SERVER['HTTP_HOST'], 'localhost') !== false;
$domain = $on_localhost ? '' : 'http://cberdata.org';
$plugin_path = $domain.'/data_center';

$this->Html->css($plugin_path.'/jquery-ui-1.11.3.custom/jquery-ui.min.css', array('inline' => false));
$this->Html->script($plugin_path.'/jquery-ui-1.11.3.custom/jquery-ui.min.js', array('inline' => false));