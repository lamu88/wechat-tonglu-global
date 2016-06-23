<style type="text/css">
.contentbox li{ width:25%; text-align:left; line-height:26px; float:left}
</style>
<div class="contentbox" style="height:450px; overflow:hidden; overflow-y:auto">
   <table cellspacing="1" cellpadding="5" width="100%">
   	<tr>
		<td>
		上传类型：<label style="cursor:pointer"><input onclick="ajax_sava_set(this)" type="radio" name="vgoods_type" value="1"<?php echo isset($vgoods_type)&&$vgoods_type=='1' ? ' checked="checked"' : '';?> />&nbsp;账号+密码&nbsp;&nbsp;</label>
		<label style="cursor:pointer"><input onclick="ajax_sava_set(this)" type="radio" name="vgoods_type" value="2"<?php echo isset($vgoods_type)&&$vgoods_type=='2' ? ' checked="checked"' : '';?> />&nbsp;密码&nbsp;&nbsp;</label>
		</td>
	</tr>
	 <tr>
		<td>
		<div style="line-height:20px;width:500px; background-color:#e2e8eb; border-bottom:3px solid #bec6ce; border-right:3px solid #bec6ce; text-align:left; padding-left:10px; padding-top:10px; padding-bottom:5px">
		<input name="upload_file" id="upload_file" type="hidden" value="" size="43">
		<iframe id="iframe_t" name="iframe_t" border="0" src="<?php echo ADMIN_URL;?>uploadfile.php?action=&ty=upload_file&tyy=vgexcle&files=&gid=<?php echo $_GET['id'];?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</div>
		</td>
	</tr>
	<tr>
		<td>
		<div style="height:30px; line-height:30px;width:100px; background-color:#e2e8eb; border-bottom:3px solid #bec6ce; border-right:3px solid #bec6ce; text-align:center; cursor:pointer"><a href="<?php echo ADMIN_URL;?>vgoods.php?type=download_tpl" style=" display:block">下载模板</a></div>
		</td>
	</tr>
	<tr>
		<td>
			<table cellspacing="2" cellpadding="5" width="100%">
				<tr>
				   <th>卡号</th>
				   <th>卡密</th>
				   <th>状态</th>
				   <th>操作</th>
				</tr>
				<?php if(!empty($rt))foreach($rt as $row){?>
				<tr>
					<td><?php echo empty($row['goods_sn']) ? '无' : $row['goods_sn'];?></td>
					<td><?php echo empty($row['goods_pass']) ? '无' : $row['goods_pass'];?></td>
					<td><?php echo $row['is_use']=='1' ? '<font color=red>已使用</font>['.date('m-d H:i',$row['usetime']).']' : '<font color=blue>未使用</font>';?></td>
					<td><a href="<?php echo ADMIN_URL.'vgoods.php?type=ajax_open_import&id='.$_GET['id'].'&gid='.$row['id'];?>" onclick="return confirm('确定删除吗')">删除</a></td>
				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>
	</table>
	<?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">
function ajax_sava_set(obj){
	va = $(obj).val();
	$.post('<?php echo ADMIN_URL.'vgoods.php';?>',{action:'ajax_sava_set',vv:va},function(data){
			
	});
}
</script>
	