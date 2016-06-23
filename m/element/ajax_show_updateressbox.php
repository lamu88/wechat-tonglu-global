<style type="text/css">
.pw{ line-height:26px; height:26px;}
</style>
<form action="" method="" name="CONSIGNEE_ADDRESS<?php echo $rt['userress']['address_id'];?>" id="CONSIGNEE_ADDRESS<?php echo $rt['userress']['address_id'];?>" >
    <table border="0" style="background:#FFFFFF; text-align:right; line-height:19px;" cellpadding="1" cellspacing="4">
  <tr>
    <td width="22%"><b class="cr2">*</b>姓名：</td>
    <td align="left"><input name="consignee" class="pw"  value="<?php echo isset($rt['userress']['consignee']) ? $rt['userress']['consignee'] : "";?>" type="text"></td>
  </tr>
    <tr>
    <td>物流：</td>
	<td align="left"> 
	  <?php
		if(!empty($rt['shippinglist'])){
		foreach($rt['shippinglist'] as $row){
		?>
		<label style="display:block; width:80px; float:left"><input name="shipping_id" id="shipping_id" value="<?php echo $row['shipping_id'];?>" type="radio"  onclick="return jisuan_shopping('<?php echo $row['shipping_id'];?>')"<?php echo $row['shipping_id']==$rt['userress']['shoppingname'] ? ' checked="checked"' : '';?> ><?php echo $row['shipping_name'];?></label>
		<?php } ?>
	  </td>
		<?php } else{ echo '<script> location.href="'.SITE_URL.'mycart.php";</script>'; exit; } ?>
  </tr>
  <tr>
    <td><b class="cr2">*</b>地区：</td>
    <td align="left">  
<?php //$this->element('address',array('resslist'=>$rt['province'],'dbtype'=>array('province'=>$rt['userress']['province'],'city'=>$rt['userress']['city'],'district'=>$rt['userress']['district']),'dbress'=>array('city'=>$rt['city'],'district'=>$rt['district'])));
?>
<select name="province" id="select_province" onchange="ger_ress_copy('2',this,'select_city')">
	<option value="0">选择省</option>
	<?php 
	if(!empty($rt['province'])){
	foreach($rt['province'] as $row){
	?>
	<option value="<?php echo $row['region_id'];?>" <?php echo $rt['userress']['province']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
	<?php } } ?>													
</select>
	
<select name="city" id="select_city" onchange="ger_ress_copy('3',this,'select_district')">
	<option value="0">选择城市</option>
	<?php
	if(!empty($rt['city'])){
	foreach($rt['city'] as $row){
	?>
	<option value="<?php echo $row['region_id'];?>" <?php echo $rt['userress']['city']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
	<?php } } ?>	
</select>
	
<select <?php echo !isset($rt['userress']['district'])? 'style="display: none;"':"";?> name="district" id="select_district">
	<option value="0">选择区</option>	
	<?php 
	if(!empty($rt['district'])){
	foreach($rt['district'] as $row){
	?>
	<option value="<?php echo $row['region_id'];?>" <?php echo $rt['userress']['district']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
	<?php } } ?>													
</select>
</td>
  </tr>
  <tr>
    <td valign="top"><b class="cr2">*</b>地址：</td>
    <td align="left" style="line-height:18px"><input name="address" class="pw" value="<?php echo isset($rt['userress']['address']) ? $rt['userress']['address'] : "";?>" type="text"/>
    </td>
  </tr>
 <!-- <tr>
    <td><b class="cr2">*</b>微信：</td>
    <td align="left"><input type="text" class="pw" name="email" value="<?php echo isset($rt['userress']['email']) ? $rt['userress']['email'] : "";?>"/></td>
  </tr>-->
  <tr>
    <td valign="top"><b class="cr2">&nbsp;</b>电话：</td>
    <td align="left" style="line-height:18px"><input type="text" class="pw" name="mobile" value="<?php echo isset($rt['userress']['mobile']) ? $rt['userress']['mobile'] : "";?>"/>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left"><input type="button" value=""  style=" overflow:hidden ; border:none; background:none;cursor:pointer; background:url(<?php echo SITE_URL;?>theme/images/add_btu.gif) no-repeat 0 0 ; width:140px; height:26px;" onclick="ressinfoop('<?php echo $rt['userress']['address_id'];?>','update','CONSIGNEE_ADDRESS<?php echo $rt['userress']['address_id'];?>')"/></td>
  </tr>
</table>
<script language="javascript" type="text/javascript">
$('input[name="shoppingname"]').live('click',function(){
	var ps= $(this).val();
	if(ps=='1'){
	$('.shipping').show();
	}else{
	$('.shipping').hide();
	}
});
function ger_ress_copy(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post(SITE_URL+'user.php',{action:'get_ress',type:type,parent_id:parent_id},function(data){
		if(data!=""){
			$(obj).parent().find('#'+seobj).html(data);
			if(type==5){ //村
				$(obj).parent().find('#'+seobj).show();
				$(obj).parent().find('#select_peisong').hide();
			}else if(type==4){ //城镇
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				$(obj).parent().find('#select_peisong').hide();
				
				$(obj).parent().find('#select_town').show();
				//$(obj).parent().find('#select_town').html("");
			}else if(type==3){ //区
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				
				$(obj).parent().find('#select_town').hide();
				$(obj).parent().find('#select_town').html('<option value="0" >选择城镇</option>');
				
				$(obj).parent().find('#select_district').show();
				//$(obj).parent().find('#select_district').html("");
				
			}else if(type==2){ //市
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				
				$(obj).parent().find('#select_town').hide();
				$(obj).parent().find('#select_town').html('<option value="0" >选择城镇</option>');
				
				$(obj).parent().find('#select_district').hide();
				$(obj).parent().find('#select_district').html('<option value="0" >选择区</option>');
			}
			
		}else{
			alert(data);
		}
	});
}
//获取配送店
function get_peisong(obj,seobj){
	village_id = $(obj).val();
	town_id = $(obj).parent().find('select[name="town"]').val();
	district_id = $(obj).parent().find('select[name="district"]').val();

	if(village_id=="" || typeof(village_id)=='undefined'){ return false; }
	$.post(SITE_URL+'user.php',{action:'get_peisong',village_id:village_id,town_id:town_id,district_id:district_id,type:'ajax'},function(data){
		if(data!=""){ 
			$(obj).parent().find('#'+seobj).html(data);
			
			$(obj).parent().find('#'+seobj).show();
			
		}else{
			alert(data);
		}
	});
}
</script>
</form>