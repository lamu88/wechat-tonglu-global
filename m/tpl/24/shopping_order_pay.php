

<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
.iconinfo {
position: relative;
margin: 20px auto;
width: 200px;
text-align: center;
}
.iconinfo .ico {
display: block;
margin: 20px auto;
width: 48px;
height: 48px;
-webkit-background-size: cover;
background-size: cover;
background-repeat: no-repeat;
}
.ico-success {
background-image: url(<?php echo $this->img('ico-success.png');?>); float:left;
}
.iconinfo strong {
font-size: 16px;
font-weight: normal;
display: block;
line-height: 22px; float:left; padding-top:20px;
}
.goodslist p{ line-height:23px;}
.btn-buy {
width: 200px;
}
.btn-buy,.ui-btn,.ui-btn {
width: 200px;
padding: 0;
height: 37px;
border: 0;
border-bottom: 2px solid #b91d11;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
background-color: #ec4e4f;
-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.15), inset 0 1px rgba(254,101,101,0);
-moz-box-shadow: 0 1px 1px rgba(0,0,0,.15), inset 0 1px rgba(254,101,101,0);
box-shadow: 0 1px 1px rgba(0,0,0,.15), inset 0 1px rgba(254,101,101,0);
background: #ea4748;
line-height: 37px;
text-align: center;
font-size: 15px;
color: #fff;
text-decoration: none;
}
.dddddd{background-color: #1bb627;border-bottom: 2px solid #1bb674;}
.ui-btn {
display: block;
margin: 5px auto 0;
}
.ui-btn-text{ color:#fff}
</style>
<div id="main" style="padding:5px; padding-top:0px; min-height:300px">
	<div class="iconinfo">
		<i class="ico ico-success"></i>
		<?php if($rt['orderinfo']['pay_status'] =='1'){?>
		<strong>已支付订单，<br>及时在会员中心留意订单状态！</strong>
		<?php }elseif($rt['orderinfo']['pay_status'] =='2'){?>
		<strong>已退款，<br>请及时将货品退还！</strong>
		<?php } else{?>
		<strong>请您及时付款 <br>以便订单尽快处理！</strong>
		<?php } $ordergoods = $rt['goodslist'];?>
	</div>
	<div class="goodslist">
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%;border-radius:5px; border:1px solid #ededed; margin-top:8px; padding-bottom:8px;">
		<?php if(!empty($ordergoods))foreach($ordergoods as $row){?>
			<tr>
				<td style="width:25%; text-align:center; height:80px; padding-top:10px; overflow:hidden;">
					<img src="<?php echo SITE_URL.$row['goods_thumb'];?>" title="<?php echo $row['goods_name'];?>" border="0" style="width:100%; border:1px solid #ededed; padding:1px; margin-left:5px;">
				</td>
				<td align="left" valign="top" style="vertical-align:top">
				<p style="padding-left:10px; padding-top:10px">
				<?php echo $row['goods_name'];?>
				</p>
				<?php if(!empty($row['goods_attr'])){
				 echo '<p style="padding-left:10px;">'.$row['goods_attr'].'</p>';
				 } ?>
				  <p style=" padding-left:10px;font-size:14px;">专柜价:<del style="color:#FF0000;">￥<?php echo $row['market_price'];?></del>&nbsp;&nbsp;惊喜价:<font color="#FF0000">￥<?php echo $row['goods_price'];?></font></p>
				  <p style="padding-left:10px;">数量:<?php echo $row['goods_number'];?><b style="margin-left:3px;"><?php  echo $row['goods_unit'];?></b>&nbsp;&nbsp;小计:<font color="#FF0000">￥<?php echo $row['goods_price']*$row['goods_number'];?></font></p>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td align="left" colspan="2">
			<p style="padding-top:5px; padding-left:10px; font-size:14px;">订单总金额：<font color="#FF0000"><b>￥<?php echo $rt['orderinfo']['order_amount'];?></b>+￥<b><?php echo $rt['orderinfo']['shipping_fee'];?></b>(邮费)=￥<?php echo $sz = format_price($rt['orderinfo']['order_amount']+$rt['orderinfo']['shipping_fee']);?></font></p>
			<p style="padding-top:5px; padding-left:10px; font-size:14px;">支付方式：
			<?php if($rt['orderinfo']['pay_id']==7){?><b><?php echo $rt['orderinfo']['pay_name'].($rt['mygouwubi'] > 0 ? '(<font color=red>￥'.$rt['mygouwubi'].'</font>)' : '');?></b><?php }else{?><b><?php echo $rt['orderinfo']['pay_name'];?></b><?php } ?>
			&nbsp;&nbsp;配送方式：<b><?php echo $rt['orderinfo']['shipping_name'];?></b></p>
			</td>
		</tr>
		<?php if($sz > $rt['mygouwubi'] && $rt['orderinfo']['pay_id']==7){?>
		<tr>
			  <td align="left" colspan="2">
			  <?php 
			if(!empty($rt['paymentlist'])){
				echo '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;"><tr>';
				?>
				<td align="left" width="85" style="font-size:14px"><span style="padding-left:10px;">其他支付：</span></td>
				<?php
				foreach($rt['paymentlist'] as $k=>$row){
				if($row['pay_id']=='7'){ continue;
				}else{
				?>
				  <td align="left" style="font-size:14px"><label style="cursor:pointer"><span><input name="pay_id"  id="pay_id" value="<?php echo $row['pay_id'];?>" type="radio" onclick="ajax_update_payid(<?php echo $row['pay_id'];?>,<?php echo $rt['orderinfo']['order_id'];?>)"></span><strong><?php echo $row['pay_name'];?></strong></label></td>
				<?php
				}
				}
				echo '</tr></table>';
			}
			?>
			</p>
			  </td>
			</tr>
		 <?php } ?>
		</table>
		<?php if($rt['orderinfo']['pay_status'] !='1'){?>
		<div>
			<a href="<?php echo ADMIN_URL.'mycart.php?type=fastpay2&oid='.$rt['orderinfo']['order_id'];?>" class="btn-buy button ui-btn ui-btn-text-only" style="width:80%" onclick="return checkpay()"><span class="ui-btn-text">立即支付</span></a>
			
		</div>
		<?php } ?>
	</div>
</div>
<div class="show_zhuan" style=" display:none;width:100%; height:100%; position:fixed; top:0px; right:0px; z-index:9999999;filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9; background:url(<?php echo $this->img('gz/121.png');?>) right top no-repeat #000;background-size:100% auto;" onclick="$(this).hide();"></div>	
<?php
 $thisurl = Import::basic()->thisurl();
 $rr = explode('?',$thisurl);
 $t2 = isset($rr[1])&&!empty($rr[1]) ? $rr[1] : "";
 $dd = array();
 if(!empty($t2)){
 	$rr2 = explode('&',$t2);
	if(!empty($rr2))foreach($rr2 as $v){
		$rr2 = explode('=',$v);
		if($rr2[0]=='from' || $rr2[0]=='isappinstalled'|| $rr2[0]=='code'|| $rr2[0]=='state') continue;
		$dd[] = $v;
	}
 }
 $thisurl = $rr[0].'?'.(!empty($dd) ? implode('&',$dd) : 'tid=0');
?>
<script type="text/javascript">
  function _report(a,c){
  	
  }
  document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        window.shareData = {  
            "imgUrl": "<?php echo $this->img('ico-success.png');?>",
            "LineLink": '<?php echo $thisurl;?>',
            "Title": "炫耀一下，支付一下吧",
            "Content": "有惊喜哦"
        };
        // 发送给好友
        WeixinJSBridge.on('menu:share:appmessage', function (argv) {
            WeixinJSBridge.invoke('sendAppMessage', { 
                "img_url": window.shareData.imgUrl,
                "img_width": "640",
                "img_height": "640",
                "link": window.shareData.LineLink,
                "desc": window.shareData.Content,
                "title": window.shareData.Title
            }, function (res) {
                _report('send_msg', res.err_msg);
            })
        });
        // 分享到朋友圈
        WeixinJSBridge.on('menu:share:timeline', function (argv) {
            WeixinJSBridge.invoke('shareTimeline', {
                "img_url": window.shareData.imgUrl,
                "img_width": "640",
                "img_height": "640",
                "link": window.shareData.LineLink,
                "desc": window.shareData.Content,
                "title": window.shareData.Title
            }, function (res) {
                _report('timeline', res.err_msg);
            });
        });
        // 分享到微博
        WeixinJSBridge.on('menu:share:weibo', function (argv) {
            WeixinJSBridge.invoke('shareWeibo', {
                "content": window.shareData.Content,
                "url": window.shareData.LineLink,
            }, function (res) {
                _report('weibo', res.err_msg);
            });
        });
        }, false)
		
		function checkpay(){
			var tt = true;
			<?php if($sz > $rt['mygouwubi'] && $rt['orderinfo']['pay_id']==7){?>
				pp = $('input[name="pay_id"]:checked').val();
				if(pp>0){
				}else{
					tt = false;
					alert('余额不足,请选择其他支付方式！');
				}
			<?php } ?>
			return tt;
		}
		
		function ajax_update_payid(payid,oid){
			$.post(SITE_URL+'mycart.php',{action:'ajax_update_payid',payid:payid,oid:oid},function(data){})
		}
</script>

<?php $this->element('24/footer',array('lang'=>$lang)); ?>
