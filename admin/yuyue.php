<?php
require_once('load.php');

if($_POST['action']){
	$app->action('yuyue',$_POST['action'],$_POST);
	exit;
}
$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'baominglist';
$app->action('yuyue',$type,$_GET);
?>