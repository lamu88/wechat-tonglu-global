<?php
require_once("load.php");
$act = isset($_POST['action']) ? $_POST['action'] : "";
if(!empty($act)){
	$m = isset($_POST['m']) ? $_POST['m'] : "page";
	$app->action($m,$act,$_POST);
	exit;
}

$f = isset($_GET['f'])&&!empty($_GET['f']) ? trim($_GET['f']) : 'index';
$m = isset($_GET['m'])&&!empty($_GET['m']) ? trim($_GET['m']) : 'page';
$app->action($m,$f,$_GET);
?>