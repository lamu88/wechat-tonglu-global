<?php
require_once('load.php');
if($_REQUEST['action']){
$app->action('shopping',$_REQUEST['action'],$_POST);
exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'shoppinglist';
$app->action('shopping',$type,$_GET);
?>