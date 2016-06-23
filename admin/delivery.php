<?php
require_once('load.php');

$type = isset($_GET['type']) ? $_GET['type'] : 'list';
switch($type){
	case 'list':
		$app->action('order','deliverylist');
		break;
	case 'info':
		$app->action('order','deliveryinfo',isset($_GET['id'])?$_GET['id'] : 0);
		break;
	case 'arealist':
		$app->action('order','delivery_area_list',isset($_GET['id'])?$_GET['id'] : 0);
		break;
	case 'areainfo':
		$app->action('order','delivery_area_info',(isset($_GET['cid'])?$_GET['cid'] : 0),(isset($_GET['id'])?$_GET['id'] : 0));
		break;
	default:
		$app->action('order','deliverylist');
		break;
}

?>