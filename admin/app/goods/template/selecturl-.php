<style type="text/css">
.contentbox li{ width:25%; text-align:left; line-height:26px; float:left}
</style>
<div class="contentbox" style="height:360px; overflow:hidden; overflow-y:auto">
   <table cellspacing="1" cellpadding="5" width="100%">
	 <tr>
		<th align="left">点击选择</th>
	</tr>
	</table>
	<ul style="padding:0px 10px 0px 10px">
		<?php if(!empty($tjgoods)){foreach($tjgoods as $row){?>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/in.php?id='.$row['id'];?>')"><?php echo '<font color=blue>[单品推荐]</font>'.$row['goods_name'];?></a></li>
		<?php } ?>
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
		<?php } ?>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/';?>')">商城首页</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php';?>')">会员中心</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/mycart.php';?>')">购物车</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php?act=orderlist';?>')">我的订单</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php?act=mygift';?>')">我的礼包</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php?act=mycoll';?>')">我的收藏</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/daili.php?act=myusertype';?>')">我的下线</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php?act=dailicenter';?>')">我的分销</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php?act=apply';?>')">申请分销</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php?act=dailicenter';?>')">我的佣金</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/daili.php?act=postmoney';?>')">我要提款</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php?act=shoplist';?>')">附近的店</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/exchange.php';?>')">积分商品</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/catalog.php?keyword=is_hot';?>')">热销商品</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/catalog.php?keyword=is_new';?>')">新品大促</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/catalog.php?keyword=is_best';?>')">推荐商品</a></li>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/user.php?act=myerweima';?>')">我的二维码</a></li>
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
		
		<?php if(!empty($artlist)){foreach($artlist as $row){?>
		<li><a href="javascript:;" onclick="seturl('<?php echo !empty($row['art_url']) ? $row['art_url'] : SITE_URL.'m/art.php?id='.$row['article_id'];?>')"><?php echo $row['article_title'];?></a></li>
		<?php } ?>
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
		<?php } ?>
                
		<?php if(!empty($catelist)){foreach($catelist as $row){?>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/catalog.php?cid='.$row['cat_id'];?>')"><?php echo $row['cat_name'];?></a></li>
		<?php } ?>
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
		<?php } ?>
                
		<?php if(!empty($catelist2)){ foreach($catelist2 as $row){?>
		<li><a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/new.php?cid='.$row['cat_id'];?>')"><?php echo $row['cat_name'];?></a></li>
		<?php } ?>
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
		<?php } ?>
		
		<?php foreach($lists as $row){?>
		<li style="height:70px; width:50%;">
		<img src="<?php echo SITE_URL.$row['goods_thumb'];?>" width="60" height="60" style="float:left; padding:1px; border:1px solid #ededed; margin-right:4px;" />
		<a href="javascript:;" onclick="seturl('<?php echo SITE_URL.'m/product.php?id='.$row['goods_id'];?>')" ><?php echo $row['goods_name'];?></a>
		</li>
		<?php } ?>
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
                
		<?php if(!empty($artlist2)){foreach($artlist2 as $row){?>
		<li><a href="javascript:;" onclick="seturl('<?php echo !empty($row['art_url']) ? $row['art_url'] : SITE_URL.'m/new.php?id='.$row['article_id'];?>')"><?php echo $row['article_title'];?></a></li>
		<?php } ?>
		<div style="clear:both; border-bottom:2px solid #ccc; margin-bottom:10px"></div>
		<?php } ?>
	</ul>
	
	<div style="clear:both"></div>
	<?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">
function seturl(url){
	window.parent.setrun(url);
	alert("已选择:"+url);
}
</script>
	