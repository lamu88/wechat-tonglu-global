<style type="text/css">
.gototype a{ padding:2px; border-bottom:2px solid #ccc; border-right:2px solid #ccc;}
</style>
<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 	 <tr>
			<th colspan="2" align="left" class="gototype"><span style="float:left"><?php echo $type=='edit' ? '修改' : '添加';?>商家</span><a href="user.php?type=shoplist&ts=<?php echo $_GET['ts'];?>" style="float:right; margin-left:10px;">返回店铺会员列表</a></th>
		</tr>
		<tr>
			<td class="label" width="15%">昵称：</td>
			<td>
			<input name="nickname" value="<?php echo isset($rt['userinfo']['nickname']) ? $rt['userinfo']['nickname'] : '';?>" size="40" type="text" />
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">真实姓名：</td>
			<td>
			<?php // if($rt['userinfo']['user_rank'] =="1") {?>
			<input name="consignee" value="<?php echo isset($rt['userress']['consignee'])&&!empty($rt['userress']['consignee']) ? $rt['userress']['consignee'] : "";?>" size="40" type="text" />
			<?php //} else{ ?>
			<!--<input name="user_name" value="<?php echo isset($rt['userinfo']['user_name']) ? $rt['userinfo']['user_name'] : '';?>" size="40" type="text" />-->
			<?php // }?>
			</td>
		</tr>
		
		
		<tr>
			<td class="label">密码：</td>
			<td>
			<input name="password" value="" size="42" type="text" />
			</td>
		</tr>
		<tr>
			<td class="label">确认密码：</td>
			<td>
			<input id="confirm_pass" value="" size="42" type="text" />
			</td>
		</tr>
		
		<tr>
			<td class="label">E-mail：</td>
			<td>
			<input name="email" value="<?php echo isset($rt['userinfo']['email']) ? $rt['userinfo']['email'] : '';?>" size="40" type="text" />
			 </td>
		</tr>

		<tr>
			<td class="label">联系电话：</td>
			<td>
			<input name="mobile_phone" value="<?php echo isset($rt['userinfo']['mobile_phone']) ? $rt['userinfo']['mobile_phone'] : '';?>" size="40" type="text" />
			 </td>
		</tr>
		<tr>
			<td class="label">地区：</td>
			<td>
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
			
		<select <?php echo !isset($rt['userress']['district'])? 'style="display: none;"':"";?> name="district" id="select_district" onchange="ger_ress_copy('4',this,'select_town')">
			
			<option value="0">选择社区/县</option>	
				
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
			<td class="label">详细地址：</td>
			<td>
			<input name="address" value="<?php echo isset($rt['userress']['address'])&&!empty($rt['userress']['address']) ? $rt['userress']['address'] : "";?>" size="40" type="text" />
			 </td>
		</tr>
		
		<tr>
			<td><input type="hidden" name="user_rank" value="10" /></td>
			<td><label>
			  <input type="submit" value="<?php echo $type=='edit' ? '确认修改' : '确认添加';?>" class="submit"/>
			</label></td>
		</tr>
		</table>
</form>
</div>
<script language="javascript">
$('.submit').click(function(){
	user = $('input[name="user_name"]').val();
	if(typeof(user)=='undefined' || user==""){
		//alert("昵称不能为空！");
		//return false;
	}
	
/*	pass = $('input[name="password"]').val();
	if(typeof(pass)=='undefined' || pass==""){
		<?php if($type=='add'){?>
		alert("密码不能空！");
		return false;
		<?php } ?>
	}
	
	if(pass.length<6){
		<?php if($type=='add'){?>
		alert("密码过于短，至少6位！");
		return false;
		<?php } ?>
	}
	
	cf_pass = $('#confirm_pass').val();
	if(pass!=cf_pass){
		alert("两次密码不相同！");
		return false;
	}*/
	
	return true;
});



function ger_ress_copy(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post('user.php',{action:'get_ress',type:type,parent_id:parent_id},function(data){
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
				
				//$(obj).parent().find('#select_city').hide();
				//$(obj).parent().find('#select_city').html("");
			}

		}else{
			alert(data);
		}
	});
}




function change_user_points_money(uid,thisobj,type){
	val = $(thisobj).val();
	if(val>0 || val<0){
		if(confirm("你确定执行该操作吗？")){
			createwindow();
			$.post('user.php',{action:'change_user_points_money',uid:uid,type:type,val:val},function(data){
				if(typeof(data)!='undefined' && data!=""){
					removewindow();
					if(parseInt(data)>0){
						if(type=='money'){
							$(thisobj).parent().find('.thismoney').html(data);
						}else if(type =='points'){
							$(thisobj).parent().find('.thispoints').html(data);
						}
					}
					alert("操作成功！");
				}else{
					alert("操作失败！");
				}
			});
		}
	}
	return false;
}

  function get_userpoint_page_list(page,uid){
  		createwindow();
		$.post('user.php',{action:'pointinfo',page:page,uid:uid},function(data){
			removewindow();
			if(data !="" && typeof(data)!='undefined'){
				$('.user_point').html(data);
			}
		});
  }
  
  function get_usermoney_page_list(page,uid){
  		createwindow();
		$.post('user.php',{action:'mymoney',page:page,uid:uid},function(data){
			removewindow();
			if(data !="" && typeof(data)!='undefined'){
				$('.user_money').html(data);
			}
		});
}
</script>
