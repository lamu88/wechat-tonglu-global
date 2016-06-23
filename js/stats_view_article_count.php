<?php
/*基于HTML页面的页面访问统计*/
require_once('../load.php');
$id = $_GET['id']; //当前页面的文章ID
$count = $app->action('article','stats_view_article_count',$id,true);
if(empty($count) || $count < 0){
$count = 1;
}
echo 'document.getElementById("view_count").innerHTML = '.$count.';'."\n";
exit;
?>