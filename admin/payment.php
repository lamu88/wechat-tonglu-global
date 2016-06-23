<?php
require_once('load.php');
if($_POST['action']){
 $app->action('order',$_POST['action'],$_POST);
 exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'list';
switch($type){
	case 'list':
		$app->action('order','paymentlist');
		break;
	case 'info':
		$app->action('order','paymentinfo',isset($_GET['id'])?$_GET['id'] : 0);
		break;
	default:
		$app->action('order','paymentlist');
		break;
}

?>