<?php
require_once('load.php');
$type = isset($_GET['type']) ? $_GET['type'] : "email_config";
switch($type){
	case 'email_config':
		$app->action('email','email_config');
		break;
	case 'tpl':
		$app->action('email','set_send_tpl');
		break;
	case 'send':
		$app->action('email','set_send_open');
		break;
	case 'sendmail':
		$app->action('email','send_test',$_GET);
		break;
}
?>