<link href="<?php echo ADMIN_URL;?>tpl/2/css.css" rel="stylesheet" type="text/css" />

<style type="text/css">
body{ background:#FFF}
.shouquan{ border:1px solid #ededed;border-radius:5px; overflow:hidden}
.shouquan li{ float:left; width:33.3%; text-align:center}
.shouquan li div{ height:100px; width:100%; cursor:pointer}
.shouquan .weixin div{ background:url(<?php echo ADMIN_URL;?>tpl/2/images/wx2.png) center center no-repeat; }
.shouquan .weixin div.active{background:url(<?php echo ADMIN_URL;?>tpl/2/images/wx.png) center center no-repeat #F4F4F4;}
.shouquan .wangwang div{ background:url(<?php echo ADMIN_URL;?>tpl/2/images/ww2.png) center center no-repeat;border-right:1px solid #ededed; border-left:1px solid #ededed}
.shouquan .wangwang div.active{background:url(<?php echo ADMIN_URL;?>tpl/2/images/ww.png) center center no-repeat #F4F4F4;}
.shouquan .shouji div{ background:url(<?php echo ADMIN_URL;?>tpl/2/images/sj2.png) center center no-repeat}
.shouquan .shouji div.active{background:url(<?php echo ADMIN_URL;?>tpl/2/images/sj.png) center center no-repeat #F4F4F4;}

</style>
<a name="tops"></a>
<div id="ui-header">
<div class="fixed">
<a class="ui-title" id="popmenu" style="color:#FFFFFF">防伪查询</a>
<a class="ui-btn-left_pre" href="javascript:history.go(-1)"></a>
<a class="ui-btn-right_home" href="<?php echo ADMIN_URL.'site.php';?>"></a>
</div>
</div>
<div style="height:46px; clear:both"></div>
<div class="page-bizinfo">
	
	<div style="margin-bottom:20px; position:relative">
		<img src="<?php echo ADMIN_URL;?>tpl/2/images/fw1.jpg" style="width:100%" />
		<img src="<?php echo ADMIN_URL;?>tpl/2/images/fwss.jpg" style="width:120px;position:absolute; right:0px; bottom:20px; z-index:99; cursor:pointer" id="scanQRCode1" />
	</div>
	
	<div style="margin-bottom:20px; position:relative">
		<img src="<?php echo ADMIN_URL;?>tpl/2/images/fw2.jpg" style="width:100%" />
		<a href="tel:13764567708"><img src="<?php echo ADMIN_URL;?>tpl/2/images/fsbd.jpg" style="width:120px;position:absolute; right:0px; bottom:20px; z-index:99; cursor:pointer" /></a>
	</div>
	<div>
		<img src="<?php echo ADMIN_URL;?>tpl/2/images/fw3.jpg" style="width:100%" />
	</div>
</div>
<script type="text/javascript">
$('.shouquan li div').click(function(){
	$(this).parent().parent().find('.active').removeClass();
	$(this).addClass('active');
	
	t = $(this).attr('id');
	if(t=='1'){
		$('input[name="textfield"]').attr('placeholder','请输入微信号查询');
		$('input[name="types"]').val('1');
	}else if(t=='2'){
		$('input[name="textfield"]').attr('placeholder','请输入旺旺号查询');
		$('input[name="types"]').val('2');
	}else{
		$('input[name="textfield"]').attr('placeholder','请输入手机号查询');
		$('input[name="types"]').val('3');
	}
});
function ajax_check_shouquan(){
	ty = $('input[name="types"]').val();
	key =$('input[name="textfield"]').val();
	$.post(SITE_URL+'user.php',{action:'login',username:username,password:password},function(data){ 
}
</script>
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
    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline','onMenuShareQQ','scanQRCode'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});

document.querySelector('#scanQRCode1').onclick = function () {
	wx.scanQRCode({
		needResult: 0, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
		scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
		success: function (res) {
		var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
	}
	});
}
</script>