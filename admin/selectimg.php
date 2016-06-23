<?php
require_once('load.php');
if($_POST['action']){
	$app->action('system',$_POST['action'],$_POST);
	exit;
}
$type = isset($_GET['type']) ? $_GET['type'] : "selectimg";
$app->action('system',$type,$_GET);
?>