<?php
require_once('load.php');
$act = isset($_POST['action']) ? $_POST['action'] : "";
if(!empty($act)){
	$app->action('vgoods',$act,$_POST);
	exit;
}

$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'lists';
$app->action('vgoods',$type,$_GET);
?>