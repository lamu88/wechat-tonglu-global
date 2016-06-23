<?php
$thisurl = ADMIN_URL.'user.php'; 
?>
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="3" align="left">用户推广二维码</th>
	</tr>
	<tr><td colspan="3" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
    	<input value=" 搜索 " class="cate_search" type="button">
	</td></tr>
    <tr>
	   <th>微信昵称</th>
	   <th>二维码</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt)){ 
	foreach($rt as $row){
	?>
	<tr>
	<td width="20%" align="left">
	<label>
	<input type="checkbox" name="quanxuan" value="<?php echo $row['path'];?>" />
	</label>
	<?php echo empty($row['nickname']) ? '未知' : $row['nickname'];?>	</td>
	<td width="40%" align="left">
	<a href="<?php echo $row['img'];?>" target="_blank"><img src="<?php echo $row['img'];?>" width="60" /></a>
	</td>
	<td>
	<a href="user.php?type=usererweima&img=<?php echo $row['path'];?>" title="编辑" onclick="return confirm('确定删除吗')"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" /></a>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="3">
		 <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
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
			$.post('<?php echo ADMIN_URL;?>user.php',{action:'ajax_del_usererweima',ids:str},function(data){
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
   
   $('.deluserid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'bathdel',ids:ids},function(data){
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
		uid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,uid:uid,type:type},function(data){
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
	
		//sous
	$('.cate_search').click(function(){
		
		keys = $('input[name="keyword"]').val();
		
		location.href='<?php echo Import::basic()->thisurl();?>&keyword='+keys;
	});
</script>