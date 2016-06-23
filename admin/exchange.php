<?php
require_once('load.php');
$type = isset($_GET['type'])&&!empty($_GET['type']) ? $_GET['type'] : 'lists';
$app->action('exchange',$type,$_GET);
?>