<div class="clear10"></div>
<style type="text/css">
.ajacartlist p,.ajacartlist .item{ line-height:21px;}
</style>
<div style="border-radius:5px; border:1px solid #ededed;" class="ajacartlist">
<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
 <?php
		  if(!empty($rt['goodslist'])){
		  $total= 0;
		  $uid = $this->Session->read('User.uid');
		  $active = $this->Session->read('User.active');
		  $rank = $this->Session->read('User.rank');
		  foreach($rt['goodslist'] as $k=>$row){
		  	  if(!($row['goods_id'])>0) continue;
			  if($row['is_alone_sale']=='0'&&(empty($rt['gift_goods_ids']) || !in_array($row['goods_id'],$rt['gift_goods_ids']))){ //条件不满足者  不允许购买赠品
			  		$gid = $row['goods_id'];
			  		$this->Session->write("cart.{$gid}","");
					continue;
			  }
			 
			  $onetotal = $row['pifa_price'];
			  $total +=$onetotal*$row['number'];
   ?>
	<tr>
		<td style="width:80px; text-align:center; height:80px; padding:10px; overflow:hidden">
			<a href="<?php echo ADMIN_URL.'product.php?id='.$row['goods_id'];?>"><img src="<?php echo $row['goods_thumb'];?>" title="<?php echo $row['goods_name'];?>" border="0" style="width:78px; height:78px; border:1px solid #ededed; padding:1px;"></a>
		</td>
		<td style="padding:10px; text-align:left;" valign="top">
		<p><font color="red"><?php if($row['is_alone_sale']=='0'||$row['is_qianggou']=='1' || $row['is_jifen_session']=='1'){
							if($row['is_jifen_session']=='1'){
								echo '[积分商品]';
							}else{
								echo $row['is_qianggou']=='1' ?  '[抢购商品]' : '[赠品]';
							}
					  }else{
						echo '[折扣]';
					  }
				?></font>
			<a href="<?php echo ADMIN_URL.'product.php?id='.$row['goods_id'];?>" class="f6"><?php echo $row['goods_name'];?></a>
			<?php if(!empty($row['buy_more_best'])){echo '<br />该商品实行<font style="color:#FE0000;font-weight:bold">['.$row['buy_more_best'].']</font>促销活动，欢迎订购！';}?></p>
		<?php if(!empty($row['spec'])){
		 echo '<p>'.implode("、",$row['spec']).'</p>';
		 } ?>
		 <p style="font-size:14px; color:#FF0000; font-weight:bold" class="raturnprice">￥<?php echo $onetotal*$row['number'];?></p>
		<div class="item" style="height:24px; line-height:24px; position:relative">
			<?php if($row['is_alone_sale']=='0' || $row['is_jifen_session']=='1'){
				if($row['is_jifen_session']=='1'){
					echo '需&nbsp;'.$row['need_jifen']*$row['number'].'&nbsp;积分<br />数量&nbsp;'.$row['number'];
				}else{
					echo ($row['is_qianggou']=='1' ?  '数量&nbsp;' .$row['number']:  '数量&nbsp;'.$row['number']);
				}
			}else{?>
				 <a class="jian" style="cursor:pointer; display:block; float:left; width:22px; height:22px; text-align:center; font-size:14px; font-weight:bold; border:1px solid #ccc; background:#ededed">-</a><input name="goods_number" id="<?php echo $k;?>" lang="<?php echo $onetotal;?>" value="<?php echo $row['number'];?>" class="inputBg" style=" float:left;text-align: center; width:28px; height:22px; line-height:22px;border-bottom:1px solid #ccc; border-top:1px solid #ccc" type="text"> <a class="jia" style="cursor:pointer; display:block; float:left; width:22px; height:22px; text-align:center; font-size:14px; font-weight:bold; border:1px solid #ccc; background:#ededed">+</a><b style="float:left; margin-left:3px;"><?php  echo $row['goods_unit'];?></b>
			<?php } ?>
			<span style="border-radius:50%; height:20px; line-height:20px; width:20px; display:block; float:right;background:#999; text-align:center; font-size:16px; font-weight:bold; color:#FFF; cursor:pointer" class="delcartid" id="<?php echo $k;?>">-</span>
			</div>
			
		</td>
	</tr>
	 <?php } }else{ ?>
   		 <tr>
		 <th colspan="2" align="center" style=" padding:20px;font-size:20px; color:#9a0000">你的购物车为空！</th>
		 </tr>
	<?php } ?>
	<tr>
	  	<td align="right" colspan="2" style="padding:10px; border-top:1px solid #ededed">
<p style="line-height:26px; padding-top:10px"><img src="<?php echo SITE_URL;?>theme/images/clearcart.jpg" width="90" height="24" onclick="location.href='<?php echo ADMIN_URL.'mycart.php?type=clear';?>'" style="cursor:pointer"/></p> 
<div style="text-align:right; height:30px; line-height:30px; color:#E135A6">商品总价(不含运费)：<span class="totalprice"><?php echo $total;?> </span>元&nbsp;&nbsp;</div> 
<div><a href="<?php echo ADMIN_URL;?>catalog.php"><img src="<?php echo SITE_URL.'theme/images/btu_continueshop.gif'?>" width="90" height="30"/></a>&nbsp;<a href="<?php echo ADMIN_URL;?>mycart.php?type=checkout"><img src="<?php echo SITE_URL.'theme/images/btu_topay.gif'?>" width="90" height="30"/></a></div> 
<div class="clear10"></div>
		</td>
	 </tr>
</table>
</div>