<?php 
$rnd=rand(1,3)/100;//每次赠送佣金1-3分钱
$ch = curl_init();  
curl_setopt ($ch, CURLOPT_URL, "http://fenxiao123.weiwin.cc/WeixinPay/ajax.pay.php?uid=ofjXasoHxTty2o-IqQC8QGXTIfP8&amount=1&DES=CESHI");  //直接微信支付打帐
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);  
$result=curl_exec($ch);  //转账结果
curl_close($ch);
if($result=='0'){//转账失败，提示用户
	echo "<script type='text/javascript'>alert('支付失败，请联系客服获取帮助。');</script>";
	exit;
}
?>