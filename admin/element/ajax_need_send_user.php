<table cellspacing="2" cellpadding="5" width="100%" class="ajaxsenduser">
<tr>
	<th width="60"><label><input type="checkbox" name="quxuanall" class="quxuanall" value="checkbox" />编号</label></th>
	<th>会员名称</th><th>电子邮箱</th><th>加入时间</th><th>生日</th>
</tr>
<?php if(!empty($rt_user)){
foreach($rt_user as $row){
?>
<tr>
<td><?php echo $row['user_id'];?><input type="checkbox" name="quanxuan" id="<?php echo empty($row['nickname']) ? '未知' : $row['nickname'];?>" value="<?php echo $row['user_id'];?>" class="uids"/></td><td><?php echo empty($row['nickname']) ? '未知' : $row['nickname'];?></td><td><?php echo $row['email'];?></td><td><?php echo date('Y-m-d',$row['reg_time']);?></td><td><?php echo $row['birthday'];?></td>
</tr>
<?php } } ?>
<tr>
<td colspan="5">
 <input type="button" name="button" value="确定添加到发送列表中" disabled="disabled" class="bathdel" id="bathdel" onclick="return_select_uid()" style="cursor:pointer"/>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="4" class="ajaxpage">
<?php echo isset($getuserpage['showmes']) ? $getuserpage['showmes'] : "";?>
<?php echo isset($getuserpage['first']) ? $getuserpage['first'] : "";?>
<?php echo isset($getuserpage['prev']) ? $getuserpage['prev'] : "";?>
<?php echo isset($getuserpage['next']) ? $getuserpage['next'] : "";?>
<?php echo isset($getuserpage['last']) ? $getuserpage['last'] : "";?>
</td>
</tr>
</table>
<script language="javascript" type="text/javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
	  	 var tt = false;
         $("input[name='quanxuan']").each(function(){this.checked=true; tt = true; });
		 if(tt == true){
		 	document.getElementById("bathdel").disabled = false;
		 }
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
	  }
  });
  
  //是删除按钮失效或者有效
  $('.uids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathdel").disabled = !checked;
  });
  
  function return_select_uid(){
  	var arr_id = [];
	var arr_name = [];
	$('input[name="quanxuan"]:checked').each(function(){
		arr_id.push($(this).val());
		arr_name.push($(this).attr('id'));
	});
	setuserid(arr_id,arr_name);
}
</script>
