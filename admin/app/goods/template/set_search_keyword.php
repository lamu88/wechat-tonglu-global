<div class="contentbox">
<table cellspacing="2" cellpadding="5" width="100%">
<tr>
<th>
<div style="margin-left:10px;">
	<p style="border-bottom:2px solid #B4C9C6; border-right:2px solid #B4C9C6;background-color:#fff; width:300px; line-height:30px; padding-left:5px">在下面的文本框输入搜索栏出现的搜索关键字</p>
	<label>
	<textarea name="keyword" id="keyword" cols="40" rows="5"><?php echo isset($search_keys)&&!empty($search_keys)?implode(',',$search_keys):"";?></textarea><font class="keyword_mes" style="color:#FF0000"></font>
	</label>
	<br />
	<em>提示：多个关键字用“,”分隔开</em>
	<br />
	<font class="return_mes" style="color:#FF0000"></font>
	<br />
	<input type="button" name="Submit" value="提交保存" onclick="savekeyword()"/>
</div>
</th>
</tr>
</table>
</div>
<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
function savekeyword(){
	key = $('#keyword').val();
	if(key==""){
		$('.keyword_mes').html("关键字不能不为空！");
		return false;
	}
	$.post('<?php echo $thisurl;?>',{action:'savekeyword',keys:key},function(data){ 
			$('.return_mes').html(data);

	});
}
</script>