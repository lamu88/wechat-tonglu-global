<style type="text/css">
.gototype a{ padding:2px; border-bottom:2px solid #ccc; border-right:2px solid #ccc;}
</style>
<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
		<tr>
			<td class="label" width="15%">消费积分：</td>
			<td>
			消费1元获得<input name="pointnum" value="<?php echo isset($rt['pointnum']) ? $rt['pointnum'] : '0';?>" size="10" type="text" />积分
			</td>
		</tr>
		<tr>
			<td class="label">推荐积分：</td>
			<td>
			 推荐一个用户所得<input name="tuijiannum" value="<?php echo isset($rt['tuijiannum']) ? $rt['tuijiannum'] : '0';?>" size="10" type="text" />积分
			  </td>
		</tr>
		<tr>
			<td class="label">地址享折扣：</td>
			<td>
			  二个地址享<input name="address2off" value="<?php echo isset($rt['address2off']) ? $rt['address2off'] : '100';?>" size="10" type="text" />%。【相对关注后的价格再折扣】<br/><br/>
			   三个地址享<input name="address3off" value="<?php echo isset($rt['address3off']) ? $rt['address3off'] : '100';?>" size="10" type="text" />%。【相对关注后的价格再折扣再相对填2个收货地址再折扣】<br/>
			  </td>
		</tr>
		<tr>
			<td class="label">关注享折扣：</td>
			<td>
			 关注后享<input name="guanzhuoff" value="<?php echo isset($rt['guanzhuoff']) ? $rt['guanzhuoff'] : '100';?>" size="6" type="text" />%。【相对当前的销售价格】
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
<script language="javascript">
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
