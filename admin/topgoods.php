<?php
require_once('load.php');
$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'clist';

switch($type){
	case 'clist':
		$app->action('topgoods','clist');
		break;
	case 'cinfo':
		$app->action('topgoods','cinfo',(isset($_GET['id'])?$_GET['id']:0));
		break;
	case 'lists':
		$app->action('topgoods','lists');
		break;
	case 'info':
		$app->action('topgoods','info',(isset($_GET['id'])?$_GET['id']:0));
		break;
	case 'searchGoods':
		$app->action('topgoods','ajax_searchGoods',$_GET['data']);
		break;
	case 'ajax_del_topgoods':
		$app->action('topgoods','ajax_del_topgoods',$_GET);
		break;
	case 'ajax_del_topgoodscate':
		$app->action('topgoods','ajax_del_topgoodscate',$_GET);
		break;
	default:
		die("无相关页面！");
		break;
}
?>