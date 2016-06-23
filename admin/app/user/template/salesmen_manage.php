<style type="text/css">
.salesmen_menu{ height:30px; background-color:#F6F6F6; border-bottom:2px solid #ccc}
.salesmen_menu li{ float:left; width:80px; height:24px; line-height:24px; background-color:#E2E8EB; margin-right:5px; padding:3px; cursor:pointer; text-align:center}
.salesmen_menu li.active{ background-color:#CCC}
.bg_grey{ font-weight:bold; color:#333}
.ac{ display:block}
</style>
<div class="contentbox" style="width:800px; padding:10px">
    <ul class="salesmen_menu">
		<li class="active bindclick" lang="tab2">品牌销量</li>
		<li class="bindclick" lang="tab3">便利店销量</li>
		<li class="bindclick" lang="tab1">推广品牌</li>
	</ul>
	<div style="clear:both; height:10px;"></div>
	<table cellspacing="2" cellpadding="5" width="100%" class="tab" id="tab1" style="display:none">
		<tr>
			<td>
			 <?php if(!empty($dbbrand))foreach($dbbrand as $row){?>
			 <label>
			 <input type="checkbox" name="checkbox" value="<?php echo $row['brand_id'];?>"<?php echo $row['is_check']=='1' ? ' checked="checked"' :'';?>/><?php echo $row['brand_name'];?>
			 </label>
			<?php } ?>					
			<p style="padding-top:10px; padding-bottom:10px; border-bottom:1px dashed #ccc">选中的为已通过审核，未选中的为未通过审核</p>
			<form id="theForm" name="theForm" method="post" action="">
				<table cellspacing="2" cellpadding="5" width="100%">
				<tr>
				<td>&nbsp;</td>
				<td align="left">
					<fieldset style="border: 1px solid #B4C9C6;">
					  <legend style="background: none repeat scroll #FFF;">推广品牌:</legend>
					  <table style="width: 700px;" align="left">
					  <tr>
						<td align="center">所有品牌</td>
						<td align="center"><div style="height:25px; line-height:25px; width:90px; background-color:#ededed; border-bottom:2px solid #ccc; border-right:2px solid #ccc; padding-left:5px; padding-right:5px; color:#fe0000"><?php //if(empty($is_salesmen)){echo '当前未审核状态';}else{ echo '当前已审核状态';}?></div></td>
						<td align="center">申请的品牌</td>
					  </tr>
					  <tr>
					  <td width="33%" align="center">        
					  <select name="source_select" id="source_select" size="20" style="width:80%;height:300px;"  ondblclick="addItem(this)">
					  <?php if(!empty($allbrand))foreach($allbrand as $row){?>
						<option value="<?php echo $row['brand_id'];?>"><?php echo $row['brand_name'];?></option>
					  <?php } ?>
					  </select>
						</td>
					  <td align="center" width="30%">
						<p style="padding-bottom:5px">
						  <input name="button" type="button" onclick="addAllItem(document.getElementById('source_select'))" value="&gt;&gt;" style="padding-left:5px; padding-right:5px; cursor:pointer"/>
						</p>
						<p style="padding-bottom:5px">
						  <input name="button" type="button" class="button" onclick="addItem(document.getElementById('source_select'))" value="&gt;" style="padding-left:5px; padding-right:5px; cursor:pointer"/>
						</p>
						<p style="padding-bottom:5px">
						  <input name="button" type="button" class="button" onclick="removeItem(document.getElementById('target_select'))" value="&lt;" style="padding-left:5px; padding-right:5px; cursor:pointer"/>
						</p>
						<p style="padding-bottom:5px">
						  <input name="button" type="button" class="button" value="&lt;&lt;" onclick="removeItem(document.getElementById('target_select'), true)" style="padding-left:5px; padding-right:5px; cursor:pointer"/>
						</p></td>
						<td width="" align="center">
						<select name="target_select" id="target_select" size="20" style="width:80%;height:300px" multiple="multiple">
						  <?php if(!empty($dbbrand))foreach($dbbrand as $row){?>
						<option value="<?php echo $row['brand_id'];?>"><?php echo $row['brand_name'];?></option>
						<?php } ?>
						</select>          
						</td>
					</tr>
					  </table>
					</fieldset>
				</td>
				</tr>
				 </table>
			 </form>
			</td>
		</tr>
	</table>
<form action="" method="get">
<input type="hidden" name="type" value="salesmen_manage" />
<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
	<table cellspacing="2" cellpadding="5" width="100%" class="tab" id="tab2" style="line-height:25px; text-align:center">
	  <tr>
		<td colspan="5" align="left">
			&nbsp;选择时间：<input type="text" id="df" name="date1" onClick="WdatePicker()" value="<?php echo isset($_GET['date1'])?$_GET['date1']:date('Y-m-d',mktime()-3600*24*7);?>"/>
			<select name="t1" style="border:1px solid #ccc; margin-top:3px">
			<?php for($i=1;$i<=24;$i++){?>
				<option value="<?php echo $i-1;?>"<?php if(!isset($_GET['t1'])&&$i==1){echo ' selected="selected"';}elseif($_GET['t1']==$i-1){ echo ' selected="selected"';}?>><?php echo $i;?>:00</option>
			<?php } ?>
			</select>
			至&nbsp;
			<input type="text" id="df" name="date2" onClick="WdatePicker()" value="<?php echo isset($_GET['date2'])?$_GET['date2']:date('Y-m-d',mktime());?>"/>
			<select name="t2" style="border:1px solid #ccc; margin-top:3px">
			<?php for($i=1;$i<=24;$i++){?>
				<option value="<?php echo $i-1;?>"<?php if(!isset($_GET['t2'])&&$i==24){echo ' selected="selected"';}elseif($_GET['t2']==$i-1){ echo ' selected="selected"';}?>><?php echo $i;?>:00</option>
			<?php } ?>
			</select>
		</td>
	  </tr>
	  <tr>
		<td colspan="5" align="left">
			 &nbsp;品牌<select name="bid">
			<option value="">--选择品牌--</option>
			 <?php 
			if(!empty($rl)){
			 foreach($rl as $row){ 
			?>
			<option value="<?php echo $row[0]['brand_id'];?>" <?php if( isset($_GET['bid'])&& $_GET['bid']==$row[0]['brand_id']){ echo 'selected="selected""'; } ?>><?php echo $row[0]['brand_name'];?></option>
			<?php
			 }//end foreach
			} ?>
		  </select>
		  &nbsp;配送店<select name="sid">
			<option value="">--选择配送店--</option>
			 <?php 
			if(!empty($rls)){
			 foreach($rls as $row){ 
			?>
			<option value="<?php echo $row[0]['shop_id'];?>" <?php if( isset($_GET['sid'])&& $_GET['sid']==$row[0]['shop_id']){ echo 'selected="selected""'; } ?>><?php echo $row[0]['user_name'];?></option>
			<?php
			 }//end foreach
			} ?>
		  </select>
		  <input type="submit" value="搜索" style="padding:4px"/>
		  </td>
	  </tr>
	  <tr>
		<td width="">编号</td>
		<td width="130">推广品牌</td>
		<td width="374">便利店<br />地址</td>
		<td width="90">总价</td>
		<td width="90">总实价</td>
	  </tr>
	   <?php
	   if(!empty($rl)){
	   $zongprice = 0;
	   $zongprice2 = 0;
	   foreach($rl as $k=>$row){
	  ?>
	  <tr>
		<td class="cr2"><?php echo ++$k;?></td>
		<td><?php echo $row[0]['brand_name'];?></td>
		<td colspan="3">
			<table width="100%"  cellpadding="0"  cellspacing="0">
			<?php $al1=0;$a2=0; foreach($row as $rows){?>
				<tr>
					<td width="359" style="border-left:none"><?php echo $rows['user_name'];?><br/><span class="loads"><img src="<?php echo SITE_URL;?>theme/images/loadings.gif" onload="return load_shop_address('<?php echo $rows['shop_id'];?>',this)"/></span></td>
					<td width="96">￥<?php echo $rows['zmarket_price'];?></td>
					<td width="90">￥<?php echo $rows['zgoods_price'];?></td>
				</tr>
			<?php
			  $a2 +=$rows['zgoods_price'];
			  $al1 += $rows['zmarket_price'];
			  $zongprice += $rows['zgoods_price'];
			  $zongprice2 += $rows['zmarket_price'];
			} ?>
			<tr style="font-weight:bold">
				<td style="text-align:right;border-left:none">总销量&nbsp;</td><td>￥<?php echo $al1;?></td><td>￥<?php echo $a2;?></td>
			</tr>
			</table>
		</td>
	  </tr>
	<?php } } ?>
	 <tr style="font-weight:bold; font-size:14px">
		<td>&nbsp;</td><td>&nbsp;</td><td style="text-align:right">总价&nbsp;</td><td>￥<?php echo $zongprice2;?></td><td>￥<?php echo $zongprice;?></td>
	 </tr>
	</table>
	</form>
	 <form action="" method="get">
	 <input type="hidden" name="type" value="salesmen_manage" />
	 <input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
	<table cellspacing="2" cellpadding="5" width="100%" class="tab" id="tab3" style="display:none; line-height:25px; text-align:center">
	 <tr>
		<td colspan="5" align="left">
			&nbsp;选择时间：<input type="text" id="df" name="date1" onClick="WdatePicker()" value="<?php echo isset($_GET['date1'])?$_GET['date1']:date('Y-m-d',mktime()-3600*24*7);?>"/>
			<select name="t1" style="border:1px solid #ccc; margin-top:3px">
			<?php for($i=1;$i<=24;$i++){?>
				<option value="<?php echo $i-1;?>"<?php if(!isset($_GET['t1'])&&$i==1){echo ' selected="selected"';}elseif($_GET['t1']==$i-1){ echo ' selected="selected"';}?>><?php echo $i;?>:00</option>
			<?php } ?>
			</select>
			至&nbsp;
			<input type="text" id="dt" name="date2" onClick="WdatePicker()" value="<?php echo isset($_GET['date2'])?$_GET['date2']:date('Y-m-d',mktime());?>"/>
			<select name="t2" style="border:1px solid #ccc; margin-top:3px">
			<?php for($i=1;$i<=24;$i++){?>
				<option value="<?php echo $i-1;?>"<?php if(!isset($_GET['t2'])&&$i==24){echo ' selected="selected"';}elseif($_GET['t2']==$i-1){ echo ' selected="selected"';}?>><?php echo $i;?>:00</option>
			<?php } ?>
			</select>
		</td>
	  </tr>
	  <tr>
		<td colspan="5" align="left">
			 &nbsp;品牌<select name="bid">
			<option value="">--选择品牌--</option>
			 <?php 
			if(!empty($rl)){
			 foreach($rl as $row){ 
			?>
			<option value="<?php echo $row[0]['brand_id'];?>" <?php if( isset($_GET['bid'])&& $_GET['bid']==$row[0]['brand_id']){ echo 'selected="selected""'; } ?>><?php echo $row[0]['brand_name'];?></option>
			<?php
			 }//end foreach
			} ?>
		  </select>
		  &nbsp;配送店<select name="sid">
			<option value="">--选择配送店--</option>
			 <?php 
			if(!empty($rls)){
			 foreach($rls as $row){ 
			?>
			<option value="<?php echo $row[0]['shop_id'];?>" <?php if( isset($_GET['sid'])&& $_GET['sid']==$row[0]['shop_id']){ echo 'selected="selected""'; } ?>><?php echo $row[0]['user_name'];?></option>
			<?php
			 }//end foreach
			} ?>
		  </select>
		  <input type="submit" value="搜索" style="padding:4px"/>
		  </td>
	  </tr>
	  <tr>
		<td width="107">编号</td>
		<td width="380">便利店<br />地址</td>
		<td width="130">推广品牌</td>
		<td width="90">总价</td>
		<td width="90">总实价</td>
	  </tr>
	   <?php
	   if(!empty($rls)){
	   foreach($rls as $k=>$row){
	  ?>
	  <tr>
		<td class="cr2"><?php echo ++$k;?></td>
		<td width="380"><?php echo $row[0]['user_name'];?><br/><span class="loads"><img src="<?php echo SITE_URL;?>theme/images/loadings.gif" onload="return load_shop_address('<?php echo $row[0]['shop_id'];?>',this)"/></span></td>
		<td colspan="3">
		<table width="100%"  cellpadding="0"  cellspacing="0">
		<?php $a3=0;$a4=0; foreach($row as $rows){?>
			<tr>
			<td width="124" style="border-left:none"><?php echo $rows['brand_name'];?></td>
			<td width="98">￥<?php echo $rows['zmarket_price'];?></td>
			<td width="">￥<?php echo $rows['zgoods_price'];?></td>
			</tr>
		<?php 
		$a3 += $rows['zmarket_price']; 
		$a4 +=$rows['zgoods_price'];
		$zongprice3 += $rows['zgoods_price'];
		$zongprice4 += $rows['zmarket_price'];
		} ?>
			<tr style="font-weight:bold">
				<td style="text-align:right;border-left:none">总销量&nbsp;</td><td>￥<?php echo $a3;?></td><td>￥<?php echo $a4;?></td>
			</tr>
		</table>
		</td>
	  </tr>
	<?php } } ?>
	 <tr style="font-weight:bold; font-size:14px">
		<td>&nbsp;</td><td>&nbsp;</td><td style="text-align:right">总价&nbsp;</td><td>￥<?php echo $zongprice4;?></td><td>￥<?php echo $zongprice3;?></td>
	 </tr>
	</table>
	</form>
	<p style="text-align:center"><input type="button" name="Submit" value="返回" onclick="history.back()"  style="cursor:pointer; padding:4px"/></p>
</div>
 <script language="javascript" type="text/javascript">
$('.bindclick').mouseover(function(){
	$('.active').removeClass('active');
	
	$(this).addClass('active');
	
	$(".tab").hide();
			
	var content_show = $(this).attr("lang");
	
	$("#"+content_show).show();
});

   function load_shop_address(sid,obj){
   		$.post('<?php echo SITE_URL;?>user.php',{action:'get_suppliers_address',suppliers_id:sid},function(data){
			$(obj).parent().html('['+data+']');
		});
		return false;
   }
</script>
 <?php  $thisurl = SITE_URL.'suppliers.php'; ?>

<script language="javascript" type="text/javascript">
var myTopic = [];

function addItem(sender, value, text)
{
  var target_select = document.getElementById("target_select");
  var newOpt   = document.createElement("OPTION");
  if (sender != null)
  {
    if(sender.options.length == 0) return false;
    var option = sender.options[sender.selectedIndex];
    newOpt.value = option.value;
    newOpt.text  = option.text;
  }
  else
  {
    newOpt.value = value;
    newOpt.text  = text;
  }
  if (targetItemExist(newOpt)) return false;
  if (target_select.length>=50)
  {
    //alert("item_upper_limit");
  }
 // myTopic.push(newOpt.value);
  target_select.options.add(newOpt);
}

function addAllItem(sender)
{
  if(sender.options.length == 0) return false;
  for (var i = 0; i < sender.options.length; i++)
  {
    var opt = sender.options[i];
    addItem(null, opt.value, opt.text);
  }
}

function removeItem(sender,isAll)
{
  if (!isAll)
  {
  	if(sender.selectedIndex == -1) return false;
	 
    for (var i = 0; i < sender.options.length;)
    {
      if (sender.options[i].selected) {
        sender.remove(i);
      }
      else
      {
        i++;
      }
    }
  }
  else
  {
    sender.innerHTML = "";
  }
}

// 商品是否存在
function targetItemExist(opt)
{
  var options = document.getElementById("target_select").options;
  for ( var i = 0; i < options.length; i++)
  {
    if(options[i].text == opt.text && options[i].value == opt.value) 
    {
      return true;
    }
  }
  return false;
}
</script>