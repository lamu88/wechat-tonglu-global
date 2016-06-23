<?php
require_once('../load.php');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
switch($action){
	case 'getgoodslist':
		$app->action('catalog','ajax_getcategoodslist',$_POST['goodswhere']); 
		break;
	exit;
}
if(isset($_REQUEST['action'])) die;
//ajax end 
?>