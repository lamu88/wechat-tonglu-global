
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
.pw,.pwt{
height:26px; line-height:normal;
border: 1px solid #ddd;
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
</style>
<div id="main" style="padding:5px; min-height:300px">
	 <form name="USERINFO" id="USERINFO" action="" method="post">
     <table width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:30px; padding:10px">
	 <tr>
		<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b>姓名：</td>
		<td width="75%" align="left" style="padding-bottom:2px;">
		<input placeholder="" type="text" value="" name="uname"  class="pw"/></td>
  	</tr>
	<tr>
	<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> 手机：</td>
    <td width="75%" align="left" style="padding-bottom:2px;">
    <input placeholder=""  type="text" value="" name="mobile_phone"  class="pw"/></td>
  	</tr>
	<tr>
	<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> 性别：</td>
    <td width="75%" align="left" style="padding-bottom:2px;">
      <label>
      <input type="radio" name="sex" value="1" />男
      </label>&nbsp;&nbsp;&nbsp;
	   <label>
      <input type="radio" name="sex" value="2" />女
      </label>
	  </td>
  	</tr>
	<tr>
	<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> 时间：</td>
    <td width="75%" align="left" style="padding-bottom:2px;">
      <label>
      <select name="yutime">
	  <option value="--:--:--">预约时间</option>
	  <?php for($i=1;$i<30;$i++){
	  $d = date('Y-m-d',mktime()+($i*24*3600));
	  ?>
	  	<option value="<?php echo $d;?>"><?php echo $d;?></option>
	  <?php }?>
      </select>
      </label>	</td>
  	</tr>
	<!--<tr>
	<td width="25%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b> 内容：</td>
    <td width="75%" align="left" style="padding-bottom:2px;">
	<textarea name="textarea" class="pw" style="height:80px"/></textarea>
	</td>
  	</tr>-->
  <tr>
    <td align="center" style="padding-top:10px;" colspan="2">
	<a href="javascript:;" onclick="ajax_submit_yuyue();" style="border-radius:5px;display:block;background:#E13934;cursor:pointer;width:140px; height:25px; line-height:25px; font-size:14px; color:#FFF; margin-top:10px">提交预约</a>
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
<script type="text/javascript">
function ajax_submit_yuyue(){
	unames = $('input[name="uname"]').val();
	mobile = $('input[name="mobile_phone"]').val();
	sexs = $('input[name="sex"]').val();
	yutimea = $('select[name="yutime"]').val();
	
	$.post('<?php echo ADMIN_URL.'user.php';?>',{action:'ajax_submit_yuyue',uname:unames,mobile:mobile,sex:sexs,yutime:yutimea,sid:'<?php echo $_GET['id'];?>'},function(data){
		alert(data);
	});
}

</script>
