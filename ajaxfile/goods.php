<?php
require_once('../load.php');
//require_once(SYS_PATH.'inc'.DS.'func.time.php');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
switch($action){
	case 'addcart':
		$app->action('product','ajax_add_cart',$_POST); //添加到购物车
		break;
	case 'addtocoll':
		$app->action('product','ajax_add_tocoll',$_POST['goods_id']); //添加收藏
		break;
	case 'comment':
		$app->action('product','ajax_comment',$_POST); //提交评论
		break;
	case 'getcommentlist':  //获取商品评论
		$app->action('product','ajax_getcommentlist',$_POST); 
		break;
	case 'categoods':
		$app->action('product','ajax_categoods_list',$_POST['page'],$_POST['cid']); //提交到控制器处理
		break;
	case 'getgoodsname':
		$app->action('product','ajax_getgoodsname',$_POST['goods_id']); //提交到控制器处理
		break;
	exit;
}
?>