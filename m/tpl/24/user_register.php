
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
.pw{
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
</style>
<div id="main" style="padding:10px; min-height:300px">
	<form id="REGISTER1" name="REGISTER1" method="post" action="">
			<table cellpadding="3" cellspacing="5" border="0" width="100%">
			<tr>
				<th align="left">手机号码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input type="text" name="mobile_phone" style="width:95%; height:30px; line-height:normal;" class="pw"/></td>
			</tr>
			<tr>
				<th align="left">用户密码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input type="password" name="password" style="width:95%; height:30px; line-height:normal;" class="pw"/></td>
			</tr>
			<tr>
				<th align="left">确认密码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input type="password" name="rp_pass" style="width:95%; height:30px; line-height:normal;" class="pw"/></td>
			</tr>
			<tr>
				<td align="center" width="100%"><input type="hidden" name="user_rank" value="1" />
				<input name="" value="同意协议并注册" type="button" id="submit" tabindex="6" data-disabled="false" style=" padding:5px; background:#FEC627; color:#fff;width:100%; line-height:25px; cursor:pointer; font-weight:bold; border:none" class="pw" onclick="return submit_register_data('REGISTER1');" />
			  </td>
			</tr>
			</table>   
			 </form>
		
</div>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>

