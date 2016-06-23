<?php
require_once('../load.php');

if(isset($_REQUEST['action'])){
	switch(trim($_REQUEST['action'])){ 
			case 'delgoods': //删除商品
				$app->action('suppliers','ajax_delgoods',$_POST['ids']);
				break;
			case 'order_op':
				$app->action('suppliers','ajax_order_op',(isset($_POST['id'])? $_POST['id'] : 0),$_POST['type']);  
				break;	
			case 'getorderlist':
				$app->action('suppliers','ajax_getorderlist',$_POST);
				break;
			case 'orderop': //发货 收货 付款 取消 ===
				$app->action('suppliers','ajax_order_option',$_POST['message']);
				break;
			case 'activeop': //商品上下架
				$app->action('suppliers','ajax_activeop',$_POST);
			case 'return_order_text':
				$app->action('suppliers','return_order_text',$_POST['order_id']);
				break;
				
			default:
				echo "run defult...";
				break;
	}
}	
?>