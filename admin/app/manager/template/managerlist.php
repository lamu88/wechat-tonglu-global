<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
     	<tr>
        	<th>用户名</th><th>级别</th><th>Email地址</th><th>加入时间</th><th>最后登录时间</th><th>操作</th>
        </tr>
        <?php 
		if(!empty($adminlist)){ 
			foreach($adminlist as $row){
		?>
        <tr>
        	<td><?php echo $row['adminname'];?></td><td><?php echo $row['groupname'] ? $row['groupname'] : '超级管理员';?></td><td><?php echo $row['email'];?></td><td><?php echo date('Y-m-d H:i:s',$row['addtime']);?></td><td><?php echo empty($row['lasttime']) ? '从未登陆' : date('Y-m-d H:i:s',$row['lasttime']);?></td><td><a href="manager.php?type=loglist&tt=<?php echo $row['adminname'];?>" title="查看日记"><img src="<?php echo $this->img('icon_view.gif');?>" title="查看日记"/></a>&nbsp;<a href="manager.php?type=edit&id=<?php echo $row['adminid'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['adminid'];?>" class="deladmin"/></td>
        </tr>
        <?php
			} 
		}
		?>
     </table>
</div>
<?php  $thisurl = ADMIN_URL.'manager.php'; ?>
<script type="text/javascript">
//jQuery(document).ready(function($){
	$('.deladmin').click(function(){
			id = $(this).attr('id');
			thisobj = $(this).parent().parent();
			if(confirm("确定删除吗？")){
				createwindow();
				$.post('<?php echo $thisurl;?>',{action:'deladmin',id:id},function(data){
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
//});
</script>