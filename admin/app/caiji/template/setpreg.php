<div class="contentbox">

     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left">网站设置</th>
	</tr>
	<?php if(!empty($rt))foreach($rt as $row){ ?>
    <tr>
	   <td align="right">网站名称:</td>
	   <td><input  type="text" value="<?php echo $row['sitename'];?>" name="sitename<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
    <tr>
	   <td align="right">网站URL表达式:</td>
	   <td><input  type="text" value="<?php echo $row['url_preg'];?>" name="url_preg<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
	<tr>
	   <td align="right">URL正则表达式:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['goods_cate_preg']);?>" name="goods_cate_preg<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">Meta Title:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['meta_title']);?>" name="meta_title<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">Meta Desc:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['meta_desc']);?>" name="meta_desc<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">Meta Keywords:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['meta_keys']);?>" name="meta_keys<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
	<tr>
	   <td align="right">匹配一:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['goods_preg_1']);?>" name="goods_preg_1<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">匹配二:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['goods_preg_2']);?>" name="goods_preg_2<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">匹配三:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['goods_preg_3']);?>" name="goods_preg_3<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">匹配四:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['goods_preg_4']);?>" name="goods_preg_4<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
			<tr>
	   <td align="right">匹配五:</td>
	   <td><input  type="text" value="<?php echo str_replace('"',"'",$row['goods_preg_5']);?>" name="goods_preg_5<?php echo $row['gcid'];?>" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
		  <input type="button" name="button" value=" 保存修改 " style="cursor:pointer; padding:3px" onclick="savesitedata('<?php echo $row['gcid'];?>')"/>&nbsp;&nbsp;<input type="button" name="button" value=" 开始抓取链接 " style="cursor:pointer; padding:3px" onclick="ajax_showbox('<?php echo $row['gcid'];?>','url','开始抓取链接')"/>&nbsp;&nbsp;<input type="button" name="button" value=" 开始抓取商品 " style="cursor:pointer; padding:3px" onclick="ajax_showbox('<?php echo $row['gcid'];?>','goods','开始抓取商品')"/>&nbsp;&nbsp;<input type="button" name="button" value=" 查看采集到的商品链接 " style="cursor:pointer; padding:3px" onclick="ajax_show_goods_url('<?php echo $row['gcid'];?>')"/>
		</td>
	</tr>
	<?php } ?>
		<tr>
		<td colspan="2"><hr/></td>
	</tr>
 </table>
 
 <form id="form1" name="form1" method="post" action="">
  <table cellspacing="2" cellpadding="5" width="100%">
	<tr>
	   <td align="right">网站名称:</td>
	   <td><input  type="text" value="" name="sitename" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
    <tr>
	   <td align="right">网站URL表达式:</td>
	   <td><input  type="text" value="" name="url_preg" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
	<tr>
	   <td align="right">URL正则表达式:</td>
	   <td><input  type="text" value="" name="goods_cate_preg" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
			<tr>
	   <td align="right">Meta Title:</td>
	   <td><input  type="text" value="" name="meta_title" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">Meta Desc:</td>
	   <td><input  type="text" value="" name="meta_desc" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">Meta Keywords:</td>
	   <td><input  type="text" value="" name="meta_keys" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
	<tr>
	   <td align="right">匹配一:</td>
	   <td><input  type="text" value="" name="goods_preg_1" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">匹配二:</td>
	   <td><input  type="text" value="" name="goods_preg_2" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">匹配三:</td>
	   <td><input  type="text" value="" name="goods_preg_3" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
		<tr>
	   <td align="right">匹配四:</td>
	   <td><input  type="text" value="" name="goods_preg_4" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
				<tr>
	   <td align="right">匹配五:</td>
	   <td><input  type="text" value="" name="goods_preg_5" style="width:500px; height:24px; line-height:24px"/></td>
	</tr>
	<tr>	
		<td>&nbsp;</td>
		<td style="height:10px"><label>
		  <input type="submit" value=" 添加 " style="cursor:pointer; padding:3px"/>
		</label>
		</td>
	</tr>
	 </table>
 </form>
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

function ajax_showbox(gcid,type,text){
	JqueryDialog.Open(text,'<?php echo ADMIN_URL;?>ajax_showbox.php?gcid='+gcid+'&type='+type,600,400,'frame');
}

function ajax_show_goods_url(gcid){
	JqueryDialog.Open('已采集到的商品链接','<?php echo ADMIN_URL;?>ajax_show_goods_url.php?gcid='+gcid,600,420,'frame');
}

function savesitedata(gcid){
	var sna = $('input[name="sitename'+gcid+'"]').val();
	var upr = $('input[name="url_preg'+gcid+'"]').val();
	var gpr = $('input[name="goods_cate_preg'+gcid+'"]').val();
	var mt = $('input[name="meta_title'+gcid+'"]').val();
	var md = $('input[name="meta_desc'+gcid+'"]').val();
	var mk = $('input[name="meta_keys'+gcid+'"]').val();
	var gpr1 = $('input[name="goods_preg_1'+gcid+'"]').val();
	var gpr2 = $('input[name="goods_preg_2'+gcid+'"]').val();
	var gpr3 = $('input[name="goods_preg_3'+gcid+'"]').val();
	var gpr4 = $('input[name="goods_preg_4'+gcid+'"]').val();
	var gpr5 = $('input[name="goods_preg_5'+gcid+'"]').val();
	$.post('<?php  echo ADMIN_URL.'caiji.php'; ?>',{action:'ajaxsetcaijipreg',gcid:gcid,sitename:sna,url_preg:upr,goods_cate_preg:gpr,meta_title:mt,meta_desc:md,meta_keys:mk,goods_preg_1:gpr1,goods_preg_2:gpr2,goods_preg_3:gpr3,goods_preg_4:gpr4,goods_preg_5:gpr5},function(data){
		alert("保存成功");
	});
}


   
   	
</script>