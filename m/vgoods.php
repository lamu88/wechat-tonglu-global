<?php
require_once('load.php');
$type = isset($_GET['type'])&&!empty($_GET['type']) ? trim($_GET['type']) : 'checkout';
$app->action('vgoods',$type,$_GET);
?>