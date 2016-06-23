<?php
require_once('../load.php');

if(isset($_POST['action'])&&$_POST['action']=='feedback'){
	$app->action('feedback','ajax_feedback',$_POST['message']);
	exit;
} 
if(isset($_POST['action'])&&$_POST['action']=='getmessagelist'){
	$app->action('feedback','ajax_getmessagelist',$_POST);
	exit;
} 

?>