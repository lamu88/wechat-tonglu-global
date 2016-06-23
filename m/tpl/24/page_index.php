<?php $this->element('guanzhu',array('shareinfo'=>$lang['shareinfo']));?>

<?php if(!empty($rt['lunbo'])){?>
<!--顶栏焦点图-->
<div class="flexslider">
	 <ul class="slides">
	 <?php if(!empty($rt['lunbo']))foreach($rt['lunbo'] as $row){
	 $a = basename($row['ad_img']);
	 ?>			 
		<li><a href="<?php echo $row['ad_url'];?>"><img src="<?php echo SITE_URL.$row['ad_img'];?>" width="100%" alt="<?php echo $row['ad_name'];?>"/></a></li>
	 <?php } ?>												
	  </ul>
</div>
<?php } ?>
<div id="main">
	<form id="form1" name="form1" method="get" action="<?php echo ADMIN_URL;?>catalog.php">
	  <div class="search_index">
		<div class="right"><input type="image" src="<?php echo $this->img('submit3.png');?>" value=""></div>
		<div class="left"><input type="text" name="keyword" id="title" class="input1" value="<?php echo !empty($keyword)&&!in_array($keyword,array('is_promote','is_qianggou','is_hot','is_best','is_new')) ? $keyword : "输入商品关键字";?>" onfocus="if(this.value=='输入商品关键字'){this.value='';}" onblur="if(this.value==''){this.value='输入商品关键字';}"></div>
	  </div>
	</form>
	<?php
	if(!empty($rt['navtop'])){
	?>
		<div class="navtop">
		<?php foreach($rt['navtop'] as $row){?>
		<a href="<?php echo $row['url'];?>">
		<?php if(!empty($row['img'])){?><img src="<?php echo SITE_URL.$row['img'];?>" /><?php } ?>
		<p><?php echo $row['name'];?></p>
		</a>
		<?php } ?>
		<div class="clear"></div>
		</div>
	<?php
	}
	?>

	<?php if(!empty($rt['cat']))foreach($rt['cat'] as $row){?>
		<?php if(!empty($row['cat_img'])&&file_exists(SYS_PATH.$row['cat_img'])){?>
		<p><a href="<?php echo $row['cat_url'];?>"><img src="<?php echo SITE_URL.$row['cat_img'];?>" style="width:100%"/></a></p>
		<?php } ?>
		<ul class="goodslists">
		<?php if(!empty($rt['goods'][$row['cat_id']]))foreach($rt['goods'][$row['cat_id']] as $k=>$rows){?>
			<a href="<?php echo ADMIN_URL.($row['is_jifen']=='1'?'exchange':'product').'.php?id='.$rows['goods_id'];?>">
			<li class="goodslistsli">
				<div  class="goodslistsliimg">
					<img src="<?php echo SITE_URL.$rows['goods_img'];?>" />
				</div>
                <div class="goodsinfo">
                    <div class="txtTitle"><?php echo $rows['goods_name'];?></div>
                    <div class="inGdesc">&nbsp;&nbsp;<?php echo $rows['sort_desc'];?></div>
                    <div>
                     	<div style="float:left"><b class="price">￥&nbsp;&nbsp;<?php echo str_replace('.00','',$rows['pifa_price']);?></b></div>
                     	<div class="buybtn"><a href="<?php echo ADMIN_URL.($row['is_jifen']=='1'?'exchange':'product').'.php?id='.$rows['goods_id'];?>">立即购买</a></div>
                    </div>
                </div>
			</li>
			</a>
		<?php } ?>
		</ul>
<?php } ?>
</div>

<div class="clear"> </div>
<div class="show_zhuan" style="display:none;width:100%; height:100%; position:fixed; top:0px; right:0px; z-index:9999999;filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9; background:url(<?php echo $this->img('gz/121.png');?>) right top no-repeat #000;background-size:100% auto;" onclick="$(this).hide();"></div>
<?php
 $thisurl1 = Import::basic()->thisurl();
 $rr = explode('?',$thisurl1);
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
		$.post('<?php ADMIN_URL;?>product.php',{action:'ajax_share',type:a,msg:c,thisurl:'<?php echo Import::basic()->thisurl();?>',imgurl:'<?php echo !empty($lang['site_logo'])? SITE_URL.$lang['site_logo'] : $this->img('logo4.png');?>',title:'<?php echo $title;?>'},function(data){
		});
  }
<?php
$t = mktime();
$signature = sha1('jsapi_ticket='.$lang['jsapi_ticket'].'&noncestr='.$lang['nonceStr'].'&timestamp='.$t.'&url='.$thisurl1);
?>		
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $lang['appid'];?>', // 必填，公众号的唯一标识
    timestamp: '<?php echo $t;?>', // 必填，生成签名的时间戳
    nonceStr: '<?php echo $lang['nonceStr'];?>', // 必填，生成签名的随机串
    signature: '<?php echo $signature;?>',// 必填，签名，见附录1
    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline','onMenuShareQQ'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});

wx.ready(function () {
	wx.onMenuShareAppMessage({
		title: '<?php echo $lang['metatitle'];?>', // 分享标题
		desc: '<?php echo $lang['metadesc'];?>', // 分享描述
		link: '<?php echo $thisurl;?>', // 分享链接
		imgUrl: '<?php echo !empty($lang['site_logo'])? SITE_URL.$lang['site_logo'] : $this->img('logo4.png');?>', // 分享图标
		success: function () { 
			// 用户确认分享后执行的回调函数
			_report('send_msg', 'st:ok');
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
	});
});

wx.onMenuShareTimeline({
      title: '<?php echo $lang['metatitle'];?>', // 分享标题
	  link: '<?php echo $thisurl;?>', // 分享链接
	  imgUrl: '<?php echo !empty($lang['site_logo'])? SITE_URL.$lang['site_logo'] : $this->img('logo4.png');?>', // 分享图标
      success: function () { 
			// 用户确认分享后执行的回调函数
			 _report('timeline', 'st:ok');
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
});
</script>

<?php $this->element('24/footer',array('lang'=>$lang)); ?>