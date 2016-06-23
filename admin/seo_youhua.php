<?php
require_once("load.php");

/*if($_POST['action']){
	switch($_POST['action']){
		case 'check_cat_name': 
			$app->action('con_case','ajax_check_cat_name',$_POST);
			break;
		case 'delcate':
			$app->action('con_case','ajax_delcate',$_POST['ids']);
			break;
		case 'delarticle':
			$app->action('con_case','ajax_delarticle',$_POST['ids']);
			break;
		case 'activeop': 
			 $app->action('con_case','ajax_active',$_POST);
			break;
		case 'alt_activeop': 
			 $app->action('con_case','ajax_alt_activeop',$_POST);
			break;
		case 'vieworder':
		    $app->action('con_case','ajax_vieworder',$_POST);
			break;
		default:
		    die('非法操作！');
	}
	exit;
}
*/
$type = isset($_GET['type']) ? $_GET['type'] : "cate_xwzx";

switch($type){
	case 'cate_xwzx': 
		$app->action('seo','seo_category',isset($_POST)? $_POST : array(),'new'); //分类新闻资讯
		break;
	case 'cate_mbal': 
		$app->action('seo','seo_category',isset($_POST)? $_POST : array(),'case'); //分类模板案例
		break;
	case 'cate_khlb': 
		$app->action('seo','seo_category',isset($_POST)? $_POST : array(),'customer'); //分类客户列表
		break;
	case 'cate_wzjs': 
		$app->action('seo','seo_category',isset($_POST)? $_POST : array(),'web'); //分类客户列表
		break;
	case 'cate_con_xwzx': 
		$app->action('seo','seo_category_content',isset($_POST)? $_POST : array(),'new'); //分类新闻资讯内容
		break;
	case 'cate_con_mbal': 
		$app->action('seo','seo_category_content',isset($_POST)? $_POST : array(),'case'); //分类模板案例内容
		break;
	case 'cate_con_khlb': 
		$app->action('seo','seo_category_content',isset($_POST)? $_POST : array(),'customer'); //分类客户列表内容
		break;
	case 'cate_con_wzjs': 
		$app->action('seo','seo_category_content',isset($_POST)? $_POST : array(),'web'); //分类客户列表内容
		break;
	
		
}
?>