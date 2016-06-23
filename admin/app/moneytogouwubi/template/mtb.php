<style type="text/css"> .contentbox table th{ font-size:12px; text-align:center}</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="6" align="left" style="text-align:left">佣金转购物币记录</th>
	</tr>
	<tr>
	   <th width="50"><label><input type="checkbox" class="quxuanall" value="checkbox" />编号</label></th>
	   <th>发起人（ID）</th>
	   <th>接收人（ID）</th>
	   <th>金额/购物币</th>
	   <th>时间</th>
	   <th width="50">操作</th>
	</tr>
	<?php if(!empty($rt))foreach($rt as $row){?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['cid'];?>" class="gids"/></td>
	<td><?php echo $row['foname'];?>(<?php echo $row['uid'];?>)</td>
	<td><?php echo $row['toname'];?>(<?php echo $row['accid'];?>)</td>
	<td><?php echo $row['gouwubi'];?></td>
	<td><?php echo date('Y-m-d H:i:s',$row['time']);?></td>
	<td><a href="<?php echo ADMIN_URL.'moneytogouwubi.php?type=del&id='.$row['cid'];?>" onclick="return confirm('确定删除吗');"><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['cid'];?>"/></a></td>
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
			var str=arr.join('+'); ;
			$.post('<?php echo $thisurl;?>',{action:'bathdel',ids:str},function(data){
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