
<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/openshop.css" media="all" />

<div id="home">
	<div id="header">
		<div class="logo" style="height:28px; padding-top:10px; background:url(<?php echo $this->img('xy.png');?>) 10px 8px no-repeat"><span onclick="history.go(-1);">&nbsp;</span></div>
		<div class="shoptitle"><span><?php echo $fxrank!='1'? '我要开店' : '编辑店铺';?></span></div>
		<div class="logoright">
			<div style="background:none; display:none">
			<a style="padding:5px; cursor:pointer;border-radius:5px; background:#890302; color:#fff; font-size:12px; height:18px; line-height:18px; margin-top:10px;filter:alpha(opacity=80); -moz-opacity:0.8; -khtml-opacity:0.8;opacity:0.8;" onclick="update_user_info(12)" href="javascript:;"><?php echo $fxrank!='1'? '立即开通' : '确认修改';?></a>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
.pw,.pwt{
height:28px; line-height:normal;
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{ width:200px;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
#main img{ max-width:100%;}
</style>
<div id="main" style="padding:5px; min-height:300px">
	<div style="padding-bottom:20px; font-size:0px">
		<?php echo isset($rt['about']['content']) ? $rt['about']['content'] : "";?>
	</div>
	<div class="container">
    <div class="floor1">
        <p class="blod">购买以下任一件，即可免费开店。</p>
        <div class="goods">
		<?php if(!empty($rt['glist']))foreach($rt['glist'] as $row){?>
            <div class="tu">
                <div class="pic" style="height:160px; overflow:hidden">
                   <a href="<?php echo ADMIN_URL.'product.php?id='.$row['goods_id'];?>"><img src="<?php echo SITE_URL.$row['goods_thumb'];?>" style="max-width:100%" /></a>
                </div>
                <div class="text">
                    <div class="title"><a href="<?php echo ADMIN_URL.'product.php?id='.$row['goods_id'];?>"><?php echo $row['goods_name'];?></a></div>
                    <div class="feft">
                      <div class="xian">￥<?php echo $row['pifa_price'];?></div>
                      <div class="yuan">￥<?php echo $row['shop_price'];?></div>
                    </div>
                    <div class="but"><a href="<?php echo ADMIN_URL.'product.php?id='.$row['goods_id'];?>">购买并开店</a></div>
                </div>
            </div>
		<?php } ?>
           
            <div style="clear:both"></div>
        </div>
    </div>
    
    <div class="floor1">
      <p class="blod">填写资料，成功开店</p>
      	 <form name="USERINFO" id="USERINFO" action="" method="post">
			 <table width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:34px; padding:10px; font-size:14px;">
			 <tr>
			 <td width="20%" align="right">&nbsp;</td>
			 <td>店名例如：<?php echo $GLOBALS['LANG']['site_name'];?>专卖店</td>
			 </tr>
			 <tr>
				<td width="20%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b>店名：</td>
				<td width="80%" align="left" style="padding-bottom:2px;">
				<span style="line-height:28px; height:28px; display:block; width:60px; float:left; border-bottom:1px solid #ccc"><?php echo $GLOBALS['LANG']['site_name'];?></span><input placeholder="店铺名称限制在6个字内" type="text" value="<?php echo isset($rt['userinfo']['question'])&&!empty($rt['userinfo']['question']) ? $rt['userinfo']['question'] : "";?>" name="question"  class="pw" style="width:120px; float:left"/><span style="line-height:28px; height:28px; display:block; width:45px; float:left; border-bottom:1px solid #ccc; padding-left:5px">专卖店</span>
				</td>
			</tr>
			<tr>
			<td width="20%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b>手机：</td>
			<td width="80%" align="left" style="padding-bottom:2px;">
			<input placeholder="手机号码"  type="text" value="<?php echo isset($rt['userinfo']['mobile_phone'])&&!empty($rt['userinfo']['mobile_phone']) ? $rt['userinfo']['mobile_phone'] : "";?>" name="mobile_phone"  class="pw"/></td>
			</tr>
			<tr>
			<td width="20%" align="right" style="padding-bottom:2px;"><b class="cr2">*</b>姓名：</td>
			<td width="80%" align="left" style="padding-bottom:2px;">
			<input placeholder="姓名"  type="text" value="<?php echo isset($rt['userinfo']['answer'])&&!empty($rt['userinfo']['answer']) ? $rt['userinfo']['answer'] : "";?>" name="answer"  class="pw"/></td>
		  </tr>
		  <tr>
			<td align="center" colspan="2">
				<a style="padding:10px; cursor:pointer;border-radius:5px; background:#890302; color:#fff; font-size:12px; height:18px; line-height:18px; margin-top:10px; width:70%; display:block; font-size:14px" onclick="update_user_info(12)" href="javascript:;"><?php echo $fxrank!='1'? '立即开通' : '确认修改';?></a>
			</td>
		  </tr>
		   <tr>
			<td align="center" colspan="2">
			<span class="returnmes" style="color:#FF0000"></span>
			</td>
		  </tr>
		</table>
		</form>
    </div>
</div>


</div>
