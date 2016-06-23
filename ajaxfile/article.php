<?php
require_once('../load.php');

if(isset($_POST['action'])){ 
	switch($_POST['action']){
		case 'alticlelist':
			$app->action('article','ajax_article_list',$_POST['cid'],$_POST['page'],$_POST['type'],intval($_POST['list']));
			break;
		case 'getarticle': //AJAX获取文章信息
			$app->action('article','ajax_getarticle_info',$_POST['article_id']);
			break;
	}
	exit;
}
exit;
?>