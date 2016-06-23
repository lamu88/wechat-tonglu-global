<div class="contentbox">
  <form id="form1" name="form1" method="post" action="">
<table cellspacing="2" cellpadding="5" width="100%" style="line-height:25px">
 <tr>
	 <th colspan="2" align="left" style="position:relative">发送红包<span style=" position:absolute; right:5px; top:3px"><a href="coupon.php?type=list">返回红包类型</a></span></th>
</tr>
<tr>
    <td class="label">类型金额</td>
    <td>
    <select name="bonus_type_id">
      <option value="<?php echo $send_type['type_id'];?>" selected="selected"><?php echo $send_type['type_name'];?> [￥<?php echo $send_type['type_money'];?>元]</option>    </select>
    </td>
  </tr>

   <tr>
      <td class="label">红包数量</td>
      <td>
      <input name="bonus_sum" size="30" maxlength="6" type="text">
      </td>
    </tr>
    <tr><td class="label">&nbsp;</td>
    <td>提示:红包序列号由六位序列号种子加上四位随机数字组成</td>

   </tr>
   <tr>
   <td class="label">&nbsp;</td>
   <td>
    <input value=" 确定 " class="button" type="submit">
    <input value=" 重置 " class="button" type="reset">
  </td>
 </tr>
</table>
  </form>

</div>