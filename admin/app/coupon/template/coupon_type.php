<div class="contentbox">
<form action="" method="post" name="theForm" enctype="multipart/form-data">
 <table cellspacing="2" cellpadding="5" width="100%" style="line-height:25px">
	<tr>
	 	<th colspan="2" align="left" style="position:relative; line-height:30px; background-color:#EEF2F5"><?php echo $type=='edit' ? '编辑' : '添加';?>红包类型<span style=" position:absolute; right:5px; top:3px"><a href="coupon.php?type=list">返回红包列表</a></span></th>
	 </tr>
  <tr>
    <td class="label" width="150">赠送券名：</td>
    <td>
      <input type='text' name='tname' maxlength="30" value="<?php echo isset($rt['tname']) ? $rt['tname'] : "";?>" size='20' />    </td>
  </tr> 
  <tr>
    <td class="label" width="150">类型名称：</td>
    <td>
      <input type='text' name='type_name' maxlength="30" value="<?php echo isset($rt['type_name']) ? $rt['type_name'] : "";?>" size='20' />    </td>
  </tr>
  <tr>
    <td class="label">红包金额：</td>
    <td>
    <input type="text" name="type_money" value="<?php echo isset($rt['type_money']) ? $rt['type_money'] : "";?>" size="20" />
    <br /><span class="notice-span" style="display:block"  id="Type_money_a">此类型的红包可以抵销的金额</span>    
	</td>
  </tr>
  <tr>
    <td class="label">最小订单金额：</td>
    <td><input name="min_goods_amount" type="text" id="min_goods_amount" value="<?php echo isset($rt['min_goods_amount']) ? $rt['min_goods_amount'] : "0.00";?>" size="20" />
    <br /><span class="notice-span" style="display:block"  id="NoticeMinGoodsAmount">只有商品总金额达到这个数的订单才能使用这种红包</span> </td>
  </tr>
  <tr>
    <td class="label">如何发放此类型红包：</td>
    <td>
      <input type="radio" name="send_type" value="0"  checked="true" onClick="showunit(0)"<?php echo (!isset($rt['send_type'])||empty($rt['send_type']))?' checked="checked"' : "";?>/>按用户发放      
	  <input type="radio" name="send_type" value="1" onClick="showunit(1)"<?php echo (isset($rt['send_type'])&&$rt['send_type']=='1')?' checked="checked"' : "";?>/>按商品发放    
	  <input type="radio" name="send_type" value="2" onClick="showunit(2)"<?php echo (isset($rt['send_type'])&&$rt['send_type']=='2')?' checked="checked"' : "";?>/>按订单金额发放    
	  <input type="radio" name="send_type" value="3" onClick="showunit(3)"<?php echo (isset($rt['send_type'])&&$rt['send_type']=='3')?' checked="checked"' : "";?>/>线下发放的红包    
	</td>
  </tr>
  <tr>
    <td class="label">发放起始日期：</td>
    <td>
      <input name="send_start_date" type="text" id="df" size="22" value='<?php echo isset($rt['send_start_date']) ? date('Y-m-d',$rt['send_start_date']) : date('Y-m-d',mktime());?>' onClick="WdatePicker()"/><em>点击文本框选择日期</em>
      <br /><span class="notice-span" style="display:block"  id="Send_start_a">只有当前时间介于起始日期和截止日期之间时，此类型的红包才可以<b>发放</b></span>    </td>
  </tr>
  <tr>
    <td class="label">发放结束日期：</td>
    <td>
      <input name="send_end_date" type="text" id="df" size="22" value='<?php echo isset($rt['send_end_date']) ? date('Y-m-d',$rt['send_end_date']) : date('Y-m-d',mktime()+30*3600*24);?>' onClick="WdatePicker()"/><em>点击文本框选择日期</em>
  </td>
  </tr>
  <tr>
    <td class="label">使用起始日期：</td>
    <td>
      <input name="use_start_date" type="text" id="df" size="22" value='<?php echo isset($rt['use_start_date']) ? date('Y-m-d',$rt['use_start_date']) : date('Y-m-d',mktime());?>' onClick="WdatePicker()" /><em>点击文本框选择日期</em>
	  <br /><span class="notice-span" style="display:block"  id="Use_start_a">只有当前时间介于起始日期和截止日期之间时，此类型的红包才可以<b>使用</b></span>    </td>
  </tr>
  <tr>
    <td class="label">使用结束日期：</td>
    <td>
      <input name="use_end_date" type="text" id="df" size="22" value='<?php echo isset($rt['use_end_date']) ? date('Y-m-d',$rt['use_end_date']) : date('Y-m-d',mktime()+30*3600*24);?>' onClick="WdatePicker()"/> <em>点击文本框选择日期</em>
	  </td>
  </tr>
  <tr>
    <td class="label">&nbsp;</td>
    <td>
      <input type="submit" value=" 确定 " class="button" />
      <input type="reset" value=" 重置 " class="button" />
	  </td>
  </tr>
</table>
</form>
</div>
<script language="javascript" type="text/javascript">


/* 红包类型按订单金额发放时才填写 */
function gObj(obj)
{
  var theObj;
  if (document.getElementById)
  {
    if (typeof obj=="string") {
      return document.getElementById(obj);
    } else {
      return obj.style;
    }
  }
  return null;
}


function showunit(get_value)
{
  //gObj("1").style.display =  (get_value == 2) ? "" : "none";
  //document.forms['theForm'].elements['send_start_date'].disabled  = (get_value != 1 && get_value != 2); //0 or 3
  //document.forms['theForm'].elements['send_end_date'].disabled  = (get_value != 1 && get_value != 2); //
 
  return;
}

</script>