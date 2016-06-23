<style type="text/css">
.goodslist li{ float:left; width:200px; height:260px; overflow:hidden; margin:8px; border:1px dotted #ccc; position:relative}
.goodslist li .imgbox{ width:200px; height:200px; overflow:hidden; text-align:center}
.goodslist li .fname{ height:40px; line-height:20px; overflow:hidden; padding:0px; margin:0px}
.goodslist li .price{ height:20px; line-height:20px; margin:0px; padding:0px}
.goodslist li .price b{ color:#FE0000; margin-right:5px;}
.goodslist li .price del{ color:#999999}
</style>
<div class="contentbox" style="overflow-y:auto; overflow-x:hidden; width:890px; height:600px">
	<p style="font-size:16px; height:30px; line-height:30px; margin:0px; padding:0px;">添加产品到当前分类<font color="#FF0000">【<?php echo $rts['bigname'].' - '.$rts['subname'];?>】</font>：<img src="<?php echo $this->img('+.jpg');?>" width="25" onclick="ajax_open_addtopgoods(<?php echo $_GET['cid'];?>)" align="absmiddle" /></p>
	<ul class="goodslist">
	<?php if(!empty($rt))foreach($rt as $rows){?>
	<?php
		$name = !empty($rows['gname']) ? $rows['gname'] : $rows['goods_name'];
		$url = !empty($rows['url']) ? $rows['url'] : SITE_URL.'m/product.php?id='.$rows['goods_id'];
		$img = !empty($rows['img']) ? SITE_URL.$rows['img'] : (!(empty($rows['goods_thumb'])) ? SITE_URL.$rows['goods_thumb'] : $this->img('no_picture.gif'));
	?>
		<li>
			<div class="imgbox">
			<a target="_blank" href="<?php echo $url;?>"><img src="<?php echo $img;?>" alt="<?php echo $name;?>" width="200" onload="loadimg(this,200,200)" title="<?php echo $name;?>" /></a>
			</div>
			<p class="fname"><a target="_blank" href="<?php echo $url;?>"><?php echo $name;?></a></p>
			<p class="price"><b>￥<?php echo $rows['pifa_price'];?></b><del>￥<?php echo $rows['shop_price'];?></del></p>
			<a href="javascript:;" onclick="ajax_del_topgoods(<?php echo $rows['gid'];?>,this)" style="position:absolute; top:0px; right:0px; padding:3px; background-color:#FE0000; color:#FFF; z-index:99">删除</a>
			<a href="<?php echo ADMIN_URL.'topgoods.php?type=info&id='.$rows['gid'];?>" style="position:absolute; top:30px; right:0px; padding:3px; background-color:#FE0000; color:#FFF; z-index:99">修改</a>
		</li>
	<?php } ?>
	<div style="clear:both"></div>
	</ul>
</div>
<?php  $thisurl = ADMIN_URL.'topgoods.php'; ?>
<script type="text/javascript">
function ajax_del_topgoods(id,obj){
	if(confirm("确定删除吗？")){
		$.get('<?php echo $thisurl;?>',{type:'ajax_del_topgoods',gid:id},function(data){
			if(data == ""){
				$(obj).parent().hide();
			}else{
				alert(data);
			}
		});
	}
	
}

function ajax_open_addtopgoods(cid){
	JqueryDialog.Open('添加产品','<?php echo ADMIN_URL;?>topgoods.php?type=info&cid='+cid,620,460,'frame');
} 
</script>