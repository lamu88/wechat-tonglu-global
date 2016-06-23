<?php
require_once("load.php");
$act = isset($_POST['action']) ? $_POST['action'] : "";
if(!empty($act)){
	$app->action('weixin',$act,$_POST);
	exit;
}
if(!isset($_GET['type'])) $_GET['type'] = '';
$app->action('weixin',$_GET['type'],$_GET);
?>