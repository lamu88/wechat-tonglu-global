<?php
$thisurl = ADMIN_URL.'con_new.php'; 
if(isset($_GET['asc'])){
$cate = $thisurl.'?type=newlist&desc=tb2.cat_name';
$article_title = $thisurl.'?type=newlist&desc=tb1.article_title';
$viewcount = $thisurl.'?type=newlist&desc=tb1.viewcount';
$is_show = $thisurl.'?type=newlist&desc=tb1.is_show';
$addtime = $thisurl.'?type=newlist&desc=tb1.addtime';
$is_top = $thisurl.'?type=newlist&desc=tb1.is_top';
}else{
$cate = $thisurl.'?type=newlist&asc=tb2.cat_name';
$article_title = $thisurl.'?type=newlist&asc=tb1.article_title';
$viewcount = $thisurl.'?type=newlist&asc=tb1.viewcount';
$is_show = $thisurl.'?type=newlist&asc=tb1.is_show';
$addtime = $thisurl.'?type=newlist&asc=tb1.addtime';
$is_top = $thisurl.'?type=newlist&asc=tb1.is_top';
}
?>

<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="6" align="left">商家列表</th>
	</tr>
    <tr>
	   <th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th><a href="<?php echo $article_title;?>">商家</a></th>
	   <th><a href="<?php echo $viewcount;?>">地址</a></th>
	   <th><a href="<?php echo $is_show;?>">电话</a></th>
	   <th><a href="<?php echo $addtime;?>">录入时间</a></th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($newlist)){ 
	foreach($newlist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['article_id'];?>" class="gids"/></td>
	<td><?php echo $row['article_title'];?></td>
	<td>广州</td>
	<td></td>
    <td><?php echo !empty($row['addtime']) ? date('Y-m-d',$row['addtime']) : "无知";?></td>
	<td>
	<a href="con_new.php?type=newedit&id=<?php echo $row['article_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_edit.gif');?>" title="编辑"/></a>&nbsp;
	<img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['article_id'];?>" class="delarticleid"/>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="6"> <input type="checkbox" class="quxuanall" value="checkbox" />
			  <input type="button" name="button" value="批量删除" disabled="disabled" class="bathdel" id="bathdel"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<?php  $thisurl = ADMIN_URL.'con_new.php'; ?>
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
			$.post('<?php echo $thisurl;?>',{action:'delarticle',ids:str},function(data){
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
   
   $('.delarticleid').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'delarticle',ids:ids},function(data){
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
		cid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'alt_activeop',active:star,cid:cid,type:type},function(data){
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