<?php
require_once('load.php');

if($_POST['action']){
	switch($_POST['action']){
		case 'edit_add': 
		 	 $data['link_logo'] = $_POST['link_logo'];
			 $data['link_url'] = $_POST['link_url'];
			 $data['link_name'] = $_POST['link_name'];
			 $data['width'] = $_POST['width'];
			 $data['height'] = $_POST['height'];
		     $app->action('friendlink','ajax_edit_add',$data,($_POST['lid'] ? $_POST['lid'] : 0));
			break;
		case 'dels': //
			$app->action('friendlink','ajax_dels',$_POST['id']);
			break;
	}
	exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : "list";

switch($type){
	case 'list': //
		$app->action('friendlink','lists');
		break;
	case 'add': //添加
	case 'edit': //编辑
		$app->action('friendlink','add_edit',$type,($_GET['id'] ?  $_GET['id'] : 0));
		break;	
	default:
	   $app->action('friendlink','lists');
	   break;
}
?>