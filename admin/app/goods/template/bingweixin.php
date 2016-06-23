<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><span style="float:left">商家关联微信</span></th>
	</tr>
	<tr>
		<td class="label" width="15%">关联微信:</td>
		<td>
		<select name="shopid">
		<?php if(isset($rt['nickname'])&&!empty($rt['nickname'])){?>
		<option value="<?php echo $rt['user_id'];?>"><?php echo $rt['nickname'];?></option>
		<?php }else{?>
		<option value="0">关联微信</option>
		<?php } ?>
		</select>
		关键字搜索<input type="text" class="searchval" style="width:100px; border:1px solid #ccc" />
	 	<input type="button" value=" 搜索 "  style="cursor:pointer" onclick="ajax_u_name(this)"/>
		</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td align="left">
		<p style="color:#FF0000">绑定后将会发送商家唯一ID到微信客户端</p>
		<input class="new_save" value="立即绑定" type="Submit" style="cursor:pointer">
		</td>
	  </tr>
	 </table>
	 </form>
</div>

<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
function ajax_u_name(obj){
	va = $(obj).parent().find('.searchval').val();
	$.post('<?php echo $thisurl;?>',{action:'ajax_u_name_shopid',searchval:va},function(data){
		if(data == ""){
			alert("未找到！");
		}else{
			$(obj).parent().find('select').html(data);
		}
	});
}
</script>

