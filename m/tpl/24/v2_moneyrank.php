
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="height:50px; border:1px solid #d8d8d8">
	<tr>
		<td align="center" width="50%">
		<p><font color="#FF0000">￥<?php echo empty($rt['userinfo']['money_ucount']) ? '0' : $rt['userinfo']['money_ucount'];?></font></p>
		<p style="font-size:14px; padding-top:2px">我的资金</p>
		</td>
		<td align="center" style="border-left:1px solid #d8d8d8">
		<p><font color="#FF0000"><?php echo $rt['userinfo']['thisrank'];?></font></p>
		<p style="font-size:14px; padding-top:2px">我的排名</p>
		</td>
	</tr>
</table>
<style type="text/css">
#main li{ background:#FFF}
#main li:hover{ background:#ededed}
</style>
<div id="main" style=" min-height:200px;margin-bottom:20px;">
<ul class="v12_ul">
<?php if(!empty($rt['ulist']))foreach($rt['ulist'] as $k=>$row){
?>
	<li style="padding:5px; border-bottom:1px solid #d8d8d8; position:relative">
		<a href="javascript:void(0)" style="display:block">
		<div style="position:relative; width:20%;float:left;"><img src="<?php echo !empty($row['headimgurl']) ? $row['headimgurl'] : $this->img('noavatar_big.jpg');?>" width="100%" style="margin-right:5px; padding:1px; border:1px solid #fafafa" />
		<?php if($row['is_subscribe']=='1'){?><img src="<?php echo $this->img('dui2.png');?>" style="position:absolute; bottom:5px; right:-2px; z-index:7" /><?php } ?>
		</div>
		<div style="float:right; width:78%;">
		<p style="line-height:23px"><?php echo $row['nickname'];?>&nbsp;&nbsp;<?php echo !empty($row['subscribe_time']) ? date('Y-m-d H:i:s',$row['subscribe_time']) : date('Y-m-d H:i:s',$row['reg_time']);?></p>
		<p style="line-height:23px">资金&nbsp;<font color="#FF0000">￥<?php echo $row['money_ucount'];?></font>&nbsp;|&nbsp;邀请&nbsp;<font color="#FF0000"><?php echo $row['share_ucount'];?></font></p>
		</div>
		<div class="clear"></div>
		</a>
		<?php if($k<3){
		$s = $k==0 ? 'mmexport1417022423647.png' : ($k==1?'mmexport1417022426972.png':'mmexport1417022429974.png')
		?>
		<img src="<?php echo $this->img('icon/'.$s);?>" height="40" style=" position:absolute; right:5px; top:8px; z-index:99" />
		<?php } else{?>
		<span style="border-radius:50%; padding:3px;float:right; display:block;background:#B70000; text-align:center; font-size:12px; font-weight:bold; color:#FFF; cursor:pointer; position:absolute;right:10px; top:17px; z-index:99" id="62"><i style="font-style:normal"><?php echo ++$k;?></i></span>
		<?php } ?>
	</li>
<?php } ?>
</ul>
	<div class="clear10"></div>
	<div class="loadsss" style="text-align:center">
	
	</div>
</div>
<script type="text/javascript">
var hh = 0;
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
	tops = getScrollHeight();
	$('.loadsss').html('<img src="<?php echo $this->img('loadings.gif');?>" style="width:16px!important; height:16px;" />加载中');
	setTimeout(function(){
		isrun = true;
	},15000);
	if(isrun==true){
		isrun = false;
		$.post('<?php echo ADMIN_URL;?>daili.php',{action:'ajax_moneyrank_page',tops:tops,hh:hh},function(data){ 
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