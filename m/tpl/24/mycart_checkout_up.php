<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css" media="all" />
<style type="text/css">
	#main{min-height:300px; margin-top:8px}
	.checkout{background:#FFF; padding-bottom:10px; font-size:0.9rem}
	.checkout .tr{height:35px; line-height:35px; padding-top:8px; overflow:hidden}
	.checkout .tr .td1{width:30%; text-align:center;}
	.checkout .tr .td2{width:60%; text-align:right;}
	.checkout .tr .td2 input{border:#CCC solid 1px; height:30px; width:95%}
	.checkout .selectlabel{ float:left; margin-left:3px; margin-right:3px; width:21%; background:#392933; color:#FFF; padding-right:5px; text-align:center}
	.checkout .selectlabelcp{ float:left; width:20%; text-align:center}
	.checkout .selectlabel .xinghao{opacity: 0; position: absolute; z-index: -1;}
	.checkout .lbchecked{background:#E03106}
	.checkout .trtable{width:100%; border-top:1px solid #ededed; margin-top:8px;}
	.up_bottom{clear:both; position:fixed; bottom:0px; line-height:45px; height:45px; background:#392933; width:100%; overflow:hidden }
	.up_bottom div{width:50%; text-align:center; color:#FFF; float:left}
	.up_bottom .ub1{width:20%; background:#F1F1F1}
	.up_bottom .ub2{width:40%}
	.up_bottom .ub3{width:40%}
	.submitbtn{background:#E03106; width:100%; height:48px; color:#FFF; font-size: 0.9rem; font-family:'微软雅黑'}
	.userreddinfo tr{height:35px; line-height:35px}
	.userreddinfo tr input{height:25px; line-height:25px}
</style>
<script>
	addToCart('<?php echo $ngoods['goods_id'];?>','jumpshopping_up');
</script>
<?php
	$ngoods_id = $ngoods_id?$ngoods_id:$ngoods['goods_id'];
?>
<div>
	<img src="/<?php echo $ngoods['goods_img'];?>" width="100%" />
</div>

<div id="main">
<form action="/m/mycart.php?type=confirm" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS">
<div class="checkout">
		<?php
			if($rank==1){
		?>
			<div class="tr">
				<div class="selectlabelcp">选择等级</div>
				<label for="xinghao3"><div class="selectlabel<?php if($ngoods_id==$goods[2]['goods_id']) echo ' lbchecked' ?>" id="sl1" onclick="slbg('sl1',<?php echo $goods[2]['goods_id']; ?>)"><input type="radio" name="xinghao" class="xinghao" id="xinghao3" value="<?php echo $goods[2]['pifa_price'];?>" onclick="typemoney()" <?php if($ngoods_id==$goods[2]['goods_id']) echo "checked='checked'" ?> /><?php echo $goods[2]['goods_name'];?></div></label>
				<label for="xinghao2"><div class="selectlabel<?php if($ngoods_id==$goods[1]['goods_id']) echo ' lbchecked' ?>" id="sl2" onclick="slbg('sl2',<?php echo $goods[1]['goods_id']; ?>)"><input type="radio" name="xinghao" class="xinghao" id="xinghao2" value="<?php echo $goods[1]['pifa_price'];?>" onclick="typemoney()" <?php if($ngoods_id==$goods[1]['goods_id']) echo "checked='checked'" ?> /><?php echo $goods[1]['goods_name'];?></div></label>
				<label for="xinghao1"><div class="selectlabel<?php if($ngoods_id==$goods[0]['goods_id']) echo ' lbchecked' ?>" id="sl3" onclick="slbg('sl3',<?php echo $goods[0]['goods_id']; ?>)"><input type="radio" name="xinghao" class="xinghao" id="xinghao1" value="<?php echo $goods[0]['pifa_price'];?>" onclick="typemoney()" <?php if($ngoods_id==$goods[0]['goods_id']) echo "checked='checked'" ?>  /><?php echo $goods[0]['goods_name'];?></div></label>
			</div>
		<?php
			}elseif($rank==8){
		?>
			<div class="tr">
				<div class="selectlabelcp">选择等级</div>
				<label for="xinghao3"><div class="selectlabel<?php if($ngoods_id==$goods[2]['goods_id']) echo ' lbchecked' ?>" id="sl1" onclick="slbg('sl1',<?php echo $goods[2]['goods_id']; ?>)"><input type="radio" name="xinghao" class="xinghao" id="xinghao3" value="<?php echo $goods[2]['pifa_price'];?>" onclick="typemoney()" <?php if($ngoods_id==$goods[2]['goods_id']) echo "checked='checked'" ?> /><?php echo $goods[2]['goods_name'];?></div></label>
				<label for="xinghao2"><div class="selectlabel<?php if($ngoods_id==$goods[1]['goods_id']) echo ' lbchecked' ?>" id="sl2" onclick="slbg('sl2',<?php echo $goods[1]['goods_id']; ?>)"><input type="radio" name="xinghao" class="xinghao" id="xinghao2" value="<?php echo $goods[1]['pifa_price'];?>" onclick="typemoney()" <?php if($ngoods_id==$goods[1]['goods_id']) echo "checked='checked'" ?> /><?php echo $goods[1]['goods_name'];?></div></label>
				<label for="xinghao1" style="text-decoration:line-through"><div class="selectlabel"<?php if($ngoods_id==$goods[0]['goods_id']) echo ' lbchecked' ?> id="sl3" onclick="slbg('sl3',<?php echo $goods[0]['goods_id']; ?>)"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao1" value="<?php echo $goods[0]['pifa_price'];?>" onclick="typemoney()" /><span style="text-decoration:line-through"><?php echo $goods[0]['goods_name'];?></span></div></label>
			</div>
		<?php
			}elseif($rank==9){
		?>
			<div class="tr">
				<div class="selectlabelcp">选择等级</div>
				<label for="xinghao3"><div class="selectlabel<?php if($ngoods_id==$goods[2]['goods_id']) echo ' lbchecked' ?>" id="sl1" onclick="slbg('sl1',<?php echo $goods[2]['goods_id']; ?>)"><input type="radio" name="xinghao" class="xinghao" id="xinghao3" value="<?php echo $goods[2]['pifa_price'];?>" onclick="typemoney()" <?php if($ngoods_id==$goods[2]['goods_id']) echo "checked='checked'" ?> /><?php echo $goods[2]['goods_name'];?></div></label>
				<label for="xinghao2" style="text-decoration:line-through"><div class="selectlabel<?php if($ngoods_id==$goods[1]['goods_id']) echo ' lbchecked' ?>" id="sl2" onclick="slbg('sl2',<?php echo $goods[1]['goods_id']; ?>)"><input type="radio" name="xinghao" disabled class="xinghao" id="xinghao2" value="<?php echo $goods[1]['pifa_price'];?>" onclick="typemoney()" /><span style="text-decoration:line-through"><?php echo $goods[1]['goods_name'];?></span></div></label>
				<label for="xinghao1" style="text-decoration:line-through"><div class="selectlabel<?php if($ngoods_id==$goods[0]['goods_id']) echo ' lbchecked' ?>" id="sl3" onclick="slbg('sl3',<?php echo $goods[0]['goods_id']; ?>)"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao1" value="<?php echo $goods[0]['pifa_price'];?>" onclick="typemoney()" /><span style="text-decoration:line-through"><?php echo $goods[0]['goods_name'];?></span></div></label>
			</div>
		<?php
			}elseif($rank==10){
		?>
			<div class="tr">
				<div class="selectlabelcp">选择等级</div>
				<label for="xinghao3" style="text-decoration:line-through"><div class="selectlabel" id="sl1" onclick="slbg('sl1')"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao3" value="<?php echo $goods[2]['pifa_price'];?>" onclick="typemoney()" /><span style="text-decoration:line-through"><?php echo $goods[2]['goods_name'];?></span></div></label>
				<label for="xinghao2" style="text-decoration:line-through"><div class="selectlabel" id="sl2" onclick="slbg('sl2')"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao2" value="<?php echo $goods[1]['pifa_price'];?>" onclick="typemoney()" /><span style="text-decoration:line-through"><?php echo $goods[1]['goods_name'];?></span></div></label>
				<label for="xinghao1" style="text-decoration:line-through"><div class="selectlabel" id="sl3" onclick="slbg('sl3')"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao1" value="<?php echo $goods[0]['pifa_price'];?>" onclick="typemoney()" checked="checked" /><span style="text-decoration:line-through"><?php echo $goods[0]['goods_name'];?></span></div></label>
			</div>
		<?php
			}
		?>
		
	  <table border="0" cellpadding="0" cellspacing="0" class="trtable">
		<tr>
		<td style="border:#D2D2D2 solid 1px; padding:5px">
			&nbsp;<b style="color:#E03106; text-shadow: #E03106 0 1px 0;">∨</b>&nbsp;收货信息
		</td>
		</tr>
		<?php if(!empty($rt['userress'])){?>
		<?php $userress_id = 0; foreach($rt['userress'] as $row){?>
		  <tr>
		  <td>
		  <label style="padding-left:10px;"><input<?php echo $row['is_default']=='1' ? ' checked="checked"' : '';?> type="radio" class="showaddress" name="userress_id" value="<?php echo $row['address_id'];?>"/>
		  <?php
		  echo $row['provincename'].$row['cityname'].$row['districtname'].$row['address'].'<br/><span style="padding-left:26px;"></span>'.'电话:'. (!empty($row['mobile']) ? $row['mobile'] : $row['tel']) .'&nbsp;联系人:'. $row['consignee'];
		  ?></label>
		  <p style="padding-left:26px;">
		  <a href="javascript:;" onclick="ressinfoop('<?php echo $row['address_id'];?>','showupdate',this)" style="border-radius:5px;display:block;background:#3083CE;cursor:pointer;width:60px; height:22px; line-height:22px; font-size:12px; color:#FFF; text-align:center">修改</a>
		  </p>
		  </td>
		  </tr>
		  <?php } }?>
		  <?php 
			$userress_id = $userress_id > 0 ? $userress_id : (isset($rt['userress'][0]) ? $rt['userress'][0]['address_id'] : 0);
		  ?>
		  <tr>
		  <td><label style="padding-left:10px;"><input class="showaddress" name="userress_id" type="radio" value="0" />&nbsp;添加新收货地址</label></td>
		  </tr>
		  <tr>
		  	<td align="left">
				<table width="100%" border="0" cellpadding="0" cellspacing="0"<?php if(!empty($rt['userress'])) echo ' style="display:none"';?> class="userreddinfo">
				  <tr>
					<td align="right">姓名：</td>
					<td align="left"><input type="text" value="" name="consignee"  class="pw" style="width:95%;"/> 
					</td>
				  </tr>
				   <tr>
					<td align="right">区域：</td>
					<td align="left">
				<?php $this->element('address',array('resslist'=>$rt['province']));?>
					</td>
					
				  </tr>
				  <tr class="address_sh">
					<td align="right">地址：</td>
					<td align="left"><input type="text" value="" name="address"  class="pw" style="width:95%;;"/></td>
				  </tr>
				  <tr>
					<td align="right">电话：</td>
					<td align="left"><input type="text" value="" name="mobile"  class="pw" style="width:95%;"/></td>
				  </tr>
				  <tr>
				  <td>&nbsp;</td>
				  <td align="left" colspan="2"><img src="<?php echo $this->img('btu_add.gif');?>" alt="" style="cursor:pointer" onclick="ressinfoop('0','add','CONSIGNEE_ADDRESS')"/></td>
				  </tr>
			</table>
			</td>
		  </tr>
	    </table>
		<div class="tr">
			<div class="td1 left">微信号</div>
			<div class="td2 left"><input type="text" value="" name="postscript" /></div>
		</div><br />
		<div align="center">
			<input type="checkbox" name="checkbox1" value="checkbox" id="checkbox1" checked> <a href="/m/new.php?id=6">我已阅读并已授受此协议</a>
		</div>
		<div style="display:none">
		<?php
			if(!empty($rt['paymentlist'])){
				foreach($rt['paymentlist'] as $k=>$row){
				if($row['pay_id']=='7'){
				?>
					<input name="pay_id"  id="pay_id"<?php if($k=='0'){ echo ' checked="checked"';}?> value="<?php echo $row['pay_id'];?>" type="radio">
				<?php
				}else{
				?>
					<input name="pay_id"  id="pay_id"<?php if($k=='0'){ echo ' checked="checked"';}?> value="<?php echo $row['pay_id'];?>" type="radio">
				<?php
				}
				}
			}
			$free = array();
			if(!empty($rt['shippinglist'])){
				foreach($rt['shippinglist'] as $k=>$row){
			?>
				<input <?php echo $k=='0' ? ' checked="checked"':'';?> name="shipping_id" id="shipping_id" value="<?php echo $row['shipping_id'];?>" type="radio" />
			<?php 
				$f = $this->action('shopping','ajax_jisuan_shopping',array('shopping_id'=>$row['shipping_id'],'userress_id'=>($userress_id > 0 ? $userress_id : '5')),'cart');
				$f = $f>0 ? $f : '0.00';
				$free[] = $f;
				}
			}
		?>
		</div>
		<input type="hidden" name="up_goods" value="<?php echo $ngoods['up_goods'] ?>" />
		<input type="hidden" name="youfei" value="0" />
		<input readonly="" id="<?php echo $k;?>" name="goods_number" value="<?php echo $row['number'];?>" class="inputBg"  type="text">
</div>

<div class="up_bottom">
	<div class="ub1"><a href="/">首页</a></div>
	<div class="ub2">价格￥ <b id="up_price"></b></div>
	<div class="ub3">
		<input class="submitbtn" value="立即抢购" type="submit" align="absmiddle" onclick="return checkvar()" />
	</div>
</div>
</form>
</div>
<br /><br />
<?php  $thisurl = ADMIN_URL.'mycart.php'; ?> 
<script language="javascript" type="text/javascript">
document.getElementById('up_price').innerHTML = $('input:radio:checked').val();
//2位小数
function toDecimal(x) {  
	var f = parseFloat(x);  
	if (isNaN(f)) {  
		return;  
	}  
	f = Math.round(x*100)/100;  
	return f;  
} 

function ajax_clear(){
	if(confirm('确定吗')){
		window.location.href='<?php echo ADMIN_URL;?>mycart.php?type=clear';
		return true;
	}
	return false;
}
$('.showaddress').live('click',function(){
	var vv= $(this).val();
	if(vv==0){
	$('.userreddinfo').show();
	}else{
	$('.userreddinfo').hide();
	}
	//$('.userreddinfo').toggle();
});

function typemoney(){
	document.getElementById('up_price').innerHTML = $('input:radio:checked').val();
}

function checkvar(){
	pp = $('input[name="pay_id"]:checked').val(); 
	if(typeof(pp)=='undefined' || pp ==""){
		alert("请选择支付方式！");
		return false;
	}
	
	xinghao = $('input[name="xinghao"]:checked').val(); 
	if(typeof(xinghao)=='undefined' || xinghao ==""){
		alert("请选择购买型号");
		return false;
	}
	if(xinghao =="0"){
		alert("金额不能为0");
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
		
			address = $('input[name="address"]').val(); 
			if(typeof(address)=='undefined' || address ==""){
				alert("详细地址不能为空！");
				return false;
			}
			
			mobile = $('input[name="mobile"]').val(); 
			tel = $('input[name="tel"]').val(); 
			if(mobile =="" && tel ==""){
				alert("请输入手机或者电话号码！");
				return false;
			}
	}	

	return true;
}

function slbg(divbg,goods_id){
	window.location.href = SITE_URL+'mycart.php?type=checkout&up=up&goods_id='+goods_id;
	/**
	document.getElementById('sl1').style.backgroundColor = "#392933";
	document.getElementById('sl2').style.backgroundColor = "#392933";
	document.getElementById('sl3').style.backgroundColor = "#392933";
	document.getElementById(divbg).style.backgroundColor = "#E03106";
	**/
}
</script>
