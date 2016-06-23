<?php 
require_once('load.php');

//ajax登录
if(isset($_REQUEST['action'])){
	switch(trim($_REQUEST['action'])){ 
		case 'register':
			$app->action('user','ajax_user_register',$_POST);
			break;
		case 'ressinfoop':
			$app->action('user','ajax_ressinfoop',$_POST);
			break;
		case 'updateinfo':
			$app->action('user','ajax_updateinfo',$_POST);
			break;
		case 'updatepass':
			$app->action('user','ajax_updatepass',$_POST);
			break;
		case 'get_ress':
			$app->action('user','ajax_get_ress',$_POST);
		case 'get_peisong':
			$app->action('user','ajax_get_ge_peisong',$_POST);
			break;
		case 'delress':
			$app->action('user','ajax_delress',$_POST['id']);
			break;
		case 'getorderlist':
			$app->action('user','ajax_getorderlist',$_POST);
			break;
		case 'order_op':
			$app->action('user','ajax_order_op',(isset($_POST['id'])? $_POST['id'] : 0),$_POST['type']);  
			break;	
		case 'getuid':
			$app->action('user','ajax_getuid');
			break;
		case 'delmycoll':
			$app->action('user','ajax_delmycoll',$_POST['ids']);
			break;
		case 'feedback':
			$app->action('user','ajax_feedback',$_POST['message']);
			break;
		case 'delmes':
			$app->action('user','ajax_delmessages',$_POST['mes_id']);
			break;
		case 'delcomment':
			$app->action('user','ajax_delcomment',$_POST['id']);
			break;	
		case 'rp_pass':
			$app->action('user','ajax_rp_pass',$_POST);  //重设密码	
		default:
			$app->action('user',$_REQUEST['action'],$_POST);	
			break;
	}
	exit;
}

$action = isset($_GET['act'])&&!empty($_GET['act']) ? $_GET['act'] : "default";
switch($action){
	case 'login': //用户登录
		$app->action('user','login');
		break;
	case 'register': //用户注册
		$app->action('user','register');
		break;	
	case 'default':
		$app->action('user','index'); //用户后台
		break;
/*	case 'myorder': //用户订单
		$app->action('user','orderlist'); 
		break;*/
	case 'orderinfo': //用户订单
		$app->action('user','orderinfo',isset($_GET['order_id']) ? $_GET['order_id'] : ""); 
		break;
	case 'address_list': //收货地址
		$app->action('user','address'); 
		break;	
	case 'mycoll':  //用户收藏
		$app->action('user','mycolle'); 
	    break;
	case 'myinfo':   //用户资料
		$app->action('user','userinfo'); 
		break;
	case 'editpass':
		$app->action('user','editpass');  //修改密码
		break;
	case 'logout';  //用户退出
		$app->action('user','logout'); //用户注销==》清空session
		break;
	case 'forgetpass':
		$app->action('user','forgetpass'); 
		break;
	case 'regsuccess':
		$app->action('user','user_regsuccess_mes'); 
		break;
	case 'tuijian':
		$app->action('user','user_tuijian'); 
		break;
	case 'question':
		$app->action('user','messages'); 
		break;
	case 'mycomment':
		$app->action('user','comment'); 
		break;
	default:
		$app->action('user',$_GET['act'],$_GET);
		break;
}
?>