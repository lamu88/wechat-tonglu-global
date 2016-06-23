<?php
$thisurl = ADMIN_URL.'user.php'; 
?>
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="5" align="left" style="text-align:left">邀请用户列表</th>
	</tr>
	<tr><td colspan="5" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
    	关键字 <input name="keyword" size="15" type="text" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "";?>">
    	<input value=" 搜索 " class="cate_search" type="button">
	</td></tr>
	<tr>
		 <td colspan="4"> 
		 	  <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="选择转移会员-点击下一步" disabled="disabled" class="bathdel" id="bathdel" style="cursor:pointer"/>
		 </td>
	</tr>
    <tr>
	   <th><label><input type="checkbox" class="quxuanall" value="checkbox" />编号</label></th>
	   <th>[上级]昵称</th>
	   <th>是否关注</th>
	   <th>加入时间</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($lists)){ 
	foreach($lists as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['id'];?>" class="gids"/></td>
	<td><a href="javascript:;" style=" text-align:center;position:relative; display:block; background-color:#E2E8EB; border-bottom:2px solid #ccc; border-right:2px solid #ccc; padding-left:5px"><font color="blue">[<?php if(!empty($row['pname'])){ echo $row['pname'];} else { echo '无';}?>]</font><?php echo !empty($row['nickname']) ? $row['nickname'] : '未关注';?></a>
	</td>
	<td><img src="<?php echo $this->img($row['is_subscribe']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_subscribe']==1 ? '0' : '1';?>" class="activeop" lang="active" id="<?php echo $row['id'];?>"/></td>
	<td><?php echo !empty($row['subscribe_time']) ? date('Y-m-d H:i:s',$row['subscribe_time']) : '无知';?></td>
	<td>&nbsp;
	<!--<a href="user.php?type=yaoqing&id=<?php echo $row['id'];?>" title="删除" onclick="return confirm('确定删除吗')"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['id'];?>" class="deluserid"/>
	</td>-->
	</tr>
	<?php
	 } ?>
<!--	<tr>
		 <td colspan="4"> 
		 	  <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="选择转移会员-点击下一步" disabled="disabled" class="bathdel" id="bathdel" style="cursor:pointer"/>
		 </td>
	</tr>-->
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
   		//if(confirm("确定转移吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('-');
			window.location.href='<?php echo $thisurl;?>?type=yaoqingids&ids='+str;
/*			$.post('<?php echo $thisurl;?>',{action:'bathdel',ids:str},function(data){
				removewindow();
				if(data == ""){
					location.reload();
				}else{
					alert(data);
				}
			});*/
		//}else{
			//return false;
		//}
   });
   	
		//sous
	$('.cate_search').click(function(){
		
		keys = $('input[name="keyword"]').val();
		
		window.location.href='<?php echo Import::basic()->thisurl();?>&keyword='+keys;
	});
	
	function showsuppliersinfo(uid){
		JqueryDialog.Open('配送区域','<?php echo ADMIN_URL;?>ajax_show_suppliers_info.php?uid='+uid,750,400,'frame');
	}
</script>