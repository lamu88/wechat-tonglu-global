<?php
$thisurl = ADMIN_URL.'goods_order_v2.php'; 
?>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="10" align="left">订单列表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		选择时间：<input type="text" id="EntTime1" name="EntTime1" onclick="return showCalendar('EntTime1', 'y-mm-dd');"  />
		至
		<input type="text" id="EntTime2" name="EntTime2" onclick="return showCalendar('EntTime2', 'y-mm-dd');"  />
		</th>
		
	</tr>
	<tr><th colspan="10" align="left">
    	<img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		订单号<input name="order_sn"  size="15" type="text" value="<?php echo isset($_GET['order_sn']) ? $_GET['order_sn'] : "";?>">
		收货人<input name="consignee"  size="15" type="text" value="<?php echo isset($_GET['consignee']) ? $_GET['consignee'] : "";?>">
		订单状态 
		<?php 
		$status_option[-1] = '请选择';
		$status_option[11] = '待确认';
		$status_option[200] = '待付款';
		$status_option[210] = '待发货';
		$status_option[222] = '已发货';
		$status_option[214] = '已完成';
		$status_option[1] = '取消';
		$status_option[4] = '无效';
		$status_option[3] = '退货';
		$status_option[2] = '退款';
		?>  
		 <select name="status" >
		 <!--2:确认订单 0:没支付 0:没发货-->
	        <?php 
			$se = 'selected="selected"';
			foreach($status_option as $k=>$var){
				echo '<option value="'.$k.'" '.($k==$_GET['status']&&isset($_GET['status']) ? $se : "").'>'.$var.'</option>';
			}
			?>
		  </select>
		<input value=" 搜索 " class="order_search" type="button">
		<a href="goods_order_v2.php?act=list&status=11">待确认</a>
		<a href="goods_order_v2.php?act=list&status=200">待付款</a>
		<a href="goods_order_v2.php?act=list&status=210">待发货</a>
	</th></tr>
    <tr>
	   <th width="80"><label><input type="checkbox" class="quxuanall" value="checkbox" />选择</label></th>
	   <th>代理商</th>
	   <th>购买者</th>
	   <th>分享者</th>
	   <th>产品</th>
	   <th>单号</th>
	   <th>时间</th>
	   <th>金额</th>
	   <th>状态</th>
	   <th>操作</th>
	</tr>
	<?php 
	if(!empty($rt['lists'])){ foreach($rt['lists'] as $row){
	?>
	<tr>
	<td><input type="checkbox" name="quanxuan" value="<?php echo $row['order_id'];?>" class="gids"/></td>
	<td><?php echo $row['dunickname'];?></td>
	<td><?php echo $row['uunickname'];?></td>
	<td><?php echo $row['punickname'];?></td>
	<td><?php
	if(!empty($row['gimg'])) foreach($row['gimg'] as $imgs){
		echo '<img src="'.SITE_URL.$imgs.'" alt="" height="50" style="margin-right:4px; padding:1px; border:1px solid #ededed" />';
	}
	?></td>
	<td><?php echo $row['order_sn'];?></td>
	<td><?php echo date('Y-m-d H:i:s',$row['add_time']);?></td>
	<td>￥<?php echo $row['order_amount'];?></td>
	<td><?php echo $row['status'];?></td>
	<td>
	<a href="goods_order_v2.php?type=order_info&id=<?php echo $row['order_id'];?>" title="编辑"><img src="<?php echo $this->img('icon_view.gif');?>" title="编辑"/></a>&nbsp;
	<?php if(in_array($row['order_status'],array('1','4'))){?><img src="<?php echo $this->img('icon_drop.gif');?>" title="删除" alt="删除" id="<?php echo $row['order_id'];?>" class="delorder"/><?php } ?>
	<!--<input name="button" value="打印" type="button" onclick="if(confirm('确定打印吗？打印后将会更改打印状态')){ $(this).css('color','#330066');window.open('<?php echo ADMIN_URL;?>goods_order_v2.php?type=orderprint&ids=<?php echo $row['order_id'];?>')}" style="cursor:pointer;<?php echo $row['is_prints']=='1' ? 'color:#330066':'color:#FE0000';?>" id="<?php echo $row['is_prints'];?>"/>-->
	</td>
	</tr>
	<?php
	 } ?>
	<tr>
		 <td colspan="10"> 
		 	<input type="checkbox" class="quxuanall" value="checkbox" />
			    <input name="button" id="bathconfirm" value="确认" class="bathop" disabled="true"  type="button">
				<input name="button" id="bathinvalid" value="无效" class="bathop" disabled="true" type="button">
				<input name="button" id="bathcancel" value="取消" class="bathop" disabled="true" type="button">
				<input name="button" id="bathdel" value="移除"  class="bathop" disabled="true" type="button"/>
			    <input name="button" id="printorder" value="打印"  class="printorder" disabled="true" type="button" onclick="return printorder()" style="cursor:pointer"/>
		 </td>
	</tr>
		<?php } ?>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$rt['pages']));?>
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
		 document.getElementById("printorder").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
		 document.getElementById("bathinvalid").disabled = true;
		 document.getElementById("bathcancel").disabled = true;
		 document.getElementById("bathconfirm").disabled = true;
		 document.getElementById("printorder").disabled = true;
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
		document.getElementById("printorder").disabled = !checked;
  });
  
  //批量删除
   $('.bathop').click(function (){
   		if(confirm("确定操作吗？")){
			optype = $(this).attr('id');
			if(typeof(optype)=='undefined' || optype==""){ return false;}
			createwindow();
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('+'); ;
			$.post('<?php echo $thisurl;?>',{action:'bathop',type:optype,ids:str},function(data){
				removewindow();
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
			createwindow();
			$.post('<?php echo $thisurl;?>',{action:'bathop',type:'bathdel',ids:ids},function(data){
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
		
		sts = $('select[name="status"]').val();
		
		
		location.href='<?php echo $thisurl;?>?type=orderlist&add_time1='+time1+'&add_time2='+time2+'&order_sn='+o_sn+'&consignee='+own+'&status='+sts;
	});
	
	
	//打印订单
	function printorder(){
		if(confirm("你确定打印吗？点击按钮之后将会更改为已打印状态！")){
			var arr = [];
			$('input[name="quanxuan"]:checked').each(function(){
				arr.push($(this).val());
			});
			var str=arr.join('-');
			//window.location.href = '<?php echo ADMIN_URL;?>goods_order_v2.php?type=orderprint&ids='+str;
			window.open('<?php echo ADMIN_URL;?>goods_order_v2.php?type=orderprint&ids='+str);
		}
		return false;
	}
</script>