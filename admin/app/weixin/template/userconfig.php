<div  style="background:#EEF2F5">
<br /><br /><br />
</div>
<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
		<tr>
			<td class="label">最小提款额度：</td>
			<td>
			<input name="dixin360" value="<?php echo isset($rt['dixin360']) ? $rt['dixin360'] : '0';?>" size="10" type="text" />元
			</td>
		</tr>
		<tr>
			<td class="label">倒三角推广奖励：</td>
			<td>
			 一层返佣金额 <input name="ticheng180_1" value="<?php echo isset($rt['ticheng180_1']) ? $rt['ticheng180_1'] : '0';?>" size="10" type="text" /> 元<br/>
			 二层返佣金额 <input name="ticheng180_2" value="<?php echo isset($rt['ticheng180_2']) ? $rt['ticheng180_2'] : '0';?>" size="10" type="text" /> 元<br/>
			 三层返佣金额 <input name="ticheng180_3" value="<?php echo isset($rt['ticheng180_3']) ? $rt['ticheng180_3'] : '0';?>" size="10" type="text" /> 元<br/>
			</td>
		</tr>
		<tr>
			<td class="label">倒三角横排限制：</td>
			<td>
			  <input name="daolimit" value="<?php echo isset($rt['daolimit']) ? $rt['daolimit'] : '0';?>" size="8" type="text" /> 个
			</td>
		</tr>
		<tr>
			<td class="label">商城黄金经销商分销：</td>
			<td>
			 一层返佣比例 <input name="ticheng180_h1_1" value="<?php echo isset($rt['ticheng180_h1_1']) ? $rt['ticheng180_h1_1'] : '0.00';?>" size="10" type="text" />%<br/>
			 二层返佣比例 <input name="ticheng180_h1_2" value="<?php echo isset($rt['ticheng180_h1_2']) ? $rt['ticheng180_h1_2'] : '0.00';?>" size="10" type="text" />%<br/>
			 三层返佣比例 <input name="ticheng180_h1_3" value="<?php echo isset($rt['ticheng180_h1_3']) ? $rt['ticheng180_h1_3'] : '0.00';?>" size="10" type="text" />%<br/>
			</td>
		</tr>
		<tr>
			<td class="label">商城白金经销商分销：</td>
			<td>
			 一层返佣比例 <input name="ticheng180_h2_1" value="<?php echo isset($rt['ticheng180_h2_1']) ? $rt['ticheng180_h2_1'] : '0.00';?>" size="10" type="text" />%<br/>
			 二层返佣比例 <input name="ticheng180_h2_2" value="<?php echo isset($rt['ticheng180_h2_2']) ? $rt['ticheng180_h2_2'] : '0.00';?>" size="10" type="text" />%<br/>
			 三层返佣比例 <input name="ticheng180_h2_3" value="<?php echo isset($rt['ticheng180_h2_3']) ? $rt['ticheng180_h2_3'] : '0.00';?>" size="10" type="text" />%<br/>
			</td>
		</tr>
		<tr>
			<td class="label">商城钻石经销商分销：</td>
			<td>
			 一层返佣比例 <input name="ticheng180_h3_1" value="<?php echo isset($rt['ticheng180_h3_1']) ? $rt['ticheng180_h3_1'] : '0.00';?>" size="10" type="text" />%<br/>
			 二层返佣比例 <input name="ticheng180_h3_2" value="<?php echo isset($rt['ticheng180_h3_2']) ? $rt['ticheng180_h3_2'] : '0.00';?>" size="10" type="text" />%<br/>
			 三层返佣比例 <input name="ticheng180_h3_3" value="<?php echo isset($rt['ticheng180_h3_3']) ? $rt['ticheng180_h3_3'] : '0.00';?>" size="10" type="text" />%<br/>
			  </td>
		</tr>
		<tr>
			<td class="label" style="background:#AFCCCC">全球升级配置：</td>
			<td style="background:#AFCCCC">
			<p>
			 直推 <input name="quanqiu1" value="<?php echo isset($rt['quanqiu1']) ? $rt['quanqiu1'] : '0';?>" size="5" type="text" /> 个&nbsp;&nbsp;
				<select name="quanqiu1dl">
					<option value ="1"<?php if($rt['quanqiu1dl']==1) echo " selected = 'selected'"; ?>>普通会员</option>
					<option value ="8"<?php if($rt['quanqiu1dl']==8) echo " selected = 'selected'"; ?>>黄金代理</option>
					<option value ="9"<?php if($rt['quanqiu1dl']==9) echo " selected = 'selected'"; ?>>白金代理</option>
					<option value ="10"<?php if($rt['quanqiu1dl']==10) echo " selected = 'selected'"; ?>>钻石代理</option>
			    </select>
			 &nbsp;&nbsp;且团队业绩≥ <input name="quanqiu1my" value="<?php echo isset($rt['quanqiu1my']) ? $rt['quanqiu1my'] : '0.00';?>" size="10" type="text" />元
			 的用户组，
			 每天奖励分红的<input name="qqmoney1" value="<?php echo isset($rt['qqmoney1']) ? $rt['qqmoney1'] : '0';?>" size="5" type="text" />%<br/>
			 </p>
			 <p>
			 直推 <input name="quanqiu2" value="<?php echo isset($rt['quanqiu2']) ? $rt['quanqiu2'] : '0';?>" size="5" type="text" /> 个&nbsp;&nbsp;
				<select name="quanqiu2dl">
					<option value ="1"<?php if($rt['quanqiu2dl']==1) echo " selected = 'selected'"; ?>>普通会员</option>
					<option value ="8"<?php if($rt['quanqiu2dl']==8) echo " selected = 'selected'"; ?>>黄金代理</option>
					<option value ="9"<?php if($rt['quanqiu2dl']==9) echo " selected = 'selected'"; ?>>白金代理</option>
					<option value ="10"<?php if($rt['quanqiu2dl']==10) echo " selected = 'selected'"; ?>>钻石代理</option>
			    </select>
			 &nbsp;&nbsp;且团队业绩≥ <input name="quanqiu2my" value="<?php echo isset($rt['quanqiu2my']) ? $rt['quanqiu2my'] : '0.00';?>" size="10" type="text" />元
			 的用户组，
			 每天奖励分红的<input name="qqmoney2" value="<?php echo isset($rt['qqmoney2']) ? $rt['qqmoney2'] : '0';?>" size="5" type="text" />%<br/>
			 </p>
			 <p>
			 直推 <input name="quanqiu3" value="<?php echo isset($rt['quanqiu3']) ? $rt['quanqiu3'] : '0';?>" size="5" type="text" /> 个&nbsp;&nbsp;
				<select name="quanqiu3dl">
					<option value ="1"<?php if($rt['quanqiu3dl']==1) echo " selected = 'selected'"; ?>>普通会员</option>
					<option value ="8"<?php if($rt['quanqiu3dl']==8) echo " selected = 'selected'"; ?>>黄金代理</option>
					<option value ="9"<?php if($rt['quanqiu3dl']==9) echo " selected = 'selected'"; ?>>白金代理</option>
					<option value ="10"<?php if($rt['quanqiu3dl']==10) echo " selected = 'selected'"; ?>>钻石代理</option>
			    </select>
			 &nbsp;&nbsp;且团队业绩≥ <input name="quanqiu3my" value="<?php echo isset($rt['quanqiu3my']) ? $rt['quanqiu3my'] : '0.00';?>" size="10" type="text" />元
			 的用户组，
			 每天奖励分红的<input name="qqmoney3" value="<?php echo isset($rt['qqmoney3']) ? $rt['qqmoney3'] : '0';?>" size="5" type="text" />%<br/>
			</p>
			 <p>
			 直推 <input name="quanqiu4" value="<?php echo isset($rt['quanqiu4']) ? $rt['quanqiu4'] : '0';?>" size="5" type="text" /> 个&nbsp;&nbsp;
				<select name="quanqiu4dl">
					<option value ="1"<?php if($rt['quanqiu4dl']==1) echo " selected = 'selected'"; ?>>普通会员</option>
					<option value ="8"<?php if($rt['quanqiu4dl']==8) echo " selected = 'selected'"; ?>>黄金代理</option>
					<option value ="9"<?php if($rt['quanqiu4dl']==9) echo " selected = 'selected'"; ?>>白金代理</option>
					<option value ="10"<?php if($rt['quanqiu4dl']==10) echo " selected = 'selected'"; ?>>钻石代理</option>
			    </select>
			 &nbsp;&nbsp;且团队业绩≥ <input name="quanqiu4my" value="<?php echo isset($rt['quanqiu4my']) ? $rt['quanqiu4my'] : '0.00';?>" size="10" type="text" />元
			 的用户组，
			 每天奖励分红的<input name="qqmoney4" value="<?php echo isset($rt['qqmoney4']) ? $rt['qqmoney4'] : '0';?>" size="5" type="text" />%<br/>
			</p>
			 <p>
			 直推 <input name="quanqiu5" value="<?php echo isset($rt['quanqiu5']) ? $rt['quanqiu5'] : '0';?>" size="5" type="text" /> 个&nbsp;&nbsp;
				<select name="quanqiu5dl">
					<option value ="1"<?php if($rt['quanqiu5dl']==1) echo " selected = 'selected'"; ?>>普通会员</option>
					<option value ="8"<?php if($rt['quanqiu5dl']==8) echo " selected = 'selected'"; ?>>黄金代理</option>
					<option value ="9"<?php if($rt['quanqiu5dl']==9) echo " selected = 'selected'"; ?>>白金代理</option>
					<option value ="10"<?php if($rt['quanqiu5dl']==10) echo " selected = 'selected'"; ?>>钻石代理</option>
			    </select>
			 &nbsp;&nbsp;且团队业绩≥ <input name="quanqiu5my" value="<?php echo isset($rt['quanqiu5my']) ? $rt['quanqiu5my'] : '0.00';?>" size="10" type="text" />元
			 的用户组，
			 每天奖励分红的<input name="qqmoney5" value="<?php echo isset($rt['qqmoney5']) ? $rt['qqmoney5'] : '0';?>" size="5" type="text" />%<br/>
			</p>
			<p>说明：每人实得=分红金额*分红比例/当前用户组人数。团队业绩包含自己的所有消费。user_rank_qq 部长1、经理2、董事3、一星董事4、二星董事5</p>
			 </td>
		</tr>
		<tr>
			<td colspan="2">
			<hr/>
			</td>
		</tr>
		<tr>
			<td class="label">分销中心查看设置：</td>
		  <td>
			  <label><input type="radio" name="viewfxset" value="1"<?php echo isset($rt['viewfxset'])&&$rt['viewfxset']=='1' ? ' checked="checked"' : '';?> />&nbsp;普通会员可查看&nbsp;&nbsp;</label>
			  <label><input type="radio" name="viewfxset" value="0"<?php echo isset($rt['viewfxset'])&&$rt['viewfxset']=='0' ? ' checked="checked"' : '';?> />&nbsp;普通会员不可查看&nbsp;&nbsp;</label>
			  </td>
		</tr>
		<tr>
			<td class="label">关注购买：</td>
		  <td>
			  <label><input type="radio" name="guanzhubuy" value="1"<?php echo isset($rt['guanzhubuy'])&&$rt['guanzhubuy']=='1' ? ' checked="checked"' : '';?> />&nbsp;可以购买&nbsp;&nbsp;</label>
			  <label><input type="radio" name="guanzhubuy" value="0"<?php echo isset($rt['guanzhubuy'])&&$rt['guanzhubuy']=='0' ? ' checked="checked"' : '';?> />&nbsp;不可以购买&nbsp;&nbsp;</label>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<hr/>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			  <input type="hidden" name="type" value="basic" />
			<label>
			  <input type="submit" value="确认保存" class="submit" style="cursor:pointer; padding:2px 4px 2px 4px"/>
		  </label></td>
		</tr>
		</table>
</form>
</div>
<br /><br />
<script language="javascript">
function ajax_u_name(obj){
	va = $(obj).parent().find('.searchval').val();
	$.post('<?php echo ADMIN_URL.'goods.php';?>',{action:'ajax_u_name_shopid',searchval:va},function(data){
		if(data == ""){
			alert("未找到！");
		}else{
			$(obj).parent().find('select').html(data);
		}
	});
}
function ajax_save_wid(obj){
	wid = $(obj).parent().find('select').val();
	$.post('<?php echo ADMIN_URL.'weixin.php';?>',{action:'ajax_save_wid',wid:wid},function(data){
		alert(data);
	});
}

$('.submit').click(function(){
	
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
