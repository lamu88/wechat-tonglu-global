<?php
require_once('load.php');
$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'list';

switch($type){
	case 'list':
		$app->action('topic','index');
		break;
	case 'info':
		$app->action('topic','topicinfo',(isset($_GET['id'])?$_GET['id']:0));
		break;
	case 'searchGoods':
		$app->action('topic','ajax_searchGoods',$_GET['data']);
		break;
	default:
		die("无相关页面！");
		break;
}
?>