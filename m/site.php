<?php
require_once('load.php');
if(isset($_REQUEST['action'])){
	$app->action('site',$_REQUEST['action'],$_POST);
	exit;
}
$act = (!isset($_GET['act'])||empty($_GET['act']))? 'index' : $_GET['act'];
$app->action('site',$act,$_GET);
?>