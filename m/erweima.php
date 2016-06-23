<?php
require_once('load.php');
if(isset($_REQUEST['action'])&&!empty($_REQUEST['action'])){
	$app->action('erweima',$_REQUEST['action'],$_REQUEST);
	exit;
}
$action = isset($_GET['act'])&&!empty($_GET['act']) ? $_GET['act'] : "index";
$app->action('erweima',$action,$_GET);
?>