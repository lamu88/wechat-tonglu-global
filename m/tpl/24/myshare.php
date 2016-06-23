
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<style type="text/css">
body{ background:#FFF !important;}
#main li:hover{ background:#ededed}
</style>
<div id="main" style="min-height:300px;margin-bottom:20px;">
	<ul class="v12_ul">
	<?php if(!empty($rt['lists']))foreach($rt['lists'] as $k=>$row){?>
		<li style="padding:5px; border-bottom:1px solid #F0F0F0; position:relative">
			<a href="javascript:void(0)" style="display:block">
			<div style="position:relative; width:20%;float:left;"><img src="<?php echo !empty($row['headimgurl']) ? $row['headimgurl'] : $this->img('noavatar_big.jpg');?>" width="100%" style="margin-right:5px; padding:1px; border:1px solid #fafafa" />
			<?php if($row['is_subscribe']=='1'){?><img src="<?php echo $this->img('dui2.png');?>" style="position:absolute; bottom:5px; right:-2px; z-index:7" /><?php } ?>
			</div>
			<div style="float:right; width:78%;">
			<p style="line-height:23px"><?php echo $row['nickname'];?>&nbsp;&nbsp;<?php echo !empty($row['subscribe_time']) ? date('Y-m-d H:i:s',$row['subscribe_time']) : date('Y-m-d H:i:s',$row['reg_time']);?></p>
			<p style="line-height:23px">积分&nbsp;<font color="#FF0000"><?php echo $row['points_ucount'];?></font>&nbsp;|&nbsp;邀请&nbsp;<font color="#FF0000"><?php echo $row['share_ucount'];?></font></p>
			</div>
			<div class="clear"></div>
			</a>
			<span style="border-radius:50%; height:22px; line-height:22px; width:22px; float:right; display:block;background:#B70000; text-align:center; font-size:12px; font-weight:bold; color:#FFF; cursor:pointer; position:absolute;right:5px; top:17px; z-index:99" id="62"><i style="font-style:normal"><?php echo ++$k;?></i></span>
		</li>
	<?php }else{
	?>
	<li style="padding-top:60px; padding-bottom:60px; font-size:16px; text-align:center">
	目前数据为空的
	</li>
	<?php
	} ?>
	<div class="clear"></div>
	</ul>
	<div class="clear10"></div>
	<div class="loadsss" style="text-align:center">
	
	</div>
</div>
<script type="text/javascript">
var hh = 0; //第一次时的高度
var isrun = true;
var tops = 0;
function page_init(){
	hh = $('.v12_ul').height();
	tops = parseInt(hh);
}
//获取滚动条当前的位置 
function getScrollTop() { 
var scrollTop = 0; 
if (document.documentElement && document.documentElement.scrollTop) { 
scrollTop = document.documentElement.scrollTop; 
} 
else if (document.body) { 
scrollTop = document.body.scrollTop; 
} 
return scrollTop; 
} 

//获取当前可是范围的高度 
function getClientHeight() { 
var clientHeight = 0; 
if (document.body.clientHeight && document.documentElement.clientHeight) { 
clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight); 
} 
else { 
clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight); 
} 
return clientHeight; 
} 

//获取文档完整的高度 
function getScrollHeight() { 
return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight); 
}

window.onscroll = function () {

if (getScrollTop() + getClientHeight() == getScrollHeight()) { 
	//tops = getScrollHeight();
	$('.loadsss').html('<img src="<?php echo $this->img('loadings.gif');?>" style="width:16px!important; height:16px;" />加载中');
	setTimeout(function(){
		isrun = true;
	},15000);
	if(isrun==true){
		isrun = false;
		$.post('<?php echo ADMIN_URL;?>user.php',{action:'ajax_myshate_page',tops:tops,hh:hh},function(data){ 
			$('.loadsss').html("");
			if(data!=""){
				tops += hh;
				$('.v12_ul').append(data);
				isrun = true;
			}else{
				$('.loadsss').html("加载完毕");
			}
		})
	}
} 
}

$(document).ready(function(){
	page_init();
});
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
