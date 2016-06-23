<link href="<?php echo ADMIN_URL;?>tpl/2/css.css" rel="stylesheet" type="text/css" />

<style type="text/css">
body{ background:#F7F7F2}
.texts img{ max-width:100%; text-indent:0px}
</style>
<a name="news"></a>
<div id="ui-header">
<div class="fixed">
<a class="ui-title" id="popmenu" style="color:#FFF"><?php echo $rt['art']['article_title'];?></a>
<a class="ui-btn-left_pre" href="javascript:history.go(-1)"></a>
<a class="ui-btn-right_home" href="<?php echo ADMIN_URL.'site.php';?>"></a>
</div>
</div>
<div style="height:46px; clear:both"></div>
<div class="page-bizinfo">
	<div class="texts" id="content">
	<?php echo $rt['art']['content'];?>
	</div>

</div>
<div style="height:40px; clear:both"></div>
<section class="mod-share share-1">
        <a class="share-btn" onclick="showPop('#pop-share')"><span class="ico-share">发送给朋友</span></a>
        <a class="share-btn" onclick="showPop('#pop-share')"><span class="ico-pyq">分享到朋友圈</span></a>
</section>
	
<a class="newsfooter" href="#news" target="_self"><span class="top">返回顶部</span></a>

<div id="pop-share" style=" display:none;width:100%; height: 100%; position:absolute; top:0px; right: 0px; z-index:9999999; opacity: 0.9;background: url(<?php echo ADMIN_URL.'tpl/2/images/text.png';?>) 100% 0% / 100% no-repeat rgb(0, 0, 0);" onclick="$(this).hide();"></div>

<script type="text/javascript">
function showPop(obj){
	$(obj).show();
	$('body,html').animate({scrollTop:0},500);
}
</script>
<div style="height:55px; clear:both"></div>