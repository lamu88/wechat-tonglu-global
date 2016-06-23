<div class="contentbox">
	<div class="openwindow"><img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/><br />正在删除，请稍后。。。</div>
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="4" align="left">旗下所有网站</th>
	</tr>
    <tr>
	   <th>网站名称</th>
	   <th>网站链接</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($list)){ 
	foreach($list as $row){
	?>
	<tr>
	<td><?php echo $row['name'];?></td>
	<td><a href="<?php echo $row['url'];?>"><?php echo $row['url'];?></a></td>
	<td>
	<a href="systemconfig.php?type=other_site_info&id=<?php echo $row['id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>" class="dellink"/>
	</td>
	</tr>
	<?php } } ?>
	 </table>
</div>
<?php  $thisurl = ADMIN_URL.'systemconfig.php'; ?>
<script type="text/javascript">
<!--
   $('.dellink').click(function(){
		id = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			$.post('<?php echo $thisurl;?>',{action:'dels',id:id},function(data){
				if(data == ""){
					thisobj.hide(300);
				}else{
					alert(data);	
				}
			});
		}else{
			return false;	
		}
   });
   
-->
</script>