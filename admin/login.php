<?php
define('LOGIN',1);
require_once('load.php');

if($app->action('manager','is_login')){
 	Import::basic()->redirect(ADMIN_URL); exit;
}

//ajax登录
if(isset($_POST['action']) && $_POST['action']=="login"){
	$data['adminname'] = $_POST['adminname'];
	$data['password'] = $_POST['password'];
	$data['vifcode'] = $_POST['vifcode'];
	$app->action('manager','login',$data);
	exit;
}

$app->action('manager','index');

?>