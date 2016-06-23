<?php
define('LOGIN',1);
require_once('load.php');

if($app->action('shop','is_login')){
 	Import::basic()->redirect(SITE_URL.'shop/'); exit;
}

//ajax登录
if(isset($_POST['action']) && $_POST['action']=="login"){
	$app->action('shop','ajax_user_login',$_POST);
	exit;
}

$app->action('shop','login');

?>