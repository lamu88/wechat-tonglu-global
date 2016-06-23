<div class="contentbox">
	<form id="form1" name="form1" method="post" action="">
     <table cellspacing="0" cellpadding="5" width="100%" align="left">
	  <tr>
		<th>组名</th><th>状态</th><th>添加时间</th><th>备注</th><th>操作</th>
	  </tr>
	  <?php 
	  if(!empty($grouplist)){
	  foreach($grouplist as $row){
	  ?>
	  <tr>
	  	<td><?php echo $row['groupname'];?></td><td><img src="<?php echo $this->img($row['active']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['active']==1 ? '0' : '1';?>" class="activeop" id="<?php echo $row['gid'];?>"/></td><td><?php echo date('Y-m-d H:i:s',$row['addtime']);?></td><td><?php echo $row['remark'];?></td><td>
		<a href="manager.php?type=group&tt=edit&id=<?php echo $row['gid'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['gid'];?>" class="delgroup"/>
		</td>
	  </tr>
	  <?php 
	  } 
	  } ?>
     </table>
	</form>
	<div class="clear">&nbsp;</div>
</div>
<?php  $thisurl = ADMIN_URL.'manager.php'; ?>
<script type="text/javascript">
//jQuery(document).ready(function($){

	$('.delgroup').click(function(){
		gid  = $(this).attr('id');
		if(confirm("确定删除吗？")){
			createwindow();
			thisobj = $(this).parent().parent();
			$.post('<?php echo $thisurl;?>',{action:'delgroup',gid:gid},function(data){ 
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
	
	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		gid = $(this).attr('id'); 
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,gid:gid},function(data){
			if(data == ""){
				if(star == 1){
					id = 0;
					src = '<?php echo $this->img('yes.gif');?>';
				}else{
					id = 1;
					src = '<?php echo $this->img('no.gif');?>';
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	});
	
//});
</script>