<?php
require_once('load.php');
$act = isset($_GET['act']) ? trim($_GET['act']) : 'paymoney';
$app->action('shop',$act,$_GET);
?>