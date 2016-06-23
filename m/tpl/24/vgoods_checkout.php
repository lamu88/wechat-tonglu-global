
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<style type="text/css">
.checkout{ background:#FFF; padding-top:10px; padding-bottom:20px}
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
line-height: 18px;
padding: 3px 0px 3px 0px;
}
.checkout .userreddinfo td {
line-height: 18px;
padding: 2px 0px 2px 0px;
}
.checkout td label{ line-height:22px;}
label{ cursor:pointer}
.pw{ line-height:23px; height:23px;}
.addgallery i{font-style:normal;}
.item-box-buy-btn {
font-size: 12px;
color: #456f9a;
border: 1px solid #456f9a;
border-radius: 5px;
cursor: pointer;
float: right;
width: 80px;
height: 25px;
line-height: 25px;
text-align: center;
overflow: hidden;
white-space: nowrap;
background:#C7ECF3;
}
.addgallery{ padding-left:14px;background:url(<?php echo $this->img('+.png');?>) 3px center no-repeat}
.removegallery{ padding-left:14px;background:url(<?php echo $this->img('-.png');?>) 3px center no-repeat}
</style>
<div id="main" style="padding-top:0px; min-height:300px">
	<div class="checkout">
	<form action="<?php echo ADMIN_URL;?>vgoods.php?type=confirm" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS">
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
		 <?php 
				 $goodslist = $rt['goodslist']; 
				  if(!empty($goodslist)){
				  $total= 0;
				  $gids = array();
				  $uid = $this->Session->read('User.uid');
				  $rank = $this->Session->read('User.rank');
				  foreach($goodslist as $k=>$row){
				  	 // $row['number'] = 1;
					  $gids[] = $row['goods_id'];
					  $total +=$row['pifa_price'];
		   ?>
		
			<tr>
				<td style="width:80px; text-align:center; height:80px; padding-top:10px; overflow:hidden; border-bottom:1px solid #ededed;" valign="top">
					<img src="<?php echo SITE_URL.$row['goods_thumb'];?>" title="<?php echo $row['goods_name'];?>" border="0" style="width:78px; height:78px; border:1px solid #ededed; padding:1px; margin-left:5px;">
				</td>
				<td style="text-align:left;border-bottom:1px solid #ededed; vertical-align:top" valign="top">
				<p style="padding-left:10px; padding-right:36px; position:relative; line-height:18px;">
					<?php echo $row['goods_name'];?>
				</p>
				<?php if(!empty($row['spec'])){
				 echo '<p style="padding-left:10px;">'.implode("、",$row['spec']).'</p>';
				 } ?>
				 <p style=" padding-left:10px;font-size:12px;line-height:20px;" class="raturnprice raturnprice<?php echo $k;?>"><!--专柜价:<font color="#5f5f5f">￥<?php echo $row['shop_price'];?></font>&nbsp;&nbsp;-->本店价:<font color="#FF0000" class="gprice">￥<?php echo $row['pifa_price']>0 ? $row['pifa_price']  : $row['shop_price'];?></font></p>
				 <div class="item" style="height:20px; line-height:20px; position:relative; padding-left:10px; padding-top:7px">
						<a class="jian" style="cursor:pointer; display:block; float:left; width:35px; height:22px;line-height:22px;text-align:center; font-size:18px; font-weight:bold; border:1px solid #ccc; background:#ededed">-</a><input readonly="" id="<?php echo $row['pifa_price']>0 ? $row['pifa_price']  : $row['shop_price'];?>" name="goods_number" value="1" class="inputBg" style="float:left;text-align: center; width:20px; height:22px; line-height:22px;border-bottom:1px solid #ccc; border-top:1px solid #ccc" type="text"> <a class="jia" style="cursor:pointer; display:block; float:left; width:35px; height:22px;line-height:22px;text-align:center; font-size:18px; font-weight:bold; border:1px solid #ccc; background:#ededed">+</a><b style="margin-left:3px;"><?php  echo $row['goods_unit'];?></b>
						&nbsp;&nbsp;小计:<font color="#FF0000">￥<span class="gzprice"><?php echo $row['pifa_price'];?></span></font>
				  </div>
				</td>
			</tr>
			
			 <?php } } ?>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%;border-bottom:1px solid #ededed; margin-top:10px;">
			<tr>
				  <td align="right" width="22%"><span>支付方式：</span></td>
				  <td align="left" width="78%">
				  <?php 
				if(!empty($rt['paymentlist'])){
					echo '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;"><tr>';
					foreach($rt['paymentlist'] as $k=>$row){
					?>
					  <td><label><span><input name="pay_id"  id="pay_id"<?php if($k=='0'){ echo ' checked="checked"';}?> value="<?php echo $row['pay_id'];?>" type="radio"></span><strong><?php echo $row['pay_name'];?></strong></label></td>
					<?php
					}
					echo '</tr></table>';
				}
				?>
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
			<p style="height:44px; line-height:22px;font-size:16px; font-weight:bold; color:#01AEAE; padding-top:5px;">结算总金额：<br/>￥<span class="ztotals"><?php echo $total; ?></span>元</p>
			<p style="height:30px; line-height:30px; margin-top:10px;">
			<input value="确认提交" type="submit" align="absmiddle" onclick="return checkvar()" style="width:110px; height:30px; line-height:30px; background:#ff6400; font-size:20px; color:#FFFFFF; font-weight:bold; text-align:center; cursor:pointer; float:left"/>
			</p>
	  </div>
	  <input name="goods_id" type="hidden" value="<?php echo implode('+',$gids);?>" />
	</form>
	</div>
</div>

<script language="javascript" type="text/javascript">
//2位小数
function toDecimal(x) {  
	var f = parseFloat(x);  
	if (isNaN(f)) {  
		return;  
	}  
	f = Math.round(x*100)/100;  
	return f;  
} 

function checkvar(){
	pp = $('input[name="pay_id"]:checked').val(); 
	if(typeof(pp)=='undefined' || pp ==""){
		alert("请选择支付方式！");
		return false;
	}

	return true;
}


//数量减1
$('.jian').live('click',function(){
	ob = $(this).parent();
	numobj = ob.find('input[name="goods_number"]');
	vall = $(numobj).val();
	if(!(vall>0)){
		ob.val('1');
		return false;
	}
	if(vall>1){
		$(numobj).val((parseInt(vall)-1));
	}
	nn = $('.mycarts').html();
	$('.mycarts').html(parseInt(nn)-1);
	change_number(numobj);
});
//数量加1
$('.jia').live('click',function(){
	ob = $(this).parent();
	numobj = ob.find('input[name="goods_number"]');
	vall = $(numobj).val();
	if(!(vall>0)){
		$(ob).val('1');
		return false;
	}
	$(numobj).val((parseInt(vall)+1));
	nn = $('.mycarts').html();
	$('.mycarts').html(parseInt(nn)+1);
	change_number(numobj);
});
//改变商品价格
function change_number(obj){
	price = $(obj).parent().find('input').attr('id');
	numbers = $(obj).val();
	if(!(numbers>0)){
	 	numbers = 1;
	 	$(obj).val('1');
	}
	
	$(obj).parent().find('.gzprice').html(toDecimal(price*numbers));
	$('.ztotals').html(toDecimal($('.ztotals').html())+toDecimal(price));
	return true;
}

</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>