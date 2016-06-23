<?php
require_once('load.php');

if(isset($_REQUEST['action'])&&$_REQUEST['action']=='feedback'){
	$app->action('feedback','ajax_feedback',$_POST['messag']);
	exit;
} 
if(isset($_REQUEST['action'])&&$_REQUEST['action']=='getmessagelist'){
	$app->action('feedback','ajax_getmessagelist',$_POST);
	exit;
} 
//$app->action('feedback','index');
?>