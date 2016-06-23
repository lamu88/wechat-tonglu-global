<?php
require_once('load.php');

if($_POST['action']){
	$app->action('cuxiao',$_POST['action'],$_POST);
	exit;
}

$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'clist';
$app->action('cuxiao',$type,$_GET);
?>