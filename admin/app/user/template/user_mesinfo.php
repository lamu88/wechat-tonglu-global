<style type="text/css">
table .label{ background-color:#EEF2F5; border-bottom:1px dotted #B4C9C6; border-left:1px solid #B4C9C6}
table .label2{ border-bottom:1px dotted #ccc; border-left:1px solid #ccc}
table th{ background-color:#EEF2F5}
</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%" style="line-height:30px">
	 	 <tr>
			<th colspan="2" align="left" style="position:relative">消息详情信息<a href="user.php?type=messagelist"style="position:absolute; right:0px; top:0px;">返回会员列表</a></th>
		</tr>
		<tr>
			<td class="label" width="15%">发布标题：</td>
			<td class="label2">
			<?php echo $rt['title'];?>
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">发布时间：</td>
			<td class="label2">
			<?php echo date('Y-m-d H:i:s',$rt['addtime']);?>
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">发布管理员：</td>
			<td class="label2">
			<?php echo $rt['adminname'];?>
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">发送对象：</td>
			<td class="label2">
			<a href="user.php?type=info&id=<?php echo $rt['uid'];?>" style="text-decoration:underline"><?php echo '['.$rt['user_name'].'&nbsp;'.$rt['nickname'].']-'.$rt['email'];?></a>
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">发表内容：</td>
			<td class="label2">
			<?php echo $rt['content'];?>
			</td>
		</tr>
		
		<?php if(!empty($rt['rp']))foreach($rt['rp'] as $rows){?>
		<tr>
			<td class="label">&nbsp;</td>
			<td align="left" valign="top" style="line-height:24px; color:#333;background-color:#FAFAFA">
			<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="10%" style="color:#cc0000" valign="top"><b>回复:</b></td>
					<td align="left" valign="top" style="line-height:24px;">
						<?php echo $rows['content'];?>
						<p style="background-color:#ccc; line-height:22px; height:22px; text-align:left">回复时间:<?php echo date('Y-m-d H:i:s',$rows['addtime']);?></p>
						
					  <div style="padding-left:50px; background-color:#ededed; text-align:left; color:#cc0000">
							<textarea class="re_content<?php echo $rows['mes_id'];?>" style="width:400px; height:120px"><?php echo $rows['rp_content'];?></textarea><input type="submit" value=" 回复 " onclick="ajax_rp_mes(<?php echo $rows['mes_id'];?>)" style="cursor:pointer; padding:2px" /><span class="rps<?php echo $rows['mes_id'];?>" style="padding-left:10px; color:#FF0000">回复时间：<?php echo $rows['re_time'] > 0 ? date('Y-m-d H:i:s',$rows['re_time']) : '没有回复';?></span>
						</div>
					
					</td>
				</tr>
			</table>
			</td>
		</tr>
		<?php } ?>
				
		</table>
</div>
<?php  $thisurl = ADMIN_URL.'user.php'; ?>
<script type="text/javascript">
function ajax_rp_mes(id){
	createwindow();
	vv = $('.re_content'+id).val();
	$.post('<?php echo $thisurl;?>',{action:'ajax_rp_mes',val:vv,id:id},function(data){
		removewindow();
		$('.rps'+id).html(data);
		alert("已经保存");

	});
			
}
</script>