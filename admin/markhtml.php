<?php
require_once('load.php');

if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'marknav':
			$app->action('markhtml','ajax_marknav',$_POST['kk'],$_POST['types'],isset($_POST['times'])? $_POST['times'] : 'all');
			break;
		case 'markall': 
			$app->action('markhtml','ajax_markall',$_POST['kk'],$_POST['types']);
			break;
	}
	exit;
}


if(!isset($_GET['type']) || empty($type)) $type = 'all';
$type = (!isset($_GET['type']) || empty($_GET['type']) || !in_array($_GET['type'],array('nav','category','all','article','index'))) ? 'all' : $_GET['type'];

switch($type){
	case 'all':
		$app->action('markhtml','markhtml','all');
		break;
	case 'nav':
		$app->action('markhtml','markhtml','nav');
		break;
	case 'category':
		$app->action('markhtml','markhtml','category');
		break;
	case 'article':
		$app->action('markhtml','markhtml','article');
		break;
	case 'index':
		$app->action('markhtml','markhtml','index');
		break;
	default:
		$app->action('markhtml','markhtml','all');
		break;
}


?>