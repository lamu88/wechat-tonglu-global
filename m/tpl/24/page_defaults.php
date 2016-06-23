
<style type="text/css">
.indexcon{ text-align:center}
.indexcon img{ max-width:100%;}
.footffont{ line-height:24px; position:relative}
.footffontbox{ position:absolute; left:0px; right:0px; top:0px; z-index:9; text-align:center; line-height:24px;}
.gototop{height:32px; line-height:32px; position:fixed; bottom:65px; left:0px; right:0px; padding-right:5px; padding-left:5px; display:block}
</style>
<div id="main">
	<div class="indexcon">
			<?php
			echo $rt['tj']['goods_desc'];
			?>
	</div>	
</div>
<div class="footffont">
	<div class="footffontbox">
	<?php echo $lang['copyright'];?>
	</div>
</div>
<div class="show_zhuan" style=" display:none;width:100%; height:100%; position:fixed; top:0px; right:0px; z-index:9999999;filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9; background:url(<?php echo $this->img('gz/121.png');?>) right top no-repeat #000;background-size:100% auto;" onclick="$(this).hide();"></div>
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
function show_zhuan(){
	$('.show_zhuan').show();
	$('body,html').animate({scrollTop:0},500);
}


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
<?php 
 if($rt['uinfo']['is_subscribe']=='0'){
	$urls = $lang['wxguanzhuurl'];
	$desc = '立即关注，将获得更多的折扣！';
	$but = '进入关注';
	$sub = '';
	$true = "false";
 }elseif($rt['uinfo']['user_rank']=='1'){
	$urls = ADMIN_URL.'product.php?id='.$rt['tj']['goods_id'];
	$desc = '你还不是东家,请购买成为东家！';
	$but = '立即购买';
	$sub = '';
	$true = "false";
 }else{
	$urls = 'javascript:;';
	$desc = '你已经是东家,推荐赚佣金吧！';
	$but = '我要分享';
	$sub = ' onclick="show_zhuan();"';
	$true = "true";
 }
?>
<?php
if($true=="true"){
?>
<div style="position:fixed; bottom:48px; right:5px; z-index:99; width:100px">
<input type="button" id="cart" class="addcar" value="我要购买" style="cursor:pointer; width:100%; background:#ca0102" onclick="return addToCart('<?php echo $rt['tj']['goods_id'];?>','jumpshopping')">
</div>
<?php
}
?>
<div style=" position:relative;height:44px; line-height:44px; background:#2d1d44;  position:fixed; bottom:0px; left:0px; width:100%; z-index:9999">
	<img src="<?php echo $rt['tjr']['headimgurl'];?>" height="40" style="margin:2px 8px 2px 10px; float:left" />
	<p style=" padding-top:2px; padding-bottom:2px; line-height:20px; color:#FFF; font-weight:bold">
	来自好友<font color="#00761d"><?php echo $rt['tjr']['nickname'];?></font>的推荐<br/><?php echo $desc;?>
	</p>
	<a href="<?php echo $urls;?>" style=" position:absolute; right:10px; top:10px; z-index:99; cursor:pointer; height:25px; line-height:25px; width:60px; border:1px solid #FFF;border-radius: 5px; text-align:center; color:#FFF"<?php echo $sub;?>><?php echo $but;?></a>
</div>