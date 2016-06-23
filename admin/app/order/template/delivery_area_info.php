<div class="contentbox">
<form id="theForm" name="theForm" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '编辑' : '添加';?>配送方式<span style="float:right"><a href="delivery.php?type=arealist&id=<?php echo $_GET['cid'];?>">返回配送区域列表</a></span></th>
	</tr>
    <tr>
	   <td width="15%" class="label">配送名称</td>
	   <td>
	       <input type="text" name="shipping_area_name" value="<?php echo isset($rt['shipping_area_name']) ? $rt['shipping_area_name'] : "";?>" size="50"/><span style="color:#FF0000">*</span>
	   </td>
	</tr>
	<tr>
    <td class="label">费用计算方式:</td>
    <td>
	  <label><input <?php echo $rt['type']=='item' || !isset($rt['type']) ? 'checked="true"' : "";?> onclick="compute_mode('item_type','weight_type')" name="fee_compute_mode" value="item" type="radio">按商品件数计算</label>  
      <label><input <?php echo $rt['type']=='weight' ? 'checked="true"' : "";?> onclick="compute_mode('weight_type','item_type')" name="fee_compute_mode" value="weight" type="radio">按重量计算</label>  
	  </td>
    </tr>
	<tr class="item_type" <?php echo  $rt['type']=='weight' ? 'style="display:none"' : "";?>>
	   <td class="label">单件商品费用</td>
	   <td>
	       <input name="item_fee" cols="50" rows="5" value="<?php echo isset($rt['item_fee']) ? $rt['item_fee'] : "0.00";?>" size="30"/>单位(￥)
	   </td>
	</tr>
		<tr class="item_type" <?php echo  $rt['type']=='weight' ? 'style="display:none"' : "";?>>
	   <td class="label">每增加一件需费用</td>
	   <td>
	       <input name="step_item_fee" cols="50" rows="5" value="<?php echo isset($rt['step_item_fee']) ? $rt['step_item_fee'] : "0.00";?>" size="30"/>单位(￥)
	   </td>
	</tr>
	<tr <?php echo ($rt['type']=='item' || !isset($rt['type'])) ? 'style="display:none"' : "";?> class="weight_type">
	   <td class="label">500克以内费用</td>
	   <td>
	       <input name="weight_fee" cols="50" rows="5" value="<?php echo isset($rt['weight_fee']) ? $rt['weight_fee'] : "0.00";?>" size="30"/>单位(￥)
	   </td>
	</tr>
		<tr <?php echo ($rt['type']=='item' || !isset($rt['type'])) ? 'style="display:none"' : "";?> class="weight_type">
	   <td class="label">每续重500克费用</td>
	   <td>
	       <input name="step_weight_fee" cols="50" rows="5" value="<?php echo isset($rt['step_weight_fee']) ? $rt['step_weight_fee'] : "0.00";?>" size="30"/>单位(￥)
	   </td>
	</tr>
		<tr>
	   <td class="label">最大费用</td>
	   <td>
	       <input name="max_money" cols="50" rows="5" value="<?php echo isset($rt['max_money']) ? $rt['max_money'] : "0.00";?>" size="30"/>单位(￥)
	   </td>
	</tr>
	   <tr>
	   <td class="label">配送描述</td>
	   <td>
	       <textarea name="shipping_desc" cols="50" rows="5"><?php echo isset($rt['shipping_desc']) ? $rt['shipping_desc'] : "";?></textarea>
	   </td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="left">
		<fieldset style="border: 1px solid #B4C9C6;">
		  <legend style="background: none repeat scroll #FFF;">所辖地区:</legend>
		  <table style="width: 700px;" align="left">
		  <tr>
			<td id="regionCell">
			<?php 
			if(!empty($rt['configure'])){
			foreach($rt['configure'] as $row){
			?>
			<input type='checkbox' name='regions[]' value='<?php echo $row['region_id'];?>' checked='true' /> <?php echo $row['region_name'];?> &nbsp;&nbsp;
			<?php
			}
			}
			?>
			</td>
		  </tr>
		  <tr>
			<td>
				<span style="vertical-align: top;">国家： </span>
				<select name="country" id="selCountries" onchange="ger_ress('1',this,'selProvinces')" size="10" style="width: 80px;">
							<option value="1">中国</option>
				 </select>
				<span style="vertical-align: top;">省份： </span>
				<select name="province" id="selProvinces" onchange="ger_ress('2',this,'selCities')" size="10" style="width: 80px;">
				 	 <option value="">请选择...</option>
				</select>
				<span style="vertical-align: top;">城市： </span>
				<select name="city" id="selCities" onchange="ger_ress('3',this,'selDistricts')" size="10" style="width: 80px;">
				  	<option value="">请选择...</option>
				</select>
				<span style="vertical-align: top;">区/县：</span>
				<select name="district" id="selDistricts" size="10" style="width: 130px;">
				  	<option value="">请选择...</option>
				</select>
				
				<span style="vertical-align:bottom;"><input value="+" class="button" onclick="addRegion()" type="button"></span>
			</td>
		  </tr>
		  </table>
		</fieldset>
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
<script>
/**
 * 添加一个区域
 */
function addRegion()
{
    var selCountry  = document.forms['theForm'].elements['country'];
    var selProvince = document.forms['theForm'].elements['province'];
    var selCity     = document.forms['theForm'].elements['city'];
    var selDistrict = document.forms['theForm'].elements['district'];
    var regionCell  = document.getElementById("regionCell");

    if (selDistrict.selectedIndex > 0)
    {
        regionId = selDistrict.options[selDistrict.selectedIndex].value;
        regionName = selDistrict.options[selDistrict.selectedIndex].text;
    }
    else
    {
        if (selCity.selectedIndex > 0)
        {
            regionId = selCity.options[selCity.selectedIndex].value;
            regionName = selCity.options[selCity.selectedIndex].text;
        }
        else
        {
            if (selProvince.selectedIndex > 0)
            {
                regionId = selProvince.options[selProvince.selectedIndex].value;
                regionName = selProvince.options[selProvince.selectedIndex].text;
            }
            else
            {
                if (selCountry.selectedIndex >= 0)
                {
                    regionId = selCountry.options[selCountry.selectedIndex].value;
                    regionName = selCountry.options[selCountry.selectedIndex].text;
                }
                else
                {
                    return;
                }
            }
        }
    }

    // 检查该地区是否已经存在
    exists = false;
    for (i = 0; i < document.forms['theForm'].elements.length; i++)
    {
      if (document.forms['theForm'].elements[i].type=="checkbox")
      {
        if (document.forms['theForm'].elements[i].value == regionId)
        {
          exists = true;
          alert('选定的地区已经存在。');
        }
      }
    }
    // 创建checkbox
    if (!exists)
    {
      regionCell.innerHTML += "<input type='checkbox' name='regions[]' value='" + regionId + "' checked='true' /> " + regionName + "&nbsp;&nbsp;";
    }
}

function ger_ress(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post('user.php',{action:'get_ress',type:type,parent_id:parent_id},function(data){ 
			$('#'+seobj).html(data);
			if(type==1){
				$('#selCities').html('<option value="0">请选择...</option>');
				$('#selDistricts').html('<option value="0">请选择...</option>');
			}else if(type==2){
				$('#selDistricts').html('<option value="0">请选择...</option>');
			}
			/*if(type==3){
				$(obj).parent().find('#'+seobj).show();
			}
			if(type==2){
				$(obj).parent().find('#select_district').hide();
				$(obj).parent().find('#select_district').html("");
			}*/
	});
}

function compute_mode(t1,t2){
	$('.'+t1).show();
	$('.'+t2).hide();
}

</script>
