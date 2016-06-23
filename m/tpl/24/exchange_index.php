
<?php $this->element('guanzhu',array('shareinfo'=>$lang['shareinfo']));?>
<div id="home">
	<div id="header">
		<div class="logo" style="height:28px; padding-top:10px; background:url(<?php echo $this->img('xy.png');?>) 10px 8px no-repeat"><span onclick=" history.go(-1);">&nbsp;</span></div>
		<div class="shoptitle"><span><?php echo NAVNAME;?></span></div>
		<div class="logoright">
			<p style="height:46px; line-height:46px;">
			<a href="javascript:void(0)" onclick="$('.show_zhuan').show();"><span>推荐返佣</span></a>
			</p>
		</div>
	</div>
</div>	

<!--顶栏焦点图--> 
<div class="flexslider" style="margin-bottom:0px;">
	 <ul class="slides">		 
		<li><img src="<?php echo SITE_URL.$rt['goodsinfo']['goods_img'];?>" width="100%" alt="<?php echo $row['goods_name'];?>"/></li>
		<li><img src="<?php echo SITE_URL.$rt['goodsinfo']['goods_img'];?>" width="100%" alt="<?php echo $row['goods_name'];?>"/></li>										
	  </ul>
</div>
<style type="text/css">
#main .goods_desc img{ max-width:100%;}
</style>		
<div id="main">
	<div class="mainhead" style="margin:5px; margin-top:0px; border:1px solid #ededed;border-radius:5px; background:#FFF">
        <form id="ECS_FORMBUY" name="ECS_FORMBUY" method="post" action="">
		<div class="shopinfol" style="font-size:14px">
		<h1><?php echo $rt['goodsinfo']['goods_name'];?></h1>
		<p class="scjprice"><b>市场价：</b><del>￥<?php echo str_replace('.00','',$rt['goodsinfo']['shop_price']);?></del></p>
		<p class="scjprice"><b>折扣价：</b><span>￥<?php echo str_replace('.00','',$rt['goodsinfo']['pifa_price']);?></span></p>
		<p class="vippfont"><b>所需积分：</b><span class="price"><?php echo $rt['goodsinfo']['need_jifen'];?></span></p>
<!--		<p style="padding-top:3px;"><b>购买数量：</b> <span class="gjian">-</span><input class="nb" type="text" name="number" size="5" value="1" /><span class="gjia">+</span> 件 <font color="#FF0000">(<u>库存 <?php echo $rt['goodsinfo']['goods_number']; ?> 件</u>)</font></p>
-->		    <?php
			  if(!empty($rt['spec'])){
					foreach($rt['spec'] as $row){
							if(empty($row)||!is_array($row) || $row[0]['is_show_cart']==0) continue;
							$rl[$row[0]['attr_keys']] = $row[0]['attr_name'];
							$attr[$row[0]['attr_keys']] = $row[0]['attr_is_select'];
					}
			   }
			?>
                  <div class="buyclass">
		  <?php
                    if(!empty($rt['spec'])){
                    foreach($rt['spec'] as $row){
                    if(empty($row)||!is_array($row) || $row[0]['is_show_cart']==0) continue;

		  ?>		 
                    <?php if(!empty($row[0]['attr_name'])){?>
		    <p class="spec_p"><span><?php  echo $row[0]['attr_name'].":";?></span>
                      <?php
                      if($row[0]['attr_is_select']==3){ //复选
                              foreach($row as $rows){
                                    $st .= '<label><input type="checkbox" name="'.$row[0]['attr_keys'].'" id="quanxuan" value="'.$rows['attr_value'].'" />&nbsp;'.$rows['attr_value']."</label>\n";
                              }
                              echo $st .='<label class="quxuanall" id="ALL" style="border:1px solid #ADADAD; background-color:#E1E5E6; padding-left:3px; height:18px; line-height:18px;padding:2px;">全选</label>';
                      }else{
                              echo '<input type="hidden" name="'.$row[0]['attr_keys'].'" value="" />'."\n";
                              foreach($row as $rows){
                                            if(!empty($rows['attr_addi']) && @is_file(SYS_PATH.$rows['attr_addi'])){//如果是图片
                                                    echo '<a lang="'.trim($rows['attr_addi']).'" href="javascript:;" name="'.$row[0]['attr_keys'].'" id="'.trim($rows['attr_value']).'"><img src="'.(empty($rows['attr_addi']) ? 'theme/images/grey.png':$rows['attr_addi']).'" alt="'.$rows['attr_value'].'" title="'.$rows['attr_value'].'" width="40" height="50" /></a>';
                                            }else{
                                                    echo '<a lang="'.trim($rows['attr_addi']).'" href="javascript:;" name="'.$row[0]['attr_keys'].'" id="'.trim($rows['attr_value']).'">'.$rows['attr_value'].'</a>';
                                            }
                              }
                      } //end if
                    ?>
				
		  </p><?php } ?>
                  <div class="clear"></div>
		 <?php } // end foreach
		  } //end if?>
		</div>
		<p style="height:32px; line-height:32px; padding-top:10px;">
		<!--<input type="button" class="pushf" value="加入收藏" style="cursor:pointer;" onclick="return addToColl('<?php echo $rt['goodsinfo']['goods_id'];?>')">-->
			<?php if($rt['goodsinfo']['goods_number'] > 0){ ?>
            <input type="button" id="cart" class="addcar" value="立即兑换" style="cursor:pointer;" onclick="return addToCartJifen('<?php echo $rt['goodsinfo']['goods_id'];?>')">
			<?php }else{?>
			<input type="button" id="cart" class="addcar" value="已兑换完了" style="cursor:pointer;">
			<?php } ?>
		</p>
		</div>
            </form>
	</div>
	<div class="mainbottombg">
	<span>产品详情</span>
	</div>
	<div style="padding:10px;" class="goods_desc">
	<?php echo $rt['goodsinfo']['goods_desc'];?>
	</div>
</div>
<div class="show_zhuan" style=" display:none;width:100%; height:100%; position:fixed; top:0px; right:0px; z-index:9999999;filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9; background:url(<?php echo $this->img('gz/121.png');?>) right top no-repeat #000;background-size:100% auto;" onclick="$(this).hide();"></div>	
<script type="text/javascript">
$('input[name="number"]').change(function(){
	vall = $(this).val();
	if(!(vall>0)){
		$(this).val('1');
	}
});

$('.spec_p a').click(function(){
	na = $(this).attr('name');
	vl = $(this).attr('id');
	$('input[name="'+na+'"]').val(vl);
	
	$(this).parent().find('a').each(function(i){
	   this.style.border='1px solid #cbcbcb';
	});
	
	$(this).css('border','1px solid #FF0000');
	
	return false;
});

$('#main .gjia').click(function(){
	var tnum = $(this).parent().find('input').val();
	$(this).parent().find('input').val(parseInt(tnum)+1);
});
$('#main .gjian').click(function(){
	var tnum = $(this).parent().find('input').val();
	tnum = parseInt(tnum);
	if(tnum>1){
		$(this).parent().find('input').val(tnum-1);
	}
});		
function checkcartattr(){
	<?php 
	if(!empty($rl)){
		foreach($rl as $k=>$v){?>
		a<?php echo $k;?> = $('.buyclass input[name="<?php echo $k;?>"]<?php echo $attr[$k]==3 ? ':checked' : "";?>').val();
		if(a<?php echo $k;?> ==""||typeof(a<?php echo $k;?>)=='undefined'){
		  alert("必须选择<?php echo $v;?>");
		  return false;
		}
	<?php } }?>
	return true;
}


var dt = '<?php echo $rt['goodsinfo']['is_promote']&&$rt['goodsinfo']['promote_start_date']<mktime() ? ($rt['goodsinfo']['promote_end_date']-mktime()) : ($rt['goodsinfo']['promote_end_date']-$rt['goodsinfo']['promote_start_date']);?>';
var st = new showTime('2', dt);  
st.desc = "促销结束";
st.preg = "倒计时	{a}天	{b}:{c}:{d}";
st.setid = "lefttime_";
st.setTimeShow(); 
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>