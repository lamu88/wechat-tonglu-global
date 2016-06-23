<?php
$thisurl = ADMIN_URL.'goods.php'; 
if(isset($_GET['asc'])){
$goods = $thisurl.'?type=comment_list&desc=u.user_name';
$email = $thisurl.'?type=comment_list&desc=g.goods_name';
$ac = $thisurl.'?type=comment_list&desc=c.status';
$dt = $thisurl.'?type=comment_list&desc=c.add_time';
$ip_from = $thisurl.'?type=comment_list&desc=c.ip_address';
$rand = $thisurl.'?type=comment_list&desc=c.comment_rank';
}else{
$uname = $thisurl.'?type=comment_list&asc=u.user_name';
$goods = $thisurl.'?type=comment_list&asc=g.goods_name';
$ac = $thisurl.'?type=comment_list&asc=c.status';
$dt = $thisurl.'?type=comment_list&asc=c.add_time';
$ip_from = $thisurl.'?type=comment_list&asc=c.ip_address';
$rand = $thisurl.'?type=comment_list&asc=c.comment_rank';
}
?>

<div class="contentbox">
<style type="text/css">
.contentbox table a{ text-decoration:underline}
.contentbox table a:hover{ text-decoration:none}
</style>
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="8" align="left">用户评论列表</th>
	</tr>
    <tr>
	   <th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />编号</label></th>
	   <th><a href="<?php echo $uname;?>">用户名</a></th>
	   <th><a href="<?php echo $goods;?>">评论对象</a></th>
	   <th><a href="<?php echo $rand;?>">好评度</a></th>
	   <th><a href="<?php echo $ip_from;?>">IP地址[地区]</a></th>
	   <th><a href="<?php echo $dt;?>">评论时间</a></th>
	   <th><a href="<?php echo $ac;?>">状态</a></th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($commentlist)){ 
	foreach($commentlist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['comment_id'];?>" class="gids"/></td>
	<td><?php echo $row['user_name'];?></td>
	<td><a href="../m/product.php?id=<?php echo $row['goods_id'];?>" target="_blank"><?php echo $row['goods_name'];?></a></td>
	<td><?php echo $row['comment_rank']=='3' ? '好评' : ($row['comment_rank']=='2' ? '中评' : '差评');?></td>
	<td><?php echo $row['ip'];?><font color="#FF0000">[<?php echo !empty($row['ip_form'])? $row['ip_form'] : '无知';?>]</font></td>
	<td><?php echo !empty($row['add_time']) ? date('Y-m-d H:i:s',$row['add_time']) : '无知';?></td>
	<td><img src="<?php echo $this->img($row['status']==1 ? 'yes.gif' : 'no.gif');?>" alt="<?php echo $row['status']==1 ? '0' : '1';?>" class="active_comment" lang="active" id="<?php echo $row['comment_id'];?>"/></td>
	<td>
	<a href="goods.php?type=comment_info&id=<?php echo $row['comment_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['comment_id'];?>" class="delcommentid"/>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="8"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdelcomment" id="bathdelcomment"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathdelcomment").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdelcomment").disabled = true;
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
		document.getElementById("bathdelcomment").disabled = !checked;
  });
  
  //批量删除
   $('.bathdelcomment').click(function (){
   		if(confirm("确定删除吗？")){
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); ;
			$.post('<?php echo $thisurl;?>',{action:'bathdel_comment',ids:str},function(data){
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
   
   $('.delcommentid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'bathdel_comment',ids:ids},function(data){
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
   
   	$('.active_comment').live('click',function(){
		star = $(this).attr('alt');
		cid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'active_comment',active:star,cid:cid,type:type},function(data){
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