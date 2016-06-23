<?php
/*
*弹出框 处理页面入口
*/
require_once('../load.php');
if(isset($_POST['action']) && $_POST['action']=='box'){
	$app->action("common","ajax_popbox",$_POST['boxname'],$_POST);
}
exit;
?>