<?php 
require_once('load.php');

//ajax登录
if(isset($_REQUEST['action'])){
	switch(trim($_REQUEST['action'])){ 
		case 'register':
			$app->action('daili','ajax_user_register',$_POST);
			break;
		case 'ressinfoop':
			$app->action('daili','ajax_ressinfoop',$_POST);
			break;
		case 'updatepass':
			$app->action('daili','ajax_updatepass',$_POST);
			break;
		case 'get_ress':
			$app->action('daili','ajax_get_ress',$_POST);
		case 'get_peisong':
			$app->action('daili','ajax_get_ge_peisong',$_POST);
			break;
		case 'delress':
			$app->action('daili','ajax_delress',$_POST['id']);
			break;
		case 'getorderlist':
			$app->action('daili','ajax_getorderlist',$_POST);
			break;
		case 'order_op':
			$app->action('daili','ajax_order_op',(isset($_POST['id'])? $_POST['id'] : 0),$_POST['type']);  
			break;	
		case 'getuid':
			$app->action('daili','ajax_getuid');
			break;
		case 'delmycoll':
			$app->action('daili','ajax_delmycoll',$_POST['ids']);
			break;
		case 'feedback':
			$app->action('daili','ajax_feedback',$_POST['message']);
			break;
		case 'delmes':
			$app->action('daili','ajax_delmessages',$_POST['mes_id']);
			break;
		case 'delcomment':
			$app->action('daili','ajax_delcomment',$_POST['id']);
			break;	
		case 'rp_pass':
			$app->action('daili','ajax_rp_pass',$_POST);  //重设密码	
		default:
			$app->action('daili',trim($_REQUEST['action']),$_POST);  //重设密码
			break;
	}
	exit;
}

$action = isset($_GET['act'])&&!empty($_GET['act']) ? $_GET['act'] : "default";
switch($action){
	case 'default':
		$app->action('daili','index'); //用户后台
		break;
	case 'myorder': //用户订单
		$app->action('daili','orderlist'); 
		break;
	case 'orderinfo': //用户订单
		$app->action('daili','orderinfo',isset($_GET['order_id']) ? $_GET['order_id'] : ""); 
		break;
	case 'address_list': //收货地址
		$app->action('daili','address'); 
		break;	
	case 'mycoll':  //用户收藏
		$app->action('daili','mycolle'); 
	    break;
	case 'editpass':
		$app->action('daili','editpass');  //修改密码
		break;
	case 'logout';  //用户退出
		$app->action('daili','logout'); //用户注销==》清空session
		break;
	case 'forgetpass':
		$app->action('daili','forgetpass'); 
		break;
	case 'regsuccess':
		$app->action('daili','user_regsuccess_mes'); 
		break;
	case 'tuijian':
		$app->action('daili','user_tuijian'); 
		break;
	case 'question':
		$app->action('daili','messages'); 
		break;
	case 'mycomment':
		$app->action('daili','comment'); 
		break;
	default:
		$app->action('daili',$_GET['act'],$_GET);
		break;
}
?>