<div class="contentbox">
	<div class="openwindow"><img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/><br />正在操作，请稍后。。。</div>
     <form id="form1" name="form1" method="post" action="">
	 <table cellspacing="2" cellpadding="5" width="100%">
	  <tr>
		<th colspan="2" align="left">站点SEO设置</th>
	  </tr>
	  <tr>
		<td class="label">Meta标题:</td>
		<td><input name="metatitle" id="metatitle"  type="text" value="<?php echo isset($rt['metatitle']) ? $rt['metatitle'] : '';?>" style="width:315px;"></td>
	  </tr>
	  <tr>
		<td class="label">Meta描述:</td>
		<td><textarea name="metadesc" cols="43" rows="6" id="metadesc"><?php echo isset($rt['metadesc']) ? $rt['metadesc'] : '';?></textarea></td>
	  </tr>
	  <tr>
		<td class="label">Meta关键字:</td>
		<td><textarea name="metakeyword" cols="43" rows="6" id="metakeyword"><?php echo isset($rt['metakeyword']) ? $rt['metakeyword'] : '';?></textarea><br /><em>多个关键字用','分隔开</em></td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>
		<input name="btn_save" class="ads_save" value="保存" type="submit">
		</td>
	  </tr>
	  </table>
  </form>
</div>