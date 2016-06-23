<div class="contentbox">
<style type="text/css">
.menu_content .tab{ display:none}
.nav .active{
	 /*background: url(<?php echo $this->img('manage_r2_c13.jpg');?>) no-repeat;*/
	 background-color:#F5F5F5;
} 
.nav .other{
	/* background: url(<?php echo $this->img('manage_r2_c14.jpg');?>) no-repeat;*/
	 background-color:#E9E9E9;
} 
h2.nav{ border-bottom:1px solid #B4C9C6;font-size:13px; height:25px; line-height:25px; margin-top:0px; margin-bottom:0px}
h2.nav a{ color:#999999; display:block; float:left; height:24px;width:113px; text-align:center; margin-right:1px; margin-left:1px; cursor:pointer}
.addi{ margin:0px; padding:0px;}
.vipprice td{ border-bottom:1px dotted #ccc}
.vipprice th{ background-color:#EEF2F5}
</style>
 <h2 class="nav">
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=clist">专区列表</a>  
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=cinfo">添加专区</a> 
 <a class="active" href="<?php echo ADMIN_URL;?>topgoods.php?type=lists">产品列表</a> 
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=info">添加产品</a> 
</h2>

 <div class="menu_content">
 	<table cellspacing="2" cellpadding="5" width="100%">
    <tr>
	   <th width="20%">大分类</th>
	   <th>小分类</th>
	   <th>图片</th>
	   <th>名称</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt))foreach($rt as $row){
	?>
	<tr>
		<td><?php echo empty($row['bname']) ? '---':$row['bname'];?></td>
		<td><?php echo $row['sname'];?></td>
		<td><a href="<?php echo !empty($row['url']) ? $row['url'] : SITE_URL.'product.php?id='.$row['goods_id'];?>" target="_blank"><img src="../<?php echo !empty($row['img']) ? $row['img'] : $row['goods_thumb'];?>" width="60" /></a></td>
		<td><?php echo !empty($row['gname']) ? $row['gname'] : $row['goods_name'];?></td>
		<td><a href="<?php echo ADMIN_URL.'topgoods.php?type=info&id='.$row['gid'];?>">编辑</a> <a href="javascript:;" onclick="return ajax_del_topgoods(<?php echo $row['gid'];?>,this)">删除</a></td>
	</tr>
	<?php } ?>
	</table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
 </div> 
 
</div>
<?php  $thisurl = ADMIN_URL.'topgoods.php'; ?>
<script type="text/javascript">
function ajax_del_topgoods(id,obj){
	if(confirm("确定删除吗？")){
		$.get('<?php echo $thisurl;?>',{type:'ajax_del_topgoods',gid:id},function(data){
			if(data == ""){
				$(obj).parent().parent().hide(300);
			}else{
				alert(data);
			}
		});
	}
	return false;
}
</script>	 
