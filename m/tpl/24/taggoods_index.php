<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=12" media="all" />
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<?php $this->element('guanzhu',array('shareinfo'=>$lang['shareinfo']));?>

<?php if(!empty($rt['ad'])){?>
<!--顶栏焦点图-->
<div class="flexslider" style="margin-bottom:0px;">
	 <ul class="slides">
	 <?php if(!empty($rt['ad']))foreach($rt['ad'] as $row){
	 ?>			 
		<li><a href="<?php echo $row['url'];?>"><img src="<?php echo $row['img'];?>" width="100%" alt="<?php echo $row['name'];?>"/></a></li>
	 <?php } ?>												
	  </ul>
</div>
<?php } ?>

<div id="main">
    <?php if(!empty($rt['cat']))foreach($rt['cat'] as $row){?>
	<div class="indexitem" style="padding-bottom:5px; padding-top:0px">
		<p class="ptitle"><span style="float:left; width:70%"><a href="javascript:void(0)"><?php echo $row['cat_name'];?></a></span></p>
		<?php if(!empty($row['cat_img'])&&file_exists(SYS_PATH.$row['cat_img'])){?>
		<p><img src="<?php echo SITE_URL.$row['cat_img'];?>" style="width:100%"/></p>
		<?php } ?>
		<ul class="goodslists">
		<?php if(!empty($rt['categoodslist'][$row['tcid']]))foreach($rt['categoodslist'][$row['tcid']] as $k=>$rows){?>
				<li style="width:50%; float:left; position:relative;">
				<div style="padding:4px"><?php
				$url = !empty($rows['url']) ? $rows['url'] : ADMIN_URL.($rows['is_jifen']=='1'?'exchange':'product').'.php?id='.$rows['goods_id'];
				$img = !empty($rows['img']) ? SITE_URL.$rows['img'] : SITE_URL.$rows['goods_img'];
				$gname = !empty($rows['gname']) ? $rows['gname'] : $rows['goods_name'];
				?>
				<a style="background:#fff; padding:5px; display:block;" href="<?php echo $url;?>">
					<div style=" height:160px; overflow:hidden; text-align:center; position:relative; z-index:10;">
						<img src="<?php echo $img;?>" style="max-width:99%;display:inline;" alt="<?php echo $gname;?>"/>
					</div>
					<p style="line-height:20px; height:20px; overflow:hidden; text-align:center; padding-bottom:5px; border-bottom:1px #dedede dotted"><?php echo $gname;?></p>
					<p style="line-height:24px; height:24px; overflow:hidden; width:60%; float:left"><span style="float:left">抢购价:</span><b class="price" style="font-size:12px; float:left; padding-left:3px;">￥<?php echo str_replace('.00','',$rows['pifa_price']);?></b></p>
					<p style="line-height:24px; height:24px; overflow:hidden; color:#999999; width:40%; float:right; text-align:right"><del>￥<?php echo str_replace('.00','',$rows['shop_price']);?></del></p>
					<div class="clear"></div>
				</a>
				</div>
			</li>
		<?php } ?>
		<div class="clear"></div>
		</ul>
	</div>
<?php } ?>

</div>
<?php
$title = $rt['cateinfo']['cat_name'];
$imgs = $imgs[rand(0,count($imgs)-1)];
?>
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
		$.post('<?php ADMIN_URL;?>product.php',{action:'ajax_share',type:a,msg:c,thisurl:'<?php echo Import::basic()->thisurl();?>',imgurl:'<?php echo $imgs;?>',title:'<?php echo $title;?>'},function(data){
		});
  }
  
  document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        window.shareData = {  
            "imgUrl": "<?php echo $imgs;?>",
            "LineLink": '<?php echo $thisurl;?>',
            "Title": "<?php echo $title;?>",
            "Content": "<?php echo !empty($rt['cateinfo']['cat_desc']) ? $rt['cateinfo']['cat_desc'] : $title;?>"
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
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); unset($rt);?>