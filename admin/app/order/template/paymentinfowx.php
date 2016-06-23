<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '编辑' : '添加';?>支付方式</th>
	</tr>
    <tr>
	   <td width="25%">支付名称</td>
	   <td>
	       <input type="text" name="pay_name" value="<?php echo isset($rt['pay_name']) ? $rt['pay_name'] : "";?>" size="50"/>
	   </td>
	</tr>
	   <tr>
	   <td>支付描述</td>
	   <td>
	       <textarea name="pay_desc" cols="50" rows="5"><?php echo isset($rt['pay_desc']) ? $rt['pay_desc'] : "";?></textarea>
	   </td>
	</tr>
	<?php //if(isset($_GET['id'])&&$_GET['id']=='1'){?>
	<tr>
	   <td>商户ID(Mchid)</td>
	   <td>
	        <input type="text" name="pay_no" value="<?php echo isset($rt['pay_no']) ? $rt['pay_no'] : "";?>" size="50"/>
	   </td>
	</tr>
		<tr>
	   <td>商户密钥Key</td>
	   <td>
	        <input type="text" name="pay_code" value="<?php echo isset($rt['pay_code']) ? $rt['pay_code'] : "";?>" size="50"/>
	   </td>
	</tr>
		<tr style="display:none">
	   <td>合作者身份ID</td>
	   <td>
	        <input type="text" name="pay_idt" value="<?php echo isset($rt['pay_idt']) ? $rt['pay_idt'] : "";?>" size="50"/>
	   </td>
	</tr>
	
	<?php //} ?>
	<tr>
	<td>&nbsp;</td>
	<td>
	  <input type="submit" value="保存" />
	</td>
	</tr>
	 </table>
 </form>
</div>
