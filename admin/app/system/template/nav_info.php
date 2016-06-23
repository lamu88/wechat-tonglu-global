<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 	 <tr>
			<th colspan="2" align="left"><?php echo $type=='nav_edit' ? '修改' : '添加';?>自定义导航</th>
		</tr>
		<tr>
			<td class="label" width="15%">名称:</td> <td><input name="name" value="<?php echo isset($rt['name']) ? $rt['name'] : '';?>" id="name" size="40" type="text"><span class="name_mes"></span></td>
		</tr>
		<tr>
			<td class="label">链接地址:</td> <td><input name="url" value="<?php echo isset($rt['url']) ? $rt['url'] : '';?>" id="url" size="40" type="text"></td>
		</tr>
		  <tr>
			<td class="label">&nbsp;</td>
			<td>
		<span class="notice-span" style="display: block;" id="notice_url">如果是本站的网址，可缩写为与网站根目录相对地址，如index.php;</span>
		</td>
		  </tr>
		<tr>
			<td class="label">是否显示:</td> <td>
			<select name="is_show">
		  <option value="1" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'selected="selected"' : '';?>>是</option><option value="0" <?php echo isset($rt['is_show'])&&$rt['is_show']==0 ? 'selected="selected"' : '';?>>否</option>
		  </select></td>
		</tr>
		<tr>
			<td class="label">是否新窗口:</td> <td>
			<select name="is_opennew">
		  <option value="0" <?php echo !isset($rt['is_opennew']) || $rt['is_opennew']==0 ? 'selected="selected"' : '';?>>否</option><option value="1" <?php echo isset($rt['is_opennew'])&&$rt['is_opennew']==1 ? 'selected="selected"' : '';?>>是</option>
		  </select></td>
		
		</tr>
		<tr>
		<td class="label">位置:</td> <td>
			<select name="type">
		  <option value='top' <?php echo isset($rt['type'])&&$rt['type']=='top' ? 'selected="selected"' : '';?>>顶部</option>
		  <option value="middle" <?php echo !isset($rt['type']) || $rt['type']=='middle' ? 'selected="selected"' : '';?>>中间</option>
		  <option value="bottom" <?php echo isset($rt['type'])&&$rt['type']=='bottom' ? 'selected="selected"' : '';?>>底部 </option>
		  </select></td>
		</tr>
	  <tr>
		<td class="label">&nbsp;</td>
		<td>
		<input value="<?php echo isset($rt['cid']) ? $rt['cid'] : '0';?>" name="cid" type="hidden"/>
		<input value="<?php echo isset($rt['ctype']) ? $rt['ctype'] : '';?>" name="ctype" type="hidden"/>
		<input class="nav_save" value="<?php echo $type=='nav_edit' ? '修改' : '添加';?>保存" type="Submit">
		</td>
	  </tr>
	 </table>
	 </form>
</div>
<script type="text/javascript">
<!--
	$('.nav_save').click(function(){
		cname = $('#name').val();
		if(cname=='undefined' || cname==""){
			$('.name_mes').html('名称不能为空！');
			$('.name_mes').css('color','#FE0000');
			return false;
		}
		return true;
	});
-->
</script>