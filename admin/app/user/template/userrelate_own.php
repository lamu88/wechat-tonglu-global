<?php
$thisurl = ADMIN_URL.'user.php'; 
?>
<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 
	<tr><td colspan="5" align="left">
    	<a href="javascript:history.go(-1);" style="font-size:14px; color:#FF0000"><-返回</a>
	</td></tr>
    <tr>
	   <th>昵称</th>
	   <th>佣金</th>
	   <th>代理数</th>
	   <th>是否关注</th>
	   <th>加入时间</th>
	</tr>
	<?php 
	if(!empty($lists)){ 
	foreach($lists as $row){
	?>
	<tr>
	<td>&nbsp;&nbsp;&nbsp;<?php if($row['zcount'] > 0){?><a href="<?php echo ADMIN_URL.'user.php?type=userrelate_own&id='.$row['uid'];?>">+&nbsp;<?php echo !empty($row['nickname']) ? $row['nickname'] : '未关注';echo "<font color=bule>(".(empty($row['zcount']) ? '0' : $row['zcount']).")</font>";?></a><?php }else{?><a href="javascript:;">&nbsp;<?php echo !empty($row['nickname']) ? $row['nickname'] : '未关注';echo "<font color=bule>(".(empty($row['zcount']) ? '0' : $row['zcount']).")</font>";?></a><?php } ?>
	</td>
	<td>￥<?php echo !empty($row['money_ucount']) ? $row['money_ucount'] : '0.00';?></td>
	<td><?php echo !empty($row['subscribe_time']) ? '0' : '0';?></td>
	<td><img src="<?php echo $this->img($row['is_subscribe']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['is_subscribe']==1 ? '0' : '1';?>" class="activeop" lang="active" id="<?php echo $row['id'];?>"/></td>
	<td><?php echo !empty($row['subscribe_time']) ? date('Y-m-d H:i:s',$row['subscribe_time']) : '无知';?></td>
	</tr>
	<?php
	 } ?>
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