<?php
require_once("load.php");

if($_POST['action']){
	switch($_POST['action']){
		case 'delnav':
			$app->action('system','ajax_delnav',$_POST['ids']);
			break;
		case 'activeop': 
			 $app->action('system','ajax_active',$_POST);
			break;
		case 'vieworder':
			$app->action('system','ajax_vieworder',$_POST);
			break;
		case 'dels':
			$app->action('system','ajax_del_lis_website',$_POST);
			break;
		case 'clearcache':
			$app->action('system','ajax_clearcache');
			break;
		default:
			$app->action('system',$_POST['action'],$_POST);
	}
	exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : "basic";

switch($type){
	case 'basic': //
		$app->action('system','system_basic');
		break;
	case 'seo': //
		$app->action('system','system_seo');
		break;
	case 'arg': //
		$app->action('system','system_arg');
		break;
	case 'nav_list':
		$app->action('system','nav_list');
		break;
	case 'nav_add':
	case 'nav_edit':
		$app->action('system','nav_info',$type,(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'other_site_info':
		$app->action('system','other_site_info',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'other_site_list':
		$app->action('system','other_site_list');
		break;
	case 'clear':
		$app->action('system','clearcache');
		break;
	case 'custom_menu':
		$app->action('system','custom');
		break;
	default:
		$app->action('system',$type,$_GET);
		
}
?>