<?php
require_once('../load.php');
if(isset($_POST['type']) && !empty($_POST['type'])){
	$app->action($_POST['func'],$_POST['type'],$_POST);
}
exit;
?>