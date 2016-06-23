<?php
require_once('../load.php');
if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
		case 'changeprice':
			$app->action('shopping','ajax_change_price',$_POST);
			break;
		case 'delcartid':
			$app->action('shopping','ajax_delcart_goods',isset($_POST['id'])? $_POST['id'] : 0);
			break;
		case 'jisuan_shopping':
			$app->action('shopping','ajax_jisuan_shopping',$_POST);
			break;
		case 'change_jifen':
			$app->action('shopping','ajax_change_jifen',$_POST['checked']);
			break;
		
	}
	exit;
}
?>
