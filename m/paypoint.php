<?php
require_once('load.php');
$act = isset($_GET['act']) ? trim($_GET['act']) : 'paypoint';
$app->action('shop',$act,$_GET);
?>