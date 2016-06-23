
<style type="text/css">
.indexcon{ text-align:center}
.indexcon img{ max-width:100%;}
.footffont{ line-height:24px; }
.footffontbox{  text-align:center; line-height:24px;}
.gototop{height:32px; line-height:32px; position:fixed; bottom:65px; left:0px; right:0px; padding-right:5px; padding-left:5px; display:block}
.pw2{background-color: #fff;}
.pw{
border: 1px solid #ddd;
border-radius: 5px;
padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
#main .indexcon img{ float:left; max-width:100%; margin:0px auto}
</style>
<div id="main">
	<div class="indexcon">
			<?php
			echo $rt['pinfo']['content'];
			?>
			<div style="clear:both"></div>
	</div>	
</div>
<div class="footffont">
	<div class="footffontbox">
		<form id="ssumit" name="ssumit" method="post" action="<?php echo ADMIN_URL.'bm.php?act=confirmpay';?>">
			<table cellpadding="3" cellspacing="5" border="0" width="100%">
			<tr>
				<td width="100%" align="center" style="text-align:center">
				<input placeholder="输入您的大名" type="text" name="uname" style="width:85%; height:44px; line-height:normal; padding-left:25px; background:url(<?php echo $this->img('u.png');?>) 5px center no-repeat #FFF; font-size:14px;" class="pw pw2"/>
				</td>
			</tr>
			<tr>
				<td width="100%" align="center" style="text-align:center">
				<input placeholder="输入您正确的手机号码" type="text" name="upne" style="width:85%; height:44px; line-height:normal; padding-left:25px;background:url(<?php echo $this->img('t.png');?>) 7px center no-repeat #FFF;font-size:14px;" class="pw pw2"/>
				</td>
			</tr>
			<tr>
				<td align="center" style="color:#FF0000; font-size:14px;">
				<span class="results"></span>
				</td>
			</tr>
			<tr>
				<td align="center" width="100%">
				<a href="javascript:;" onclick="return check_senddata()" style="background:#00c800; color:#FFF; font-size:14px; text-align:center; display:block; width:100%; padding-bottom:7px; padding-top:7px; height:24px; font-weight:bold;border-radius:5px;"><img src="<?php echo $this->img('24/images/wxioc.png');?>" align="absmiddle" style="height:24px; margin-right:10px" />立即报名(微信支付<?php echo $rt['pinfo']['price'];?>)元</a>
				</td>
			</tr>
			</table> 
			 <input type="hidden" name="ids" value="<?php echo $rt['pinfo']['id'];?>" />
			 <input type="hidden" name="price" value="<?php echo $rt['pinfo']['price'];?>" />
		</form>
	</div>
	<div style="clear:both"></div>
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
function check_senddata(){
	uuname = $('input[name="uname"]').val();
	if(uuname=="" || typeof(uuname)=='undefinde'){
		$('td .results').html("输入您的大名");
		return false;
	}
	uupne = $('input[name="upne"]').val();
	if(uupne=="" || typeof(uupne)=='undefinde'){
		$('td .results').html("输入您正确的手机号码");
		return false;
	}
	$('#ssumit').submit();
	return true;
}

function show_zhuan(){
	$('.show_zhuan').show();
	$('body,html').animate({scrollTop:0},500);
}

function ajax_submitbuy(){
	$('body,html').animate({scrollTop:3000},500);
	check_senddata();
}

  function _report(a,c){
		$.post('<?php ADMIN_URL;?>product.php',{action:'ajax_share',type:a,msg:c,thisurl:'<?php echo Import::basic()->thisurl();?>',imgurl:'<?php echo !empty($rt['pinfo']['img'])? SITE_URL.$rt['pinfo']['img'] : $this->img('logo4.png');?>',title:'<?php echo $title;?>'},function(data){
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
		title: '<?php echo $rt['pinfo']['title'];?>', // 分享标题
		desc: '<?php echo $rt['pinfo']['title'];?>', // 分享描述
		link: '<?php echo $thisurl;?>', // 分享链接
		imgUrl: '<?php echo !empty($rt['pinfo']['img'])? SITE_URL.$rt['pinfo']['img'] : $this->img('logo4.png');?>', // 分享图标
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
      title: '<?php echo $rt['pinfo']['title'];?>', // 分享标题
	  link: '<?php echo $thisurl;?>', // 分享链接
	  imgUrl: '<?php echo !empty($rt['pinfo']['img'])? SITE_URL.$rt['pinfo']['img'] : $this->img('logo4.png');?>', // 分享图标
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
	$desc = '立即关注报名抢占地盘吧！';
	$but = '进入关注';
	$sub = '';
	$true = "false";
 }elseif($rt['uinfo']['user_rank']=='1'){
	$urls = 'javascript:;';
	$desc = '您还不是会员，请立即报名！';
	$but = '我要报名';
	$sub = ' onclick="ajax_submitbuy();"';
	$true = "false";
 }else{
	$urls = 'javascript:;';
	if(empty($rt['uinfo'])){
	$desc = '请先登录！';
	}else{
	$desc = '你已经是会员！';
	}
	$but = '我要分享';
	$sub = ' onclick="show_zhuan();"';
	$true = "true";
 }
?>
<div style="height:50px; clear:both"></div>
<div style=" position:relative;height:44px; line-height:44px; background:#222;  position:fixed; bottom:0px; left:0px; width:100%; z-index:9999">
	<img src="<?php echo $rt['tjr']['headimgurl'];?>" height="40" style="margin:2px 8px 2px 10px; float:left;border-radius:50%" />
	<p style=" padding-top:2px; padding-bottom:2px; line-height:20px; color:#FFF; font-weight:bold">
	来自好友<font color="#BB8638"><?php echo $rt['tjr']['nickname'];?></font>的推荐<br/><?php echo $desc;?>
	</p>
	<a href="<?php echo $urls;?>" style=" position:absolute; right:10px; top:10px; z-index:99; cursor:pointer; height:25px; line-height:25px; width:60px;border-radius: 5px; text-align:center; color:#FFF; background:#BE0000"<?php echo $sub;?>><?php echo $but;?></a>
</div>