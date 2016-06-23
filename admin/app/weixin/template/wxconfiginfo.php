<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><span style="float:left"><?php echo $type=='newedit' ? '修改' : '添加';?>信息</span><span style="float:right"><a href="weixin.php?type=wxconfig">返回内容列表</a></span></th>
	</tr>
	  <tr>
		<td class="label">公众名称:</td>
		<td><input name="wxname" id="wxname"  type="text" size="43" value="<?php echo isset($rt['wxname']) ? $rt['wxname'] : '';?>"></td>
	  </tr>
	
	  <tr>
		<td class="label">appid:</td>
		<td>
		  <input name="appid" id="appid" type="text" value="<?php echo isset($rt['appid']) ? $rt['appid'] : '';?>" size="43"/>
		</td>
	  </tr>
	   <tr>
		<td class="label">appsecret:</td>
		<td>
		  <input name="appsecret" id="appsecret" type="text" value="<?php echo isset($rt['appsecret']) ? $rt['appsecret'] : '';?>" size="43"/>
		</td>
	  </tr>
	  <tr>
    <td class="label">公众号类型:</td>
    <td>
	  <label><input name="winxintype" value="3" type="radio" <?php echo $rt['winxintype']==3 ? 'checked="checked"' : '';?> /> 服务号</label>  
	  </td>
  	 </tr>
	 <tr>
    <td class="label">是否授权:</td>
    <td>
	  <label><input name="is_oauth" value="1" type="radio" <?php echo ($rt['is_oauth']==1 || !isset($rt['is_oauth'])) ? 'checked="checked"' : '';?> /> 开启授权</label>
	  <label><input name="is_oauth" value="0" type="radio" <?php echo (isset($rt['is_oauth'])&&$rt['is_oauth']==0) ? 'checked="checked"' : '';?> /> 关闭授权</label>  
	  </td>
  	 </tr>
	  <tr>
		<td>&nbsp;</td>
		<td align="left">
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit" style="cursor:pointer">
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
