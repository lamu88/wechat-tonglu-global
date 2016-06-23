<?php
require_once('load.php');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
if(!empty($action)){
	$app->action('product',$action,$_REQUEST);
	exit;
}

$id = isset($_GET['id'])&&!empty($_GET['id']) ? intval($_GET['id']) : 0;
$app->action('product','index',$id);
?>