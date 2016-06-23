<?php
require_once('../load.php');

if(isset($_POST['action'])&&$_POST['action']=='feedback'){
	$app->action('freecatalog','ajax_get_freecatalog',$_POST['message']);
	exit;
} 
?>