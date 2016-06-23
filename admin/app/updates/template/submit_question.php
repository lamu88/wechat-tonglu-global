<div style="padding:10px">
<style type="text/css">
.kjnav a, .kjnav a:visited{background: url(<?php echo $this->img('kjico.png');?>) no-repeat; display:block; width:79px; height:29px; line-height:29px; text-align:center}
input,textarea{border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;-khtml-border-radius:4px;border-radius: 4px;}
</style>
<div id="artlist">
	<div class="mod kjnav">
		<a>信息反馈</a>
	</div>   	
</div>
<div class="cr"></div>
<div style="padding:10px;">
<table width="100%" border="0" cellpadding="0" cellspacing="5">
	<tr>
		<td width="100" align="right">联系方式：</td>
		<td align="left"><label>
		  <input type="text" name="titles" style="width:350px; height:22px; line-height:22px;" />
		</label><font color="#aaa">电话、QQ、邮箱等</font></td>
	</tr>
	<tr>
		<td width="100" align="right">问题内容：</td>
		<td align="left">
		<label>
		<textarea name="contents" style="width:350px; height:200px; line-height:20px;"></textarea>
		</label>
		<font color="#aaa">反馈、意见、意见、bug等</font></td>
	</tr>
	<tr>
		<td width="100" align="right">上传附件：</td>
		<td align="left">
		<input name="imgs" id="imgs" type="hidden" value=""/>
		<iframe id="iframe_t" name="iframe_t" border="0" src="<?php echo ADMIN_URL;?>uploadfile.php?action=&ty=imgs&files=" scrolling="no" width="380" frameborder="0" height="25"></iframe>
		<font color="#aaa">文本、图片等</font>
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
		$.post('<?php echo ADMIN_URL.'updates.php';?>',{action:'ajax_submit_question',title:$('input[name="titles"]').val(),imgs:$('input[name="imgs"]').val(),content:$('textarea[name="contents"]').val(),site:'<?php echo SITE_URL;?>'},function(data){
			if(data=="1"){
				alert("提交成功，我们会尽快处理！");
				$('input[name="titles"]').val("");
				$('textarea[name="contents"]').val("");
			}else{
				alert(data);
			}
			$(obj).val('快速提交');
		});
		
	}
	return false;
}
</script>