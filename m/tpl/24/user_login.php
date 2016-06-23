
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
.pw2{background-color: #fff;}
.pw{
border: 1px solid #ddd;
border-radius: 5px;
padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
</style>
<div id="main" style="padding:10px; min-height:300px">
	<form id="LOGIN" name="LOGIN" method="post" action="">
			<table cellpadding="3" cellspacing="5" border="0" width="100%">
			<tr>
				<th width="100%" align="left">登录账号：</th>			
			</tr>
			<tr>
				<td width="100%" align="center"><input placeholder="为手机号码" type="text" name="username" style="width:98%; height:30px; line-height:normal;" class="pw pw2"/></td>
			</tr>
			<tr>
				<th align="left">用户密码：</th>
			</tr>
			<tr>
				<td width="100%" align="center"><input placeholder="微信端设置密码" type="password" name="password" style="width:98%; height:30px; line-height:normal;" class="pw pw2"/></td>
			</tr>
			
			<tr>
				<td align="center" width="100%">
				<input name="" value="登录" type="button" id="submit" tabindex="6" data-disabled="false" class="pw loginbut" onclick="return submit_login_data()">
				</td>
			</tr>
			<tr>
				<td align="center" width="100%">
				<p style="text-align:right; padding-right:10px"><a href="<?php echo ADMIN_URL;?>user.php?act=register">新用户注册?</a>&nbsp;&nbsp;&nbsp;<a href="">忘记密码?</a></p>
				</td>
			</tr>
			</table>   
			 </form>
		
</div>

<?php $this->element('24/footer',array('lang'=>$lang)); ?>
