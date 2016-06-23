<?php
require_once("load.php");

if($_POST['action']){
	switch($_POST['action']){
		case 'brand_dels':
			$app->action('goods','ajax_brand_dels',$_POST['ids']);
			break;
		case 'cate_dels':
			$app->action('goods','ajax_cate_dels',$_POST['ids']);
			break;
		case 'cate_active':
			$app->action('goods','ajax_cate_active',$_POST);
			break;
		case 'brand_active': 
			 $app->action('goods','ajax_brand_active',$_POST);
			break;
		case 'brand_order':
		    $app->action('goods','ajax_brand_order',$_POST);
			break;
		/****************** look 添加 开始   ***********************************/	
		case 'goods_order_market':
		    $app->action('goods','ajax_goods_order_market',$_POST);
			break;
		case 'goods_order_shop':
		    $app->action('goods','ajax_goods_order_shop',$_POST);
			break;
		case 'goods_order_pifa':
		    $app->action('goods','ajax_goods_order_pifa',$_POST);
			break;
		case 'ajax_upload': 
		    $app->action('goods','ajax_upload_cache_photo',$_POST);
			break;
		case 'delgoods': //删除商品
			$app->action('goods','ajax_delgoods',$_POST['ids'],(isset($_POST['reduction'])?$_POST['reduction'] : ""));
			break;
		case 'cate_sort': //商品分类排序
		    $app->action('goods','ajax_catesort',$_POST);
			break;
		case 'attr_sort': //商品属性排序
		    $app->action('goods','ajax_attrsort',$_POST);
			break;
		case 'goods_attr_del': //删除商品属性
			$app->action('goods','ajax_attribute_del',$_POST['id']);
			break;
		case 'goods_attr_item_del': //删除商品属性下的商品属性
			$app->action('goods','ajax_goods_attr_del',$_POST['id']);
			break;
		case 'del_subcate_id': //删除当前商品的子分类
			$app->action('goods','ajax_del_subcate',$_POST);
			break;
		case 'delgallery': //删除商品相册图片
			$app->action('goods','ajax_delgallery_photo',$_POST['id']);
			break;
		case 'activeop': //商品上下架
			$app->action('goods','ajax_activeop',$_POST);
			break;	
			
		case 'bathdel_comment': //删除商品评论
			$app->action('goods','ajax_del_comment',$_POST['ids']);
			break;
		case 'active_comment': //商品评论激活
			$app->action('goods','ajax_active_comment',$_POST);
			break;
		case 'savekeyword':
			$app->action('goods','set_search_keyword',$_POST['keys']); //保存搜索关键字
			break;
		case 'delgoodsgift':
			$app->action('goods','ajax_delgoodsgift',$_POST); //保存搜索关键字
			break;
		case 'catalog_dels':
			$app->action('goods','ajax_catalog_dels',$_POST['ids']); //保存搜索关键字
			break;
		case 'goodsedit': //ajax更改商品信息
			$app->action('goods','ajax_update_goods_info',$_POST); //
			break;
		case 'delgoods_suppliers': //删除供应商商品
			$app->action('goods','ajax_delgoods_suppliers',$_POST);
			break;
		case 'activeop_suppliers': //供应商商品上下架
			$app->action('goods','ajax_activeop_suppliers',$_POST);
			break;
		case 'goodsedit_suppliers': //ajax更改商品信息
			$app->action('goods','ajax_update_goods_info_suppliers',$_POST); //
			break;
		default:
			$app->action('goods',$_POST['action'],$_POST); //
			break;
	}
	exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : "goods_list";

switch($type){
	case 'goods_list': 
		$app->action('goods','goods_list');
		break;
	case 'goods_list_check': 
		$app->action('goods','goods_list_check');
		break;
	case 'goods_info':
		$app->action('goods','goods_info',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'batch_add':
		$app->action('goods','goods_batch_add',(isset($_GET['op']) ?  $_GET['op'] : ""));
		break;
	case 'cate_list': 
		$app->action('goods','cate_list');
		break;
	case 'cate_info':
		$app->action('goods','cate_info',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'band_list': 
		$app->action('goods','brand_list');
		break;
	case 'band_info':
		$app->action('goods','brand_info',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'keyword': 
		$app->action('goods','set_search_keyword');
		break;
	case 'batch_add_text': 
		$app->action('batchupload','batchupload');
		break;
	case 'download_tpl': 
		$app->action('batchupload','download_tpl');
		break;
	case 'goods_attr_info':
		$app->action('goods','goods_attr_info',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'goods_attr_list':
		$app->action('goods','goods_attr_list');
		break;
	case 'comment_list':
		$app->action('goods','goods_comment_list');
		break;
	case 'comment_info':
		$app->action('goods','goods_comment_info',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'spend_gift':
		$app->action('goods','goods_spend_gift',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'freecatalog':
		$app->action('goods','freecatalog');
		break;
	case 'freecataloginfo':
		$app->action('goods','freecataloginfo',(isset($_GET['id']) ?  $_GET['id'] : 0));
		break;
	case 'zhuanyi':
		$app->action('goods','zhuanyi_goods');
		break;
	default:
		$app->action('goods',$type,$_GET);
		break;
	
		
}
?>