
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<div id="main" style="min-height:300px">
	<div style="margin:10%; margin-bottom:0px; background:#FFF; padding:10%; text-align:center">
		<img src="<?php echo $qcodeimg;?>" style=" width:100%;max-width:100%; cursor:pointer" />
	</div>
	<div style="margin:10%; margin-top:0px; background:#4c4343;text-align:center; height:45px; line-height:45px; font-size:14px; font-weight:bold; color:#FFF;-webkit-box-shadow: 0px 4px 4px #abaaaa; margin-bottom:5px;">
	快来扫一扫，抢占东家地盘！
	</div>
	<div style="margin:10%; margin-top:0px;height:45px; line-height:45px; font-size:14px; font-weight:bold; color:#FFF;-webkit-box-shadow: 0px 4px 4px #abaaaa; margin-bottom:5px; line-height:22px">
	<span style=" display:block; color:#999999">点击复制链接</span>
	<p class="copyurl" style="width:100%; color:#666; background:#FAFAFA; border:none; overflow:hidden" onclick="clickselect()"><?php echo $thisurl;?></p>
	</div>
	<a href="<?php echo ADMIN_URL;?>user.php?act=ajax_down_img" class="addcar" style=" width:90px; background:#4f82b4; position:fixed; left:5px; bottom:55px;height:24px; line-height:24px; color:#FFF" onclick="alert('暂不支持该类型下载，正在努力解决');">下载二维码</a>
</div>
<div style="height:40px; clear:both"></div>
<script type="text/javascript">
function clickselect(obj){
	$(obj).select();
}
</script>

<?php $this->element('24/footer',array('lang'=>$lang)); ?>