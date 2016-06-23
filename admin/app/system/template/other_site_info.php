<div class="contentbox">
 <form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>友情链接
	    </th>
	</tr>
	<tr>
		<td class="label" width="15%">网站名称:</td>
		<td width="85%">
		<input name="name" id="name"  type="text" size="43" value="<?php echo isset($rt['name']) ? $rt['name'] : '';?>">
		<span class="require-field">*</span></td>
	  </tr>
	  <tr>
		<td class="label">网站链接:</td>
		<td><input name="url" id="url"  type="text" size="43" value="<?php echo isset($rt['url']) ? $rt['url'] : '';?>"><span class="require-field">*</span></td>
	  </tr>
	  <tr>
		<td class="label">&nbsp;</td>
		<td>
		<input  class="button"  value="<?php echo $type=='edit' ? '修改' : '添加';?>保存" type="Submit" onclick="return checkvar()">
		</td>
	  </tr>
	 </table>
</form>
</div>
<script type="text/javascript">
function checkvar(){
cname = $('#name').val();
if(cname==""){
alert("网站名称不能为空！");
return false;
}
curl = $('#url').val();
if(curl==""){
alert("链接地址不能为空！！");
return false;
}
return true;
}
</script>