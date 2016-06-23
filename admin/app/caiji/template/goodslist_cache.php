<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th align="left"><?php echo $catename;?>商品导出</th><th colspan="7" style="text-align:left">
		 <select name="goods_cate" onchange="window.location.href='<?php echo ADMIN_URL;?>caiji.php?type=goodslist_cache&cname='+$(this).val()">
		<option value="">选择分类</option>
		<?php if(!empty($catelist))foreach($catelist as $val){?>
		<option value="<?php echo $val;?>"  <?php if(isset($_GET['cname'])&&$_GET['cname']==$val){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $val;?></option>
		<?php } ?>
		 </select>
		</th>
	</tr>
   
	<tr>
		<th>导航</th><th>分类</th><th>图片</th><th>商品名</th><th>规格</th><th>品牌</th><th>条形码</th><th>操作</th>
	</tr>
	<?php 
	if(!empty($rt)) foreach($rt as $item){
	?>
		<tr>
		<td><?php echo $item['goods_cate_all'];?></td>
		<td><?php echo $item['goods_cate'];?></td>
		<td><img src="<?php echo empty($item['goods_thumb']) ? $this->img('no_picture.gif') : SITE_URL.$item['goods_thumb'];?>" width="60" alt="<?php echo $item['goods_name'];?>"/></td>
		<td><?php echo $item['goods_name'];?></td>
		<td><?php echo $item['goods_brief'];?></td>
		<td><?php echo $item['brand_name'];?></td>
		<td<?php echo $item['goods_sn'];?>></td>
		<td>删除</td>
		</tr>
	<?php } ?>
	<tr>
		<td colspan="8">
		  <label>
			<input type="button" name="Submit" value= " 商品导出 " style="cursor:pointer; padding:4px" onclick="jumpExport('<?php echo isset($_GET['cname']) ? $_GET['cname'] : 'all'?>','<?php echo isset($_GET['list'])? $_GET['list'] : '10000';?>')"/>
		  </label>		</td>
	</tr>
	 </table>
	  <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>

<script type="text/javascript">
//全选
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		 document.getElementById("bathset").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathset").disabled = true;
	  }
  });
  
  //是删除按钮失效或者有效
  $('.gids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathset").disabled = !checked;
  });

	function jumpExport(cname,list){
		window.location.href = '<?php echo ADMIN_URL;?>caiji.php?type=export_goods_list&cname='+cname+'&list='+list;
	}
</script>
