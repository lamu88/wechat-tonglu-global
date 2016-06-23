<!--<div id="home" style="border-top:4px solid #4c764e">
	<div id="header">
		<div class="logo"><img src="<?php echo $this->img('logo.png');?>" height="44" /></div>
		<div class="logoright">
			<div>
			<a href="<?php echo ADMIN_URL.'user.php';?>"></a>
			</div>
		</div>
	</div>
</div>-->
<?php
$shareinfo = $lang['shareinfo'];
if(!empty($shareinfo)){
?>
<div style=" position:relative;height:44px; line-height:44px; background:url(<?php echo $this->img('gzbg.png');?>) repeat; position:fixed; top:0px; left:0px; width:100%; z-index:9999">
	<img src="<?php echo $shareinfo['headimgurl'];?>" height="40" style="margin:2px 8px 2px 10px; float:left" />
	<p style=" padding-top:2px; padding-bottom:2px; line-height:20px; color:#FFF; font-weight:bold">
	来自好友<font color="#00761d"><?php echo $shareinfo['nickname'];?></font>的推荐<br/>立即关注，将获得更多的折扣！
	</p>
	<a href="<?php echo ADMIN_URL.'art.php?id=7';?>" style=" position:absolute; right:10px; top:6px; z-index:99; cursor:pointer; height:35px;"><img src="<?php echo $this->img('guanzhu.png');?>" /></a>
</div>
<?php } ?>