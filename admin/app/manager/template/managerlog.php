<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
     	<tr>
        	<th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />编号</label></th><th>操作者</th><th>操作日期</th><th>IP地址</th><th>操作记录</th>
        </tr>
		<?php 
		if(!empty($rts)){
		foreach($rts as $row){
		?>
        <tr>
			<td><input type="checkbox" name="quanxuan" value="<?php echo $row['gid'];?>" class="gids"/><?php echo $row['gid'];?></td><td><?php echo $row['optioner'];?></td><td><?php echo date('Y-m-d H:i:s',$row['optiondt']);?></td><td><?php echo $row['optionip'];?></td><td><?php echo $row['optionlog'];?></td>
		</tr>
		<?php }  ?>
		<tr>
		  <td colspan="5"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		   </td>
		</tr>
		<?php }  ?>
     </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<?php  $thisurl = ADMIN_URL.'manager.php'; ?>
<script type="text/javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathdel").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
	  }
  });
  
  //是删除按钮失效或者有效
  $('.gids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathdel").disabled = !checked;
  });
  
  //批量删除
   $('.bathdel').click(function (){
   		if(confirm("确定删除吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); 
			$.post('<?php echo $thisurl;?>',{action:'dellog',logid:str},function(data){
				removewindow();
				if(data == ""){
					$('.openwindow').hide(200);
					location.reload();
				}else{
					alert(data);
				}
			});
		}else{
			return false;
		}
   });
</script>