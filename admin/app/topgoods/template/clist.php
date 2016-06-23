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
 <a class="active" href="<?php echo ADMIN_URL;?>topgoods.php?type=clist">专区列表</a>  
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=cinfo">添加专区</a> 
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=lists">产品列表</a> 
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=info">添加产品</a> 
</h2>

 <div class="menu_content">
 	<table cellspacing="2" cellpadding="5" width="100%">
    <tr>
	   <th width="40%">名称</th>
	   <th>链接</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($catelist))foreach($catelist as $row){
	?>
	<tr>
		<td>+<?php echo $row['name'];?></td>
		<td><a href="<?php echo SITE_URL.'m/taggoods.php?cid='.$row['id'];?>" target="_blank"><?php echo SITE_URL.'m/taggoods.php?cid='.$row['id'];?></a></td>
		<td>&nbsp;<a href="<?php echo ADMIN_URL;?>topgoods.php?type=cinfo&id=<?php echo $row['id'];?>"><img src="<?php echo $this->img('icon_edit.gif');?>" /></a><img src="<?php echo $this->img('icon_view.gif');?>"  onclick="ajax_showbox('<?php echo $row['id'];?>')" />
		<a href="javascript:;" onclick="ajax_del_topgoodscate(<?php echo $row['id'];?>,this)" style="color:#FF0000">删除</a>
		</td>
	</tr>
	
			<?php 
			if(!empty($row['cat_id']))foreach($row['cat_id'] as $rows){
			?>
			<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rows['name'];?></td>
				<td><a href="<?php echo SITE_URL.'m/taggoods.php?cid='.$rows['id'];?>" target="_blank"><?php echo SITE_URL.'m/taggoods.php?cid='.$rows['id'];?></a></td>
				<td>&nbsp;<a href="<?php echo ADMIN_URL;?>topgoods.php?type=cinfo&id=<?php echo $rows['id'];?>"><img src="<?php echo $this->img('icon_edit.gif');?>" /></a><img src="<?php echo $this->img('icon_view.gif');?>" onclick="ajax_showbox('<?php echo $rows['id'];?>')" />
				<a href="javascript:;" onclick="ajax_del_topgoodscate(<?php echo $rows['id'];?>,this)" style="color:#FF0000">删除</a>
				</td>
			</tr>
			<?php } ?>
		<?php } ?>
	</table>
 </div> 
 
</div>
<?php  $thisurl = ADMIN_URL.'topgoods.php'; ?>
<script type="text/javascript">

function ajax_showbox(gcid){
	JqueryDialog.Open('分类产品','<?php echo ADMIN_URL;?>ajax_cate_goods.php?cid='+gcid,900,600,'frame');
}

function ajax_del_topgoodscate(cid,obj){
	if(confirm("确定删除吗？")){
		$.get('<?php echo $thisurl;?>',{type:'ajax_del_topgoodscate',tcid:cid},function(data){
			if(data == ""){
				$(obj).parent().parent().hide(300);
			}else{
				alert(data);
			}
		});
	}
}

</script>
	 
