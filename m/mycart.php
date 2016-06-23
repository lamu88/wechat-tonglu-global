<?php
require_once('load.php');
if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
		case 'delcartid':
			$app->action('shopping','ajax_delcart_goods',isset($_POST['id'])? $_POST['id'] : 0);
			break;
		case 'jisuan_shopping':
			$app->action('shopping','ajax_jisuan_shopping',$_POST);
			break;
		case 'change_jifen':
			$app->action('shopping','ajax_change_jifen',$_POST['checked']);
			break;
		default:
			$app->action('shopping',$_REQUEST['action'],$_POST);
			break;
	}
	exit;
}

$type = !isset($_REQUEST['type'])||empty($_REQUEST['type'])? 'cartlist' : $_REQUEST['type'];
switch($type){
	case 'cartlist':
		$app->action('shopping','checkout');
		break;
	case 'clear':
		$app->action('shopping','mycart_clear');
		break;
	case 'checkout':
		$app->action('shopping','checkout');
		break;
	case 'confirm':
		$app->action('shopping','confirm');
		break;
	default:
		$app->action('shopping',$type);
		break;
}

?>