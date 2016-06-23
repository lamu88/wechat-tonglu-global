<?php
require_once("load.php");

$type = isset($_GET['type']) ? $_GET['type'] : "fixlevel";

switch($type){
	case 'fixlevel': //
		$app->action('fixlevel','fixlevel');
		break;
	case 'huangjin': //
		$app->action('fixlevel','huangjin');
		break;
	case 'sf': //
		$app->action('fixlevel','sf');
		break;	
	default:
		$app->action('fixlevel',$type,$_GET);
		
}
?>