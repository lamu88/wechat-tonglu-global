<?php
require_once('load.php');
$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'list';

switch($type){
	case 'order_trend': //订单走势
		$app->action('stats','order_trend');
		break;
	case 'sale_trend': //销售走势
		$app->action('stats','sale_trend');
		break;
	case 'profit_trend':  //利润走势
		$app->action('stats','profit_trend');
		break;
	case 'sale_rank': //销售排行
		$app->action('stats','sale_rank');
		break;
	case 'visit_sale': //访问购买率
		$app->action('stats','visit_sale');
		break;
	default:
		$app->action('stats',$type,$_GET);
		break;
}
?>