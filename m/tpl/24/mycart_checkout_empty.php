
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
.ctop{ height:150px; background:url(<?php echo $this->img('catbg.png');?>) bottom center no-repeat}
</style>
<div id="main" style="padding:0px; min-height:300px">
		<div class="ctop"></div>
		<p style="font-size:20px; text-align:center; height:40px; line-height:40px; color:#b8b8b8">购物车还是空空的</p>
		<p style="font-size:20px; text-align:center; height:24px; line-height:24px; color:#b8b8b8">现在就去选购吧</p>
		<p style=" padding-top:20px;text-align:center">
			<a style="width:80px; height:30px; line-height:30px; border-radius:5px; border:1px solid #b8b8b8; display:block; margin:0px auto; font-size:18px; text-decoration:underline" href="<?php echo ADMIN_URL;?>">去购物</a>
		</p>
		
</div>
