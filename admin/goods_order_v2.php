<?php
require_once('load.php');
if($_REQUEST['action']){
$app->action('order_v2',$_REQUEST['action'],$_POST);
exit;
}

$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'orderlist';
$app->action('order_v2',$type,$_GET);
?>