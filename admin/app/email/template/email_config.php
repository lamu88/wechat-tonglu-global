<div class="main-div" style="border:6px solid #EEF2F5;padding:3px;">
<p style="padding: 0 10px">如果您的服务器支持 Mail 函数（具体信息请咨询您的空间提供商）。我们建议您使用系统的 Mail 函数。<br />当您的服务器不支持 Mail 函数的时候您也可以选用 SMTP 作为邮件服务器。</p>
</div>
<div class="contentbox">
<form method="POST" action="" name="theForm">
  <table width="100%">
              <tr>
				<td class="label" valign="top" width="215">邮件服务:</td>
				<td>
                    <label><input type="radio" name="mail_service" value="0"<?php echo (!isset($GLOBALS['LANG']['mail_service']) || empty($GLOBALS['LANG']['mail_service'])) ? ' checked="true"':"";?>/>采用服务器内置的 Mail 服务</label>
                    <label><input type="radio" name="mail_service" value="1"<?php echo (isset($GLOBALS['LANG']['mail_service'] ) && $GLOBALS['LANG']['mail_service']=='1') ? ' checked="true"':"";?>/>采用其他的 SMTP 服务</label> <br />
          			<em>如果您选择了采用服务器内置的 Mail 服务，您不需要填写下面的内容。</em>
                  </td>
      </tr>
              <tr>
				<td class="label" valign="top">邮件服务器是否要求加密连接(SSL):</td>
				<td>
                 <label><input type="radio" name="smtp_ssl" value="0"<?php echo (!isset($GLOBALS['LANG']['smtp_ssl'] ) || empty($GLOBALS['LANG']['smtp_ssl'])) ? ' checked="true"':"";?>/>否</label>
                    <label><input type="radio" name="smtp_ssl" value="1" onclick="return confirm('此功能要求您的php必须支持OpenSSL模块, 如果您要使用此功能，请联系您的空间商确认支持此模块');"<?php echo (isset($GLOBALS['LANG']['smtp_ssl'] ) && $GLOBALS['LANG']['smtp_ssl']=='1') ? ' checked="true"':"";?>/>是</label>
                </td>
      </tr>
              <tr>
				<td class="label" valign="top">发送邮件服务器地址(SMTP):</td>
				<td>
                   <input name="smtp_host" type="text" value="<?php echo isset($GLOBALS['LANG']['smtp_host']) ? $GLOBALS['LANG']['smtp_host'] : "";?>" size="40" /> <br />
          <em>邮件服务器主机地址。如果本机可以发送邮件则设置为localhost</em>
                  </td>
      </tr>
              <tr>
        <td class="label" valign="top">服务器端口:</td>
        <td>
                    <input name="smtp_port" type="text" value="<?php echo isset($GLOBALS['LANG']['smtp_port']) ? $GLOBALS['LANG']['smtp_port'] : "25";?>" size="40" />
        </td>
      </tr>
              <tr>
        <td class="label" valign="top"> 邮件发送帐号:</td>
        <td>
                    <input name="smtp_user" type="text" value="<?php echo isset($GLOBALS['LANG']['smtp_user']) ? $GLOBALS['LANG']['smtp_user'] : "";?>" size="40" /> <br />
          <em>发送邮件所需的认证帐号，如果没有就为空着</em>
                  </td>
      </tr>
              <tr>
        <td class="label" valign="top">帐号密码:</td>
        <td>
                    <input name="smtp_pass" type="password" value="<?php echo isset($GLOBALS['LANG']['smtp_pass']) ? $GLOBALS['LANG']['smtp_pass'] : "";?>" size="40" />
         </td>
      </tr>
	     <tr>
        <td class="label" valign="top">邮件发送地址:</td>
        <td>
               <input name="smtp_mail" type="text" value="<?php echo isset($GLOBALS['LANG']['smtp_mail']) ? $GLOBALS['LANG']['smtp_mail'] : "";?>" size="40" />
         </td>
      </tr>
       <tr>
        <td class="label" valign="top"> 邮件编码: </td>
        <td>
                统一使用国际化编码（utf8）
         </td>
      </tr>
        <tr>
      <td class="label">邮件地址:</td>
      <td>
        <input type="text" name="test_mail_address" size="30" />
        <input type="button" value="发送测试邮件" onclick="return send_test()" class="button" style="cursor:pointer"/>
      </td>
    </tr>
    <tr>
      <td align="center" colspan="2">
        <input name="submit" type="submit" value=" 确定 " class="button" />
        <input name="reset" type="reset" value=" 重置 " class="button" />
      </td>
    </tr>
  </table>
</form>
</div>
<?php  $thisurl = ADMIN_URL.'email.php'; ?>
<script language="javascript" type="text/javascript">
function send_test(){
	createwindow();
	var smtp_mai = $('input[name="test_mail_address"]').val();
	if(smtp_mai==null || smtp_mai=="" || typeof(smtp_mai)=='undefined') return false;
	$.get('<?php echo $thisurl;?>',{type:'sendmail',useremail:smtp_mai},function(data){
		removewindow();
		alert(data);
	});
}
</script>
