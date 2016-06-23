<link href="<?php echo ADMIN_URL;?>tpl/2/css.css" rel="stylesheet" type="text/css" />

<style type="text/css">
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
	<ul class="shouquan">
		<li class="weixin">
		<div class="active" id="1">
		
		</div>
		</li>
		<li class="wangwang">
		<div id="2">
		
		</div>
		</li>
		<li class="shouji">
		<div id="3">
		
		</div>
		</li>
		<div class="clear"></div>
	</ul>
	<div style="margin-top:30PX;">
		<div style="width:78%; float:left">
		<input type="hidden" name="types" value="1" />
		<input placeholder="请输入微信号查询" type="text" name="textfield" style="border:1px solid #EDEDED; line-height:35px; height:35px;border-radius:5px; width:98%; font-size:16px; padding-left:3px" />
	  </div>
		<div style="width:20%; float:right">
		<a href="javascript:;" style="line-height:35px; height:35px;border-radius:5px; background:#FC4849; width:100%; display:block; font-size:20px; text-align:center; color:#FFF; font-weight:bold" onclick="ajax_check_shouquan();">Go!</a>
		</div>
		<div class="clear"></div>
	</div>
	
	<div style="height:auto; line-height:24px; margin-top:10px; color:#FF3300; font-size:14px; padding-bottom:20px" class="messagetxt">
	
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
	if(key!=""){
		$.post('<?php echo ADMIN_URL;?>site.php',{action:'ajax_check_shouquan',types:ty,keys:key},function(data){
			$('.messagetxt').html(data);
		});
	}else{
		$('.messagetxt').html("请输入关键字搜索");
	}
}
</script>
