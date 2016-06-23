<?php
require_once("load.php");

$type = isset($_GET['type']) ? $_GET['type'] : "quanqiu";

switch($type){
	case 'quanqiu': //
		$app->action('quanqiu','quanqiu');
		break;
	default:
		$app->action('quanqiu',$type,$_GET);
		
}
?>