<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="4" align="left">友情链接列表</th>
	</tr>
    <tr>
	   <th>链接名称</th>
	   <th>链接地址</th>
	   <th>链接LOGO</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($lists)){ 
	foreach($lists as $row){
	?>
	<tr>
	<td><?php echo $row['link_name'];?></td>
	<td><a href="<?php echo $row['link_url'];?>"><?php echo $row['link_url'];?></a></td>
	<td><img src="../<?php echo $row['link_logo'];?>" alt="<?php echo $row['link_name'];?>" width="100"/></td>
	<td>
	<a href="friendlink.php?type=edit&id=<?php echo $row['link_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['link_id'];?>" class="dellink"/>
	</td>
	</tr>
	<?php } } ?>
	 </table>
</div>
<?php  $thisurl = ADMIN_URL.'friendlink.php'; ?>
<script type="text/javascript">
<!--
   $('.dellink').click(function(){
		id = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'dels',id:id},function(data){
				removewindow();
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