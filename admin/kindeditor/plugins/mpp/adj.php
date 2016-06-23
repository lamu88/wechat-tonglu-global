<?php
require_once('ll.php');
if(isset($_REQUEST['action'])&&!empty($_REQUEST['action'])){
	$app->action('page',$_REQUEST['action'],$_REQUEST);
	exit;
}
?>