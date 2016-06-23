<?php
require_once('load.php');

$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'list';

switch($type){
	case 'getgroupgoods':
		$app->action('groupbuy','ajax_get_group_goods',$_GET);
		break;
	case 'delgroupgoods':
		$app->action('groupbuy','ajax_del_group_goods',$_GET['id']);
		break;
	case 'delgoods': 
		$app->action('groupbuy','ajax_delgroup',$_GET['ids']);
		break;
	case 'list':
		$app->action('groupbuy','index');
		break;
	case 'info':
		$app->action('groupbuy','groupinfo',isset($_GET['id'])&&$_GET['id']>0 ? $_GET['id'] : 0);
		break;	
	default:
		$app->action('groupbuy','index');
		break;
}
?>