<?php
require_once('load.php');
$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'list';

switch($type){
	case 'list':
		$app->action('coupon','index',(isset($_GET['id'])?$_GET['id']:0));
		break;
	case 'coupontype':
		$app->action('coupon','coupon_type',(isset($_GET['id'])?$_GET['id']:0));
		break;
	case 'couponview':
		$app->action('coupon','coupon_view',(isset($_GET['id'])?$_GET['id']:0));
		break;
	case 'couponsend':
		$app->action('coupon','coupon_send');
		break;
	case 'searchGoods':
		$app->action('coupon','ajax_searchGoods',$_GET['data']);
		break;
	case 'couponsend_op': //ajax派发红包
		$app->action('coupon','ajax_couponsend',$_GET);
		break;
	default:
		die("无相关页面！");
		break;
}
?>