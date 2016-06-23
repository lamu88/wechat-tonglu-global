<?php
require_once("load.php");
$act = isset($_POST['action']) ? $_POST['action'] : "";
if(!empty($act)){
	$app->action('muban',$act,$_POST);
	exit;
}
if(!isset($_GET['type'])) $_GET['type'] = '';
$app->action('muban',$_GET['type'],$_GET);
?>