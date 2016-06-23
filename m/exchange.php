<?php
require_once('load.php');
$id = isset($_GET['id'])&&!empty($_GET['id']) ? intval($_GET['id']) : 0;
if($id>0){
$app->action('exchange','goods',$id);
}else{
$app->action('exchange','index');
}
?>