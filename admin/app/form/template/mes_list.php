<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="11" align="left">列表</th>
	</tr>
    <tr>
	   <th><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th>姓名[微信昵称]</th>
	   <th>父亲姓名</th>
	   <th>母亲姓名</th>
	   <th>家庭排行</th>
	   <th>辈分</th>
	   <th>身份地址</th>
	   <th>出生日期</th>
	   <th>联系电话</th>
	   <th>联系QQ</th>
	   <th>操作</th>
	</tr>
	<?php 
	//print_r($meslist);
	if(!empty($meslist)){ 
	foreach($meslist as $row){
	?>
	<tr>
	<td width="50"><input type="checkbox" name="quanxuan" value="<?php echo $row['mes_id'];?>" class="gids"/><?php echo $row['mes_id'];?></td>
	<td width="100" align="center"><?php echo $row['user_name'].'['.$row['nickname'].']';?></td>
	<td><?php echo $row['companyurl'];?></td>
	<td><?php echo $row['companyname'];?></td>
	<td><?php echo $row['trade'];?></td>
	<td><?php echo $row['jobs'];?></td>
	<td><?php echo $row['comment_title'];?></td>
	<td><?php echo $row['mobile'];?></td>
	<td><?php echo $row['telephone'];?></td>
	<td><?php echo $row['fax'];?></td>
	<td width="45">
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['mes_id'];?>" class="deladstag"/>
	</td>
	</tr>
	<?php } ?>
	<tr>
		 <td colspan="11"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
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
			$.post('<?php echo $thisurl;?>',{action:'delmes',tids:str},function(data){
				removewindow();
				if(data == ""){
					location.reload();
				}else{
					alert(data);
				}
			});
		}else{
			return false;
		}
   });
   
   $('.deladstag').click(function(){
		id = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'delmes',tids:id},function(data){
				removewindow();
				if(data == ""){
					thisobj.hide(300);
				}else{
					alert(data);	
				}
			});
		}else{
			return false;	
		}
   });
   
   	$('.activeop').live('click',function(){
		star = $(this).attr('alt');
		tid = $(this).attr('id'); 
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,tid:tid},function(data){
			if(data == ""){
				if(star == 1){
					id = 0;
					src = '<?php echo $this->img('yes.gif');?>';
				}else{
					id = 1;
					src = '<?php echo $this->img('no.gif');?>';
				}
				obj.attr('src',src);
				obj.attr('alt',id);
			}else{
				alert(data);
			}
		});
	});
	
</script>