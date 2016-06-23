<?php
require_once('load.php');

if($_POST['action']){
	switch($_POST['action']){
		case 'bathop':
			$app->action('order','ajax_order_bathop',$_POST['ids'],$_POST['type']); //批量操作
			break;
		case 'cate_dels':
			$app->action('order','ajax_cate_dels',$_POST['ids']);
			break;
		case 'op_status':
			$app->action('order','ajax_op_status',$_POST);
			break;
		case 'get_status_button':
			$app->action('order','ajax_get_status_button',$_POST['status']);
			break;
		case 'checkorder':
			$app->action('order','ajax_checkorder');
			break;
	}
	exit;
}
	
$type = isset($_GET['type']) ? $_GET['type'] : "list";

switch($type){
	case 'list': 
		$app->action('order','order_list');
		break;
	case 'order_info':
		$app->action('order','order_info',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'delivery_list': //发货单
		$app->action('order','order_delivery_list');
		break;
	case 'back_list': //退货单
		$app->action('order','order_back_list');
		break;
	case 'product_list': //产品总销量
		$app->action('order','product_order_list');
		break;	
	case 'orderprint':
		$app->action('order','order_print');
		break;		
	default: 
		$app->action('order',$type,$_GET);
		break;
		
}
?>