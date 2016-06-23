<div class="contentbox">
	<form id="form1" name="form1" method="post" action="">
     <table cellspacing="0" cellpadding="5" width="100%" align="left">
	 	<tr>
			<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>管理员</th>
		</tr>
		<tr>
		<td class="label" width="15%">用户名</td>
		<td width="85%">
		  <input name="adminname" class="adminname" maxlength="20" size="34" type="text" value="<?php echo isset($rts['adminname']) ? $rts['adminname'] : '';?>" /><span class="require-field">*</span></td>
	  </tr>
	  <tr>
		<td class="label">Email地址</td>
	
		<td>
		  <input name="email" class="email" size="34" type="text" value="<?php echo isset($rts['email']) ? $rts['email'] : '';?>" /><span class="require-field">*</span></td>
	  </tr>
	  <tr>
	  	<td class="label">帐号权限</td>
		<td> 
		<select name="groupid" class="groupid">
		<option value="">==请选择==</option>
		<?php 
		if(!empty($groupar)){ 
		foreach($groupar as $row){
		?>
		<option value="<?php echo $row['gid'];?>" <?php echo isset($rts['groupid'])&&$row['gid']==$rts['groupid'] ? 'selected="selected"' : '';?>><?php echo $row['groupname'];?></option>
		<?php }} ?>
		</select><span class="require-field">*</span>
		</td>
	  </tr>
	   <tr>
		<td class="label">密  码</td>
		<td>
		  <input name="password" class="password" maxlength="32" size="34" type="password"><span class="require-field">*</span></td>
	
	  </tr>
	  <tr>
		<td class="label">确认密码</td>
		<td>
		  <input name="pwd_confirm" class="pwd_confirm" maxlength="32" size="34" type="password"><span class="require-field">*</span></td>
	  </tr>
	  <tr>
	  	<th style="border-right:1px solid #B4C9C6">&nbsp;</th>
	  	<td>
	  	    <input type="button" name="button" value="<?php echo $type=='edit' ? '编辑' : '添加';?>"  class="addadmin"/>&nbsp;&nbsp;
  	        <input type="reset" name="Submit2" value="重置" />
			<input  type="hidden" class="adminid" value="<?php echo isset($rts['adminid']) ? $rts['adminid'] : "";?>"/>
        </td>
	  </tr>
     </table>
	</form>
	<div class="clear">&nbsp;</div>
</div>
<?php $this->element('showdiv');?>
<?php  $thisurl = ADMIN_URL.'manager.php'; ?>
<script type="text/javascript">
//jQuery(document).ready(function($){

	$('.addadmin').click(function(){
		pass  = $('.password').val();
		con_pass  = $('.pwd_confirm').val();
		emails  = $('.email').val();
		uname  = $('.adminname').val();
		grid = $('.groupid').val(); 
		if(typeof(grid)=='undefined'){
			grid = 50;
		}
		//gridname = $('.groupid option:selected').text()
		if(uname == "" || emails =="" || pass =="" || con_pass ==""){
			alert("请输入完整信息！");
		   return false;
		}
		if(emails.search("^(?:\\w+\\.?)*\\w+@(?:\\w+\\.?)*\\w+$")!=0){
			alert("请输入正确的Email！");
		   return false;
		}
		
		if(grid == ""){
			alert("请选择权限组！");
		   return false;
		}
		
		if(pass != con_pass){
			alert("两次密码不一致！！");
		   return false;
		}
		

		aid  = $('.adminid').val();
		createwindow();
	
		$.post('<?php echo $thisurl;?>',{action:'addmanmger',pass:pass,uname:uname,email:emails,groupid:grid,aid:aid},function(data){ 
			removewindow();
			if(data == ""){
				$('.black_overlay').show(200);
				$('.white_content').show(200);
			}else{
				alert(data);
			}
		});
	});
	
//});
</script>