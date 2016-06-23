<?php
require_once('load.php');
if(isset($_REQUEST['action'])){
	$app->action('exchange',$_REQUEST['action'],$_POST);
	exit;
}

$type = !isset($_REQUEST['type'])||empty($_REQUEST['type'])? 'cartlist' : $_REQUEST['type'];
switch($type){
	case 'cartlist':
		$app->action('exchange','checkout');
		break;
	case 'clear':
		$app->action('exchange','mycart_clear');
		break;
	case 'checkout':
		$app->action('exchange','checkout');
		break;
	case 'confirm':
		$app->action('exchange','confirm');
		break;
	default:
		$app->action('exchange',$type);
		break;
}

?>