<?php
require_once("load.php");
$act = isset($_POST['action']) ? $_POST['action'] : "";
if(!empty($act)){
	$app->action('hbjilu',$act,$_POST);
	exit;
}
if(!isset($_GET['type'])) $_GET['type'] = '';
$app->action('hbjilu',$_GET['type'],$_GET);
?>