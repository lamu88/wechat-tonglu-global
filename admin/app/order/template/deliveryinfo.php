<div class="contentbox">
<form id="theForm" name="theForm" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '编辑' : '添加';?>配送方式<span style="float:right"><a href="delivery.php?type=list">返回配送列表</a></span></th>
	</tr>
    <tr>
	   <td width="25%">配送名称</td>
	   <td>
	       <input type="text" name="shipping_name" value="<?php echo isset($rt['shipping_name']) ? $rt['shipping_name'] : "";?>" size="50"/>
	   </td>
	</tr>
<!--	<tr>
	   <td width="25%">物流代号</td>
	   <td>
	       <input type="text" name="shipping_code" value="<?php echo isset($rt['shipping_code']) ? $rt['shipping_code'] : "";?>" size="50"/>
	   </td>
	</tr>-->
	   <tr>
	   <td>配送描述</td>
	   <td>
	       <textarea name="shipping_desc" cols="50" rows="5"><?php echo isset($rt['shipping_desc']) ? $rt['shipping_desc'] : "";?></textarea>
	   </td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>
	  <input type="submit" value="保存" />
	</td>
	</tr>
	 </table>
 </form>
</div>