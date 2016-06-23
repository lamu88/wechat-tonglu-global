<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
    <input type="hidden" name="is_show" value="1" />
    <table cellspacing="2" cellpadding="5" width="100%">
	 	 <tr>
			<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>会员等级<span style="float:right"><a href="user.php?type=levellist">返回会员等级</a></span></th>
		</tr>
		<tr>
			<td class="label" width="15%">会员等级名称：</td>
			<td>
			<input name="level_name" value="<?php echo isset($rt['level_name']) ? $rt['level_name'] : '';?>" size="40" type="text" />
			</td>
		</tr>
		<!--<tr>
			<td class="label">初始折扣率：</td>
			<td>
			<input name="discount" value="<?php echo isset($rt['discount']) ? $rt['discount'] : '100';?>" size="40" type="text" />
			<br />请填写为0-100的整数,如填入80，表示初始折扣率为8折
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">会员升级：</td>
			<td>
			<input name="uptype" value="<?php echo isset($rt['uptype']) ? $rt['uptype'] : '';?>" size="40" type="text" />升级类型，under为直推 money为佣金 money+为佣金加绩效。黄金分销商设置无效
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">所推数量：</td>
			<td>
			<input name="uptypenum" value="<?php echo isset($rt['uptypenum']) ? $rt['uptypenum'] : '';?>" size="40" type="text" />升级所需要数量，在此设置
			<br />
			<input name="uptypeplus" value="<?php echo isset($rt['uptypeplus']) ? $rt['uptypeplus'] : '';?>" size="40" type="text" />绩效升级标准，当类型为money+时，再设置此项
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">下属一级白金及以上用户：</td>
			<td>
			<input name="membernum" value="<?php echo isset($rt['membernum']) ? $rt['membernum'] : '';?>" size="40" type="text" />升级所需要人数，在此设置
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">绩效奖励：</td>
			<td>
			<input name="jixiao" value="<?php echo isset($rt['jixiao']) ? $rt['jixiao'] : '';?>" size="10" type="text" />% 该级别的绩效奖励
			</td>
		</tr>
		<!--
		<tr>
			<td class="label" width="15%">初级消费门槛</td>
			<td>
			<input name="money" value="<?php echo isset($rt['money']) ? $rt['money'] : '';?>" size="40" type="text" />升级最低等级的消费门槛
			</td>
		</tr>
		-->
		<!--
		<tr>
			<td class="label">积分来源：</td>
			<td>
			  <label>
			  <input type="checkbox" name="jifendesc[]" value="comment" <?php echo in_array('comment',$rt['jifendesc']) ? 'checked="checked"':"";?>/>每天留言赚积分
		      </label>
		  </td>
		</tr>
		-->
		<tr>
			<td>&nbsp;</td>
			<td><label>
			  <input type="submit" value="<?php echo $type=='edit' ? '确认修改' : '确认添加';?>" class="submit"/>
			</label></td>
		</tr>
	</table>
</form>
</div>