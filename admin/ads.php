<?php
require_once('load.php');

if($_POST['action']){
	switch($_POST['action']){
		case 'addadtag': //添加广告标签
			 $data['ad_name'] = $_POST['a_name'];
			 $data['ad_width'] = $_POST['a_width'];
			 $data['ad_height'] = $_POST['a_height'];
			 $data['ad_desc'] = $_POST['a_desc'];
			 $data['is_show'] = $_POST['active'];
		    $app->action('ads','ajax_adtags_info',$data,($_POST['tid'] ? $_POST['tid'] : 0));
			break;
		case 'addads': //添加广告
			 $data['ad_name'] = $_POST['a_name'];
			 $data['tid'] = $_POST['tid'];
			 $data['ad_img'] = $_POST['uploadfile'];
			 $data['ad_file'] = $_POST['ad_file'];
			 $data['ad_url'] = $_POST['ad_url'];
			 $data['is_show'] = $_POST['active'];
			 $data['cat_id'] = $_POST['cat_id'];
			 $data['remark'] = $_POST['mark'];
			 $data['type'] = $_POST['type'];
		    $app->action('ads','ajax_addads_info',$data,($_POST['pids'] ? $_POST['pids'] : 0));
			break;
		case 'deladstag'://删除广告标签
			$app->action('ads','ajax_deladstag',$_POST['tids']);
			break;
		case 'delads': //删除广告
			$app->action('ads','ajax_delads',$_POST['pids']);
			break;
		case 'activeop': //激活标签
			 $data['is_show'] = $_POST['active'];
			 $data['uptime'] = time(); 
			 $app->action('ads','ajax_adtags_info',$data,$_POST['tid']);
			break;
		case 'activeadsop': //激活广告
			 $data['is_show'] = $_POST['active'];
			 $data['uptime'] = time(); 
			 $app->action('ads','ajax_addads_info',$data,$_POST['pid']);
			break;
		case 'getcateoption': //根据类型获取商品分类的菜单还是文章分类的菜单
			 $app->action('ads','ajax_getcateoption',$_POST['type']);
			break;
		default:
			 $app->action('ads',$_POST['action'],$_POST);
			break;
	}
	exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : "adslist";

switch($type){
	case 'adslist': //广告列表
		$app->action('ads','adslist');
		break;
	case 'adstaglist': //广告标签列表
		$app->action('ads','adstaglist');
		break;
	case 'adstag_add': //添加广告标签
	case 'adstag_edit':
		$app->action('ads','adstag_info',$type,($_GET['id'] ?  $_GET['id'] : 0));
		break;
	case 'ads_add': //添加广告
	case 'ads_edit': //编辑广告
		$app->action('ads','ads_info',$type,($_GET['id'] ?  $_GET['id'] : 0));
		break;	
	default:
	   $app->action('ads','adslist');
	   break;
}

?>