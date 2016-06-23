<?php
/*基于HTML页面的页面访问统计*/
require_once('../load.php');
$id = $_GET['id']; //当前页面的商品ID
$count = $app->action('product','stats_view_goods_count',$id,true);
if(empty($count) || $count < 0){
$count = 1;
}
?>