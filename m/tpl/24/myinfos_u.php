
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
body{ background:#FFF !important;}
.pw,.pwt{
height:30px; line-height:normal;
border: 1px solid #f0f0f0;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{ width:90%;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.myuserinfo{ height:36px; line-height:36px;-webkit-box-shadow:0px 2px 2px #ddd}
.myuserinfo a{ display:block; width:33.3%; float:left; text-align:center}
.myuserinfo a.acc{ background:#ddd; color:#FFF}
</style>
<div id="main" style="min-height:300px;">
	<div class="myuserinfo">
		<a class="acc" href="<?php echo ADMIN_URL.'user.php?act=myinfos_u';?>"><i></i>我的注册资料</a>
		<a href="<?php echo ADMIN_URL.'user.php?act=myinfos_s';?>"><i></i>我的收货资料</a>
	</div>
	<div class="clear10"></div>
	 <form name="USERINFO" id="USERINFO" action="" method="post">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:30px; padding:10px;">

<tr>
		<td width="25%" align="right" style="padding-bottom:10px;"><b class="cr2">*</b>手机：</td>
		<td width="65%" align="left" style="padding-bottom:10px;">
		<input type="text" value="<?php echo isset($rt['userinfo']['mobile_phone'])&&!empty($rt['userinfo']['mobile_phone']) ? $rt['userinfo']['mobile_phone'] : "";?>" name="mobile_phone"  class="pw" placeholder="手机号码为登陆账号" style="padding-left:25px; background:url(<?php echo $this->img('u.png');?>) 3px center no-repeat"/></td>
	</tr>
	<tr>
	 <tr>
	<td width="25%" align="right" style="padding-bottom:10px;"><b class="cr2">*</b>微信号：</td>
    <td width="65%" align="left" style="padding-bottom:10px;">
    <input type="text" value="<?php echo isset($rt['userinfo']['qq'])&&!empty($rt['userinfo']['qq']) ? $rt['userinfo']['qq'] : "";?>" name="qq"  class="pw" style="padding-left:25px; background:url(<?php echo $this->img('weixin.png');?>) 3px center no-repeat"/></td>
  </tr>

  <tr>
    <td align="center" style="padding-top:20px;" colspan="2">
	<a href="javascript:;" onclick="return update_user_info(1);" style="display:block;background:#e13935;cursor:pointer;width:110px; height:32px; line-height:32px; font-size:14px; color:#FFF; font-weight:bold; text-align:center;border-radius: 5px; overflow:hidden">确定修改</a>
	</td>
  </tr>
   <tr>
    <td align="center" colspan="2">
	<span class="returnmes" style="color:#FF0000"></span>
	</td>
  </tr>
</table>
</form>
</div>

<?php $this->element('24/footer',array('lang'=>$lang)); ?>