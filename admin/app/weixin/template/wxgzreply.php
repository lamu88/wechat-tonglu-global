<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	  <tr>
		<td class="label" width="30%" align="right">设置回复时关键字:</td>
		<td><input name="keyword" id="keyword"  type="text" size="43" value="<?php echo isset($rt['keyword']) ? $rt['keyword'] : '';?>"></td>
	  </tr>
	  <tr>
	  	<td class="label">&nbsp;</td>
		<td align="left">
		例：填写"美丽",系统会检索包含最近发布的5条信息，若想关注回复回复首页,此项请填写 首页
		</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td align="left">
		  <input type="hidden" name="ttt" value="1" />
		<input class="new_save" value="保存" type="Submit" style="cursor:pointer">
		</td>
	  </tr>
	 </table>
	 </form>
</div>

<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		wxnames = $('#wxname').val();
		if(wxnames=='undefined' || art_title==""){
			alert("公众号名称不能为空");
			return false;
		}
		return true;
	});
//});
-->
</script>
