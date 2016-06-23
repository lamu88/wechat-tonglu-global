

<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
.checkout{ background:#FFF;}
.checkout p.title {
background: #eaeaea;
height: 27px;
line-height: 27px;
text-indent: 10px;
width: 100%;
color: #9a0000;
font-weight: bold;
margin: 10px 0px 0px 0px;
border-bottom:2px solid #CCC
}
.checkout table {
text-align: left;
color: #5f5f5f;
margin:0px;
}
.checkout td {
line-height: 20px;
padding: 3px 0px 5px 0px;
}
.checkout .userreddinfo td {
line-height: 20px;
padding: 2px 0px 2px 0px;
}
.checkout td label{ line-height:22px;}
label{ cursor:pointer}
.pw{ line-height:24px; height:24px;}
</style>
<div id="main" style="padding:5px; padding-top:0px; min-height:300px">
	<div class="checkout">
	<form action="<?php echo ADMIN_URL;?>excart.php?type=confirm" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS" >
		<?php if(!empty($rt['userress'])){?>
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%;border-radius:5px; border:1px solid #ededed; margin-top:10px;">
		<?php $userress_id = 0; foreach($rt['userress'] as $row){?>
		  <tr>
		  <td>
		  <label style="padding-left:10px;"><input type="radio" name="userress_id" value="<?php echo $row['address_id'];?>" <?php if($row['is_default']=='1'){?>checked="checked"<?php $userress_id = $row['address_id']; } ?>/>
		  <?php
		  echo $row['provincename'].$row['cityname'].$row['districtname'].$row['address'].'<br/><span style="padding-left:26px;"></span>'.'电话:'. (!empty($row['mobile']) ? $row['mobile'] : $row['tel']) .'&nbsp;联系人:'. $row['consignee'];
		  ?></label>
		  <p style="padding-left:26px;"><img src="<?php echo $this->img('btu_up.gif');?>"  height="18" width="50" border="0" align="absmiddle" onclick="ressinfoop('<?php echo $row['address_id'];?>','showupdate',this)" style="cursor:pointer"/></p>
		  </td>
		  </tr>
		  <?php } ?>
		  <?php 
				$userress_id = $userress_id > 0 ? $userress_id : (isset($rt['userress'][0]) ? $rt['userress'][0]['userress_id'] : 0);
		  ?>
		  <tr>
		  <td><label style="padding-left:10px;"><input name="userress_id" type="radio" value="0" />&nbsp;添加新收货地址</label></td>
		  </tr>
	  </table>
		 <?php } ?>
		  
		  	<table border="0" cellpadding="0" cellspacing="0" class="userreddinfo" style="width:100%;border-radius:5px; border:1px solid #ededed; margin-top:10px;<?php if(!empty($rt['userress'])) echo 'display:none;';?>" width="100%">
			  <tr>
				<td align="right">&nbsp;&nbsp;姓名：</td>
				<td align="left"><input type="text" value="" name="consignee"  class="pw" style="width:240px;"/> 
				</td>
			  </tr>
				
			  
			   <tr>
				<td align="right">&nbsp;&nbsp;区域：</td>
				<td align="left">
			<?php $this->element('address',array('resslist'=>$rt['province']));?>
				</td>
				
			  </tr>
			  <tr class="address_sh">
				<td align="right">&nbsp;&nbsp;地址：</td>
				<td align="left"><input type="text" value="" name="address"  class="pw" style="width:240px;"/></td>
			  </tr>
			  <tr>
				<td align="right">&nbsp;&nbsp;邮箱：</td>
				<td align="left"><input type="text" value="" name="email"  class="pw" style="width:240px;"/></td>
			  </tr>
			  <tr>
				<td align="right">&nbsp;&nbsp;电话：</td>
				<td align="left"><input type="text" value="" name="mobile"  class="pw" style="width:240px;"/></td>
			  </tr>
			  <tr>
			  <td>&nbsp;</td>
			  <td align="left" colspan="2"><img src="<?php echo $this->img('btu_add.gif');?>" alt="" style="cursor:pointer" onclick="ressinfoop('0','add','CONSIGNEE_ADDRESS')"/></td>
			  </tr>
			</table>
			
			<table border="0" cellpadding="0" cellspacing="0" style="width:100%;border-radius:5px; border:1px solid #ededed; margin-top:10px;">
		    <?php
				  if(!empty($rt['goodslist'])){
				  $total= 0;
				  $uid = $this->Session->read('User.uid');
				  foreach($rt['goodslist'] as $k=>$row){
					  if(!($row['goods_id'])>0) continue;
					  $onetotal = $row['need_jifen'];
					  $total +=$onetotal*$row['number'];
		   ?>
			<tr>
				<td style="width:80px; text-align:center; height:80px; padding:10px; padding-left:0px; padding-right:0px; overflow:hidden">
					<img src="<?php echo SITE_URL.$row['goods_thumb'];?>" title="<?php echo $row['goods_name'];?>" border="0" style="width:78px; height:78px; border:1px solid #ededed; padding:1px; margin-left:5px;">
				</td>
				<td style="text-align:left;" valign="top">
				<p style="padding-left:10px; position:relative">
					<?php echo $row['goods_name'];?>
					<span style="padding:2px 5px 2px 5px; color:#FF0000; cursor:pointer; position:absolute; right:1px; top:-3px; z-index:22; background:#fafafa; border:1px solid #ededed;border-radius:5px;" class="delcartid" id="<?php echo $k;?>">删除</span>
					</p>
				<?php if(!empty($row['spec'])){
				 echo '<p style="padding-left:10px;">'.implode("、",$row['spec']).'</p>';
				 } ?>
				 <div class="item" style="height:24px; line-height:24px; padding-left:10px;">
					<?php 
							echo '需&nbsp;<font color=red>'.$row['need_jifen']*$row['number'].'</font>&nbsp;积分<br />数量&nbsp;'.$row['number'];
					?>
				  </div>
				</td>
			</tr>
			 <?php } } ?>
		</table>

		
		<!--<p>配送方式</p>-->
		<table cellpadding="0" cellspacing="0" style="width:100%;border-radius:5px; border:1px solid #ededed; margin-top:10px;">
			<tr style="display:none">
				  <td align="right" width="22%"><span>支付方式：</span></td>
				  <td align="left" width="78%">
				  <?php 
				if(!empty($rt['paymentlist'])){
					echo '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">';
					foreach($rt['paymentlist'] as $k=>$row){
					?>
					<tr>
					  <td><label><span><input name="pay_id"  id="pay_id"<?php if($k=='0'){ echo ' checked="checked"';}?> value="<?php echo $row['pay_id'];?>" type="radio"></span><strong><?php echo $row['pay_name'];?></strong></label></td>
					</tr>
					<?php
					}
					echo '</table>';
				}
				?>
				  </td>
			</tr>
			<tr>
				  <td align="right" width="22%"><span>配送方式：</span></td>
				  <td align="left" width="78%">
					<?php 
					$free = array();
					if(!empty($rt['shippinglist'])){
					echo '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;display:none">';
					foreach($rt['shippinglist'] as $k=>$row){
					?>
					<tr>
					  <td><label><span style="padding-left:10px;"><input<?php echo $k=='0' ? ' checked="checked"':'';?> name="shipping_id" id="shipping_id" value="<?php echo $row['shipping_id'];?>" type="radio" /></span><strong><?php echo $row['shipping_name'];?></strong></label></td>
					</tr>
					<?php
					}
					echo '</table>';
				}
				?>快递免邮
				  </td>
			</tr>
			<tr>
				<td align="right" width="22%">订单附言：</td>
				<td>
				<textarea class="pw" name="postscript" id="postscript" style="width:96%; height:60px;"></textarea>
				</td>
			</tr>
		</table>
		<div style="padding-left:5px;">
			<p style="text-align:right; padding:25px; padding-right:5px; font-weight:bold">当前积分：<font color="red"><?php echo $rt['mypoints']>0 ? $rt['mypoints'] : 0;?></font>&nbsp;&nbsp;&nbsp;需要支付积分：<font color="red"><?php echo $total;?></font>&nbsp;&nbsp;&nbsp;
			</p>
			<p style="height:30px; line-height:30px; margin-top:10px;">
			<input value="提交" type="submit" align="absmiddle" onclick="" style="margin-bottom:33px;width:100px; height:30px; line-height:30px; background:url(<?php echo ADMIN_URL;?>images/buybut.jpg) 0px 0px no-repeat; font-size:20px; color:#FFFFFF; font-weight:bold; text-align:center; cursor:pointer"/>
			</p>
	    </div>
	</form>
	</div>
</div>

<div style="height:20px;"></div>
<?php  $thisurl = ADMIN_URL.'excart.php'; ?> 
<script language="javascript" type="text/javascript">
$('.delcartid').click(function(){
	if(confirm("确定移除吗")){
		gid = $(this).attr('id');	
		$(this).parent().parent().parent().remove();
		
		$.post('<?php echo $thisurl;?>',{action:'ajax_remove_excargoods',gid:gid},function(data){
			if(data=='1'){
				window.location.reload(SITE_URL+'excart.php?type=checkout');
			}
		});
	}
	return false;
});

$('input[name="userress_id"]').live('click',function(){
	var vv= $(this).val();
	if(vv==0){
	$('.userreddinfo').show();
	}else{
	$('.userreddinfo').hide();
	}
	//$('.userreddinfo').toggle();
});

function checkvar(){
	pp = $('input[name="pay_id"]:checked').val(); 
	if(typeof(pp)=='undefined' || pp ==""){
		alert("请选择支付方式！");
		return false;
	}
	
	ss = $('input[name="shipping_id"]:checked').val(); 
	if(typeof(ss)=='undefined' || ss ==""){
		alert("请选择配送方式！");
		return false;
	}
	
	userress_id = $('input[name="userress_id"]:checked').val();
	if(userress_id == '0' || userress_id == '' || typeof(userress_id)=='undefined'){
			consignee = $('input[name="consignee"]').val(); 
			if(typeof(consignee)=='undefined' || consignee ==""){
				alert("收货人不能为空！");
				return false;
			}
			
			provinces = $('select[name="province"]').val();
			if ( provinces == '0' )
			{
				alert("请选择收货地址！");
				return false;
			}
			
			city = $('select[name="city"]').val();
			if ( city == '0' )
			{
				alert("请完整选择收货地址！");
				return false;
			}
			
			district = $('select[name="district"]').val();
			if ( district == '0' )
			{
				alert("请完整选择收货地址！");
				return false;
			}
			
			shipping_id = $(':radio[name="shipping_id"]:checked').val();
			if ( shipping_id == '6')
			{
				shop_id = $('select[name="shop_id"]').val();
				if ( shop_id == '0' || shop_id == '' )
				{
					alert("此处暂无配送店,请选择送货上门。");
					return false;
				}
			}
			
		
			address = $('input[name="address"]').val(); 
			if(typeof(address)=='undefined' || address ==""){
				alert("详细地址不能为空！");
				return false;
			}
			
			zipcode = $('input[name="zipcode"]').val(); 
			if(typeof(zipcode)=='undefined' || zipcode ==""){
				alert("邮政编码有误！");
				return false;
			}
			
			mobile = $('input[name="mobile"]').val(); 
			tel = $('input[name="tel"]').val(); 
			if(mobile =="" && tel ==""){
				alert("请输入手机或者电话号码！");
				return false;
			}
	}	
	
	var arr = [];
	$('input[name="userress_id"]:checked').each(function(){
		arr.push($(this).val());
	});
	$('input[name="userress_ids"]').val(arr.join('+'));
	if(arr.length<1){
		alert("请选择收货地址");
		return false;
	}
	return true;
}

</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
