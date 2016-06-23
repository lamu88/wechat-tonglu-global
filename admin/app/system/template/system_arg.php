<div class="contentbox">
	<div class="openwindow"><img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/><br />正在操作，请稍后。。。</div>
     <form id="form1" name="form1" method="post" action="">
	 <table cellspacing="1" cellpadding="5" width="100%">
	  <tr>
		<th colspan="2" align="left">参数设置</th>
	  </tr>
	  <tr>
		<td class="label" width="15%">缓存时间:</td>
		<td>
		<input type="text" name="cache_time" size="10" value="<?php echo isset($rt['cache_time']) ? $rt['cache_time'] : '';?>"/>
		</td>
	  </tr>
	  <tr>
		<td class="label" width="15%">缓存开启:</td>
		<td>
		<label>
		<input type="radio" name="is_cache" id="is_cache" value="0" <?php echo $rt['is_cache']==0 ? 'checked="checked"' : '';?>/>
		关闭</label><label>
		<input type="radio" name="is_cache" id="is_cache" value="1" <?php echo $rt['is_cache']==1 ? 'checked="checked"' : '';?>/>
		开启</label>
		</td>
	  </tr>
	  <!--<tr>
		<td class="label">静态化:</td>
		<td>
		<label>
		<input type="radio" name="is_static" id="is_static" value="0" <?php echo $rt['is_static']==0 ? 'checked="checked"' : '';?>/>
		关闭</label>
		<input type="radio" name="is_static" id="is_static" value="1" <?php echo $rt['is_static']==1 ? 'checked="checked"' : '';?>/>
		开启</label>
		</td>
	  </tr>
	  <tr>
		<td class="label">伪静态:</td>
		<td>
		<label>
		<input type="radio" name="static" value="0" <?php echo ($rt['is_static']!=1 && $rt['is_false_static']!=1 && $rt['is_best_static']!=1) ? 'checked="checked"' : '';?>/>
		关闭</label>
		<label><input type="radio" name="static" value="1" <?php echo $rt['is_static']==1 ? 'checked="checked"' : '';?>/>
		静态化</label>
		<label><input type="radio" name="static" value="2" <?php echo $rt['is_false_static']==1 ? 'checked="checked"' : '';?>/>
		简单伪静态</label>
		<label><input type="radio" name="static" value="3" <?php echo $rt['is_best_static']==1 ? 'checked="checked"' : '';?>/>
		复杂伪静态</label>
		</td>
	  </tr>-->
	  <tr>
		<td class="label">商品图片缩略图:</td>
		<td>
		  宽：<input type="text" name="th_width_s" size="5" value="<?php echo isset($rt['th_width_s']) ? $rt['th_width_s'] : '';?>"/>&nbsp;&nbsp;高：<input type="text" name="th_height_s" size="5" value="<?php echo isset($rt['th_height_s']) ? $rt['th_height_s'] : '';?>"/>&nbsp;<em>默认建议200x300px</em>
		</td>
	  </tr>
	  <tr>
		<td class="label">商品图片大缩略图:</td>
		<td>
		  宽：<input type="text" name="th_width_b" size="5" value="<?php echo isset($rt['th_width_b']) ? $rt['th_width_b'] : '';?>"/>&nbsp;&nbsp;高：<input type="text" name="th_height_b" size="5" value="<?php echo isset($rt['th_height_b']) ? $rt['th_height_b'] : '';?>"/>
		  &nbsp;<em>默认建议420x630px</em>
		</td>
	  </tr>
	  <!--<tr>
	  	<td class="label">
		注册送金额
		</td>
		<td>
		<input type="text" name="give_money" size="5" value="<?php echo isset($rt['give_money']) ? $rt['give_money'] : '';?>"/>元
		</td>
	  </tr>
	  <tr>
	  	<td class="label">
		每月消费比例
		</td>
		<td>
		<input type="text" name="give_money_month" size="5" value="<?php echo isset($rt['give_money_month']) ? $rt['give_money_month'] : '';?>"/>%
		</td>
	  </tr>
	   <tr>
	  	<td class="label">
		每次消费占比例
		</td>
		<td>
		<input type="text" name="give_money_month_one1" size="5" value="<?php echo isset($rt['give_money_month_one1']) ? $rt['give_money_month_one1'] : '';?>"/>%
		&nbsp;&nbsp;<em>个人会员</em>
		</td>
	  </tr>
	  <tr>
	  	<td class="label">
		每次消费占比例
		</td>
		<td>
		<input type="text" name="give_money_month_one11" size="5" value="<?php echo isset($rt['give_money_month_one11']) ? $rt['give_money_month_one11'] : '';?>"/>%
		&nbsp;&nbsp;<em>企业会员</em>
		</td>
	  </tr>
	  <tr>
	  	<td class="label">
		每次消费占比例
		</td>
		<td>
		<input type="text" name="give_money_month_one12" size="5" value="<?php echo isset($rt['give_money_month_one12']) ? $rt['give_money_month_one12'] : '';?>"/>%
		&nbsp;&nbsp;<em>零售会员</em>
		</td>
	  </tr>
	  <tr>
	  	<td class="label">
		每次消费占比例
		</td>
		<td>
		<input type="text" name="give_money_month_one10" size="5" value="<?php echo isset($rt['give_money_month_one10']) ? $rt['give_money_month_one10'] : '';?>"/>%
		&nbsp;&nbsp;<em>供应商会员</em>
		</td>
	  </tr>-->
	  <tr>
		<th>&nbsp;</th>
		<td>
		<input class="ads_save" value="保存" type="submit">
		</td>
	  </tr>
	  </table>
  </form>
</div>