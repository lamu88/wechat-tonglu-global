<?php
require_once('load.php');

if($_POST['action']){
	switch($_POST['action']){
		case 'bathdel':
			$app->action('user','ajax_bathdel',$_POST['ids']);
			break;
		case 'activeop': //激活
			$app->action('user','ajax_activeop',$_POST);
			break;
		case 'get_ress':
			$app->action('user','ajax_get_ress',$_POST);
			break;
		case 'delress':
			$app->action('user','ajax_delress',$_POST['id'],$_POST['uid']);
			break;
		case 'change_user_points_money':
			$app->action('user','ajax_change_user_points_money',$_POST);
			break;
		case 'pointinfo':
			$app->action('user','ajax_user_pointchange'); 
			break;
		case 'mymoney':
			$app->action('user','ajax_user_mymoney'); 
			break;
		case 'getuser':
			$app->action('user','ajax_getuser',$_POST['message']); 
			break;
		case 'sendmessage':
			$app->action('user','ajax_user_sendmessage',$_POST); 
			break;
		case 'ajax_save_suppliers_info':
			$app->action('user','ajax_save_suppliers_info',$_POST); 
			break;
		case 'check_salesmen_brand':
			$app->action('user','ajax_check_salesmen_brand',$_POST); 
			break;
		case 'ajax_rp_mes':
			$app->action('user','ajax_rp_mes',$_POST); 
			break;
		default:
			$app->action('user',$_POST['action'],$_POST); 
			break;
	}
	exit;
		
}
if(isset($_GET['type'])){
	switch($_GET['type']){
		case 'list':
			$app->action('user','user_list');
			break;
		case 'info':
			$app->action('user','user_info',($_GET['id'] ?  $_GET['id'] : 0));
			break;
		case 'userress':
			$app->action('user','user_consignee_address',($_GET['id'] ?  $_GET['id'] : 0)); //用户收货地址
			break;
		case 'setjifen':
			$app->action('user','user_setjifen');
			break;
		case 'levellist':
			$app->action('user','user_level_list');
			break;
		case 'levelinfo':
			$app->action('user','user_level_info',($_GET['id'] ?  $_GET['id'] : 0));
			break;
		case 'send_message':	
			$app->action('user','user_send_message'); //消息群发
			break;
		case 'messagelist':	
			$app->action('user','user_message_list'); //消息列表
			break;
		case 'mesinfo':	
			$app->action('user','user_mesinfo',($_GET['id'] ?  $_GET['id'] : 0)); 
			break;
		case 'send_message_frame':	
			$app->action('user','user_send_message_frame'); 
			break;
		case 'salesmen':	
			$app->action('user','suppliers_salesmen'); 
			break;
		case 'salesmen_manage':
			$app->action('user','salesmen_manage'); 
			break;
		default:
			$app->action('user',$_GET['type'],$_GET);
			break;
	}
}else{
	$app->jump('user.php?type=list'); exit;
}
?>