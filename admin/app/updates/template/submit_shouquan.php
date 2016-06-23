<div style="padding:10px">
<style type="text/css">
.kjnav a, .kjnav a:visited{background: url(<?php echo $this->img('kjico.png');?>) no-repeat; display:block; width:79px; height:29px; line-height:29px; text-align:center}
input,textarea{border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;-khtml-border-radius:4px;border-radius: 4px;}
</style>
<div id="artlist">
	<div class="mod kjnav">
		<a>授权信息</a>
	</div>   	
</div>
<div class="cr"></div>
<div style="padding:10px;">
<table width="100%" border="0" cellpadding="0" cellspacing="5">
	<tr>
		<td width="100" align="right">称呼：</td>
		<td align="left"><label>
		  <input type="text" name="uname" style="width:350px; height:22px; line-height:22px;" />
		</label></td>
	</tr>
	<tr>
		<td width="100" align="right">旺旺：</td>
		<td align="left"><label>
		  <input type="text" name="wangwang" style="width:350px; height:22px; line-height:22px;" />
		</label></td>
	</tr>
	<tr>
		<td width="100" align="right">QQ：</td>
		<td align="left"><label>
		  <input type="text" name="qq" style="width:350px; height:22px; line-height:22px;" />
		</label></td>
	</tr>
	<tr>
		<td width="100" align="right">域名：</td>
		<td align="left"><label>
		  <input type="text" name="yumming" style="width:350px; height:22px; line-height:22px;" />
		</label></td>
	</tr>
	<tr>
		<td width="100" align="right">内容：</td>
		<td align="left">
		<label>
		<textarea name="contents" style="width:350px; height:200px; line-height:20px;"></textarea>
		</label>
		</td>
	</tr>
	<tr>
		<td width="100" align="right">&nbsp;</td>
		<td align="left">
		  <label>
		  <input type="submit" name="Submit" value="快速提交" onclick="ajax_submit_question(this);" style="cursor:pointer; padding:3px" />
		  </label>		
		  </td>
	</tr>
</table>

</div>
</div>
<script type="text/javascript">
function ajax_submit_question(obj){
	if(confirm("确定在线提交吗？")){
		$(obj).val('正在提交...');
		$.post('<?php echo ADMIN_URL.'updates.php';?>',{action:'ajax_submit_shouquan',unames:$('input[name="uname"]').val(),wangwangs:$('input[name="wangwang"]').val(),qqs:$('input[name="qq"]').val(),yummings:$('input[name="yumming"]').val(),content:$('textarea[name="contents"]').val(),site:'<?php echo SITE_URL;?>'},function(data){
			if(data=="1"){
				alert("提交成功，我们会尽快处理！");
				$('input[name="uname"]').val("");
				$('textarea[name="contents"]').val("");
				$('input[name="wangwang"]').val("");
				$('input[name="qq"]').val("");
				$('input[name="yumming"]').val("");
			}else{
				alert(data);
			}
			$(obj).val('快速提交');
		});
		
	}
	return false;
}
</script>