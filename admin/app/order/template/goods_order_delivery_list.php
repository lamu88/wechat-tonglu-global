<?php
$thisurl = ADMIN_URL.'goods_order.php'; 
if(isset($_GET['asc'])){
$oi = $thisurl.'?type=list&tt=delivery&status=222&desc=order_id';
$os = $thisurl.'?type=list&tt=delivery&status=222&desc=order_sn';
$tprice = $thisurl.'?type=list&tt=delivery&status=222&desc=goods_amount';
$own = $thisurl.'?type=list&tt=delivery&status=222&desc=consignee';
$dt = $thisurl.'?type=list&tt=delivery&status=222&desc=add_time';
}else{
$oi = $thisurl.'?type=list&tt=delivery&status=222&asc=order_id';
$os = $thisurl.'?type=list&tt=delivery&status=222&asc=order_sn';
$tprice = $thisurl.'?type=list&tt=delivery&status=222&asc=goods_amount';
$own = $thisurl.'?type=list&tt=delivery&status=222&asc=consignee';
$dt = $thisurl.'?type=list&tt=delivery&status=222&asc=add_time';
}
?>

<div class="contentbox">
	<div class="openwindow"><img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/><br />正在处理，请稍后。。。</div>
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="8" align="left">发货订单列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
		
		</th>
	</tr>
	<tr><td colspan="8" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		订单号<input name="order_sn"  size="15" type="text" value="<?php echo isset($_GET['order_sn']) ? $_GET['order_sn'] : "";?>">
		收货人<input name="consignee"  size="15" type="text" value="<?php echo isset($_GET['consignee']) ? $_GET['consignee'] : "";?>">
		<input value=" 搜索 " class="order_search" type="button">
	</td></tr>
    <tr>
	   <th width="80"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th><a href="<?php echo $os;?>">订单号</a></th>
	   <th><a href="<?php echo $dt;?>">下单时间</a></th>
	   <th><a href="<?php echo $own;?>">收货人</a></th>
	   <th><a href="<?php echo $tprice;?>">总金额</a></th>
	   <th>订单状态</th>
	   <th>物流</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($orderlist)){ 
	foreach($orderlist as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['order_id'];?>" class="gids"/></td>
	<td><?php echo $row['order_sn'];?></td>
	<td><?php echo $row['add_time'];?></td>
	<td><?php echo $row['consignee'];?></td>
	<td><?php echo $row['tprice'];?></td>
	<td><?php echo $row['status'];?></td>
	<td>
	<select name="shopping_id" onchange="ajax_change_shopping(this)">
	<option value="0">选择物流</option>
	<?php if(!empty($shoppinglist))foreach($shoppinglist as $item){?>
	<option value="<?php echo $item['shipping_id'];?>"<?php if($item['shipping_id']==$row['shipping_id_true']){ echo ' selected="selected"';} ?>><?php echo $item['shipping_name'];?></option>
	<?php } ?>
	</select>
	<?php echo !empty($row['sn_id']) ? '<a style="color:#fe0000" href="http://m.kuaidi100.com/index_all.html?type='.$row['shipping_code'].'&postid='.$row['sn_id'].'" target="_blank">'.$row['sn_id'].'</a></font>' : '<span class="vieworder" style="color:#fe0000;display:none" id="'.$row['order_id'].'">点击输入物流单</span>';?></td>
	<td>
	<a href="goods_order.php?type=order_info&id=<?php echo $row['order_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_view.gif');?>" title="编辑"/></a>&nbsp;
	<?php if(in_array($row['order_status'],array('1','4'))){?><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['order_id'];?>" class="delorder"/><?php } ?>
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="7"> 
		 	<input type="checkbox" class="quxuanall" value="checkbox" />
			    <input name="button" id="bathconfirm" value="确认" class="bathop" disabled="true"  type="button">
				<input name="button" id="bathinvalid" value="无效" class="bathop" disabled="true" type="button">
				<input name="button" id="bathcancel" value="取消" class="bathop" disabled="true" type="button">
				<input name="button" id="bathdel" value="移除"  class="bathop" disabled="true" type="button"/>
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
		 document.getElementById("bathinvalid").disabled = false;
		 document.getElementById("bathcancel").disabled = false;
		 document.getElementById("bathconfirm").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
		 document.getElementById("bathinvalid").disabled = true;
		 document.getElementById("bathcancel").disabled = true;
		 document.getElementById("bathconfirm").disabled = true;
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
		document.getElementById("bathconfirm").disabled = !checked;
		document.getElementById("bathcancel").disabled = !checked;
		document.getElementById("bathinvalid").disabled = !checked;
  });
  
  //批量删除
   $('.bathop').click(function (){
   		if(confirm("确定操作吗？")){
			optype = $(this).attr('id');
			if(typeof(optype)=='undefined' || optype==""){ return false;}
			$('.openwindow').show(200);
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); ;
			$.post('<?php echo $thisurl;?>',{action:'bathop',type:optype,ids:str},function(data){
				$('.openwindow').hide(200);
				if(data == ""){
					location.reload();
				}else{
					alert(data);
					//location.reload();
				}
			});
		}else{
			return false;
		}
   });
 
   $('.delorder').click(function(){
   		ids = $(this).attr('id');
		thisobj = $(this).parent().parent();
		if(confirm("确定删除吗？")){
			$('.openwindow').show(200);
			$.post('<?php echo $thisurl;?>',{action:'bathop',type:bathdel,ids:ids},function(data){
				$('.openwindow').hide(200);
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
		gid = $(this).attr('id'); 
		type = $(this).attr('lang');
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'activeop',active:star,gid:gid,type:type},function(data){
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
	$('.order_search').click(function(){
		time1 = $('input[name="EntTime1"]').val();  //look 添加
		
		time2 = $('input[name="EntTime2"]').val();	//look 添加
		
		o_sn = $('input[name="order_sn"]').val();
		
		own = $('input[name="consignee"]').val();
		
		location.href='<?php echo $thisurl;?>?type=list&add_time1='+time1+'&add_time2='+time2+'&tt=delivery&status=222&order_sn='+o_sn+'&consignee='+own;
	});
	
	function ajax_change_shopping(obj){
		vv = $(obj).val();
		if(vv!="0"){
			$(obj).parent().find('.vieworder').show();
		}else{
			$(obj).parent().find('.vieworder').hide();
		}
	}
	//ajax排序处理
	$('.vieworder').click(function (){ edit_2012127(this); });
	function edit_2012127(object){
		thisvar = $(object).html()
		ids = $(object).attr('id');//订单ID
		sid = $(object).parent().find('select[name="shopping_id"]').val();
		if(!(sid>0)){
			alert("请选择配送物流");
			return false;
		}
		 if(typeof($(object).find('input').val()) == 'undefined'){
             var input = document.createElement('input');
			 $(input).attr('value', thisvar);
			 $(input).css('width', '100px');
             $(input).change(function(){
			 	if(confirm("确定操作吗")){
                 	update_2012127(ids, this,sid);
				}
             })
             $(input).blur(function(){
                 $(this).parent().html($(this).val());
             });
             $(object).html(input);
			 $(input).select();
             $(object).find('input').focus();
         }
	}
	
	function update_2012127(id, object,sid){
       var editval = $(object).val();
       var obj = $(object).parent();
	   $.post('<?php echo ADMIN_URL.'shopping.php';?>',{action:'ajax_shopping_op',oid:id,val:editval,sid:sid},function(data){
			 obj.html(editval);
           	 $(object).unbind('click');
           	 $(object).click(function(){
               edit_2012127(object);
             })
		});
    }
</script>