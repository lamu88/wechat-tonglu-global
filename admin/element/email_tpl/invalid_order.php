<div>
<p>亲爱的<b><?php echo $rt['user_name'];?></b>，订单已被设为失效状态！</p>
<p>当前操作订单号：<b><?php echo $rt['order_sn'];?><b>，<a href="<?php echo $rt['orderinfourl'];?>" target="_blank">订单详情查看</a></p>
<p>如果点击以上链接无效，那么复制下面链接在浏览器的地址栏上按回车键！</p>
<p><a href="<?php echo $rt['orderinfourl'];?>" target="_blank"><?php echo $rt['orderinfourl'];?></a></p>
<p>推广图片<br /><a href="<?php echo SITE_URL;?>"><img src="<?php echo SITE_URL;?>theme/images/logo.gif" /></a></p>
</div>