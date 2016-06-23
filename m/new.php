<?php
require_once('load.php');
$cid = isset($_GET['cid'])&&!empty($_GET['cid']) ? intval($_GET['cid']) : 0;
$page = isset($_GET['page'])&&!empty($_GET['page']) ? intval($_GET['page']) : 1;
$id = isset($_GET['id'])&&!empty($_GET['id']) ? intval($_GET['id']) : 0;

$app->action('new',($id>0?'article':'news'),($id>0?$id:$cid),$page);
?>