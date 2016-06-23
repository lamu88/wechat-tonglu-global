<style type="text/css">
.contentbox table a.searchAA{color:#222; border-bottom:2px solid #ccc; border-right:2px solid #ccc; padding:3px; background-color:#FAFAFA;}
.contentbox table.ajaxsenduser th{ background-color:#EEF2F5}
.contentbox table.ajaxsenduser td{ border:1px solid #EEF2F5}
.contentbox table.ajaxsenduser td.ajaxpage a{ padding:3px; margin-right:3px; border-bottom:2px solid #ccc; border-right:2px solid #ccc; background-color:#ededed}
</style>

<div class="contentbox">
<form id="theFrom" name="theFrom" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 		<tr>
				<th colspan="5" align="left"><span style="float:left">用户红包派发</span><a href="coupon.php?type=list" style="float:right">返回红包类型</a></th>
			</tr>
	 	 	<tr>
			<td colspan="2" align="right"><span style="color:#FF0000; font-weight:bold">你可以快速大量派发</span></td>
			 <td>
		  		<select name="quest_user_rank">
				<option value="0">选择等级</option>
				<?php 
				if(!empty($rt['user_jibie'])){
				foreach($rt['user_jibie'] as $row){
				?>
				  <option value="<?php echo $row['lid'];?>" <?php echo isset($rt['userinfo']['user_rank'])&&$row['lid']==$rt['userinfo']['user_rank'] ? 'selected="selected"' : "";?>><?php echo $row['level_name'];?></option>
				  <?php }}else{?>
				   <option value="1">普通会员</option>
				  <?php } ?>
			    </select>
		  </td>
		   <td colspan="2" align="left">
		   <input type="button" value="选择等级快速派发" style="cursor:pointer; color:#FF0000" onclick="send_coupon_userlevel(this)"/>&nbsp;&nbsp;<span class="send_message_level" style="color:#FF0000; display:none"></span>
			</td>
		</tr>
		<tr>
		<td colspan="5">&nbsp;</td>
		</tr>
	 	 <tr>
		 <td valign="40"><img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
</td>
		<td width="60" align="right">关键字：</td><td align="left" width="200"><input type="text" name="keys" /></td>
		<td width="60" align="right">地区：</td>
		<td align="left">
		<select name="province" id="select_province" onchange="ger_ress_copy('2',this,'select_city')">
			<option value="0">选择省</option>
			<?php 
			if(!empty($rt['province'])){
			foreach($rt['province'] as $row){
			?>
			<option value="<?php echo $row['region_id'];?>"><?php echo $row['region_name'];?></option>	
			<?php } } ?>													
			</select>
			
			<select name="city" id="select_city" onchange="ger_ress_copy('3',this,'select_district')">
			<option value="0">选择城市</option>
			<?php
			if(!empty($rt['city'])){
			foreach($rt['city'] as $row){
			?>
			<option value="<?php echo $row['region_id'];?>"><?php echo $row['region_name'];?></option>	
			<?php } } ?>	
			</select>
			
			<select <?php echo !isset($rt['userress']['district'])? 'style="display: none;"':"";?> name="district" id="select_district">
			<option value="0">选择区</option>	
			<?php 
			if(!empty($rt['district'])){
			foreach($rt['district'] as $row){
			?>
			<option value="<?php echo $row['region_id'];?>"><?php echo $row['region_name'];?></option>	
			<?php } } ?>													
			</select>
		</td>
		 </tr>
		 <tr>
		 <td>&nbsp;</td>
		 <td>会员级别：</td>
		  <td>
		  		<select name="user_rank">
				<option value="0">选择等级</option>
				<?php 
				if(!empty($rt['user_jibie'])){
				foreach($rt['user_jibie'] as $row){
				?>
				  <option value="<?php echo $row['lid'];?>" <?php echo isset($rt['userinfo']['user_rank'])&&$row['lid']==$rt['userinfo']['user_rank'] ? 'selected="selected"' : "";?>><?php echo $row['level_name'];?></option>
				  <?php }}else{?>
				   <option value="1">普通会员</option>
				  <?php } ?>
			    </select>
		  </td>
		   <td>会员性别：</td>
		    <td>
				<select name="sex">
				<option value="0">选择性别</option>
				<option value="1">保密</option>
				<option value="2">男</option>
				<option value="3">女</option>
			    </select>
			</td>
		 </tr>
		 <tr>
		 	<td>&nbsp;</td>
			<td align="right">生日：</td>
			<td>
			<input name="start_birthday" id="df" value="" type="text" onClick="WdatePicker()" size="7"/>&nbsp;-&nbsp;
			<input name="end_birthday" id="df" value="" type="text" onClick="WdatePicker()" size="7"/>
			</td>
			<td>注册日期：</td>
			<td>
			<input name="start_reg_date" id="df" value="" type="text" onClick="WdatePicker()"size="7"/>&nbsp;-&nbsp;
			<input name="end_reg_date" id="df" value="" type="text" onClick="WdatePicker()"size="7"/>
			</td>
		 </tr>
		 <tr>
				<td colspan="2" align="right"><a href="javascript:void(0)" onclick="getuser('salerank')" class="searchAA"><img src="<?php echo $this->img('icon_view.gif');?>" align="absmiddle"/>销量排行搜索</a></td>
				<td colspan="2"><a href="javascript:void(0)" onclick="getuser('poitsrank')" class="searchAA"><img src="<?php echo $this->img('icon_view.gif');?>" align="absmiddle"/>积分排行搜索</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="getuser('logincount')" class="searchAA"><img src="<?php echo $this->img('icon_view.gif');?>" align="absmiddle"/>登录次数搜索</a></td>
				<td align="left">
				  <input type="button" name="button" value="并列条件查找" style="cursor:pointer" onclick="getuser('')"/>
				</td>
		 </tr>
		 <tr>
		 	<td colspan="5" class="USER_LIST" style="border-top:1px dotted #ccc;border-buttom:1px dotted #ccc">
			<?php $this->element('ajax_need_send_user',array('rt_user'=>$rt_user)); ?>
			</td>
		 </tr>
		 <tr>
	 	   <td colspan="5">
			<em style="color:#FF0000">双击选中项可以删除！</em><br />
			<select name="user[]" id="user" multiple="true" style="width:400px; margin-left:5px;" size="15" ondblclick="delUser()">
      </select>
	        <input type="button" name="Submit" value="确认派发" style="cursor:pointer; color:#FF0000" onclick="send_coupon_select()"/>&nbsp;&nbsp;<span class="send_message" style="color:#FF0000; display:none"></span>
			</td>
		 </tr>
	</table>
</form>
</div>
<?php  $thisurl = ADMIN_URL.'user.php'; ?>
<script language="javascript" type="text/javascript">
function ger_ress_copy(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post('<?php echo $thisurl;?>',{action:'get_ress',type:type,parent_id:parent_id},function(data){
		if(data!=""){
			$(obj).parent().find('#'+seobj).html(data);
			if(type==3){
				$(obj).parent().find('#'+seobj).show();
			}
			if(type==2){
				$(obj).parent().find('#select_district').hide();
				$(obj).parent().find('#select_district').html("");
			}
		}else{
			alert(data);
		}
	});
}

function getuser(type){
  var theFrom      = document.forms['theFrom']; //表单
  var spec_arr     = new Object(); //获取过来的商品属性
  createwindow();
  // 检查是否有商品规格 
  if (theFrom)
  {
    spec_arr = getFormAttrs(theFrom);
	spec_arr.type= type;
	spec_arr.page= 1;
	spec_arr.returnw= "";
  }
  $.post('<?php echo $thisurl;?>',{action:'getuser',message:$.toJSON(spec_arr)},function(data){
		$('.USER_LIST').html(data);
		removewindow();
  });
}
function ajax_getuser(page,w){
  var spec_arr     = new Object(); //获取过来的商品属性
  createwindow();
  // 检查是否有商品规格 
  spec_arr.page= page;
  spec_arr.returnw= w;
 
  $.post('<?php echo $thisurl;?>',{action:'getuser',message:$.toJSON(spec_arr)},function(data){
		$('.USER_LIST').html(data);
		removewindow();
  });
}

function getId(id){
	return document.getElementById(id);
}
  
function setuserid(arr_id,arr_name){
	var str="";
	if(arr_id.length>0){
		var dest = document.getElementById('user');
		for(i=0;i<arr_id.length;i++){
				//检查是否已经存在
				var tt = false;
				if(dest.options.length>0){
					for(ii=0;ii<dest.options.length;ii++){
						if(dest.options[ii].value == arr_id[i]){ tt = true; break;}
					}
				}
				if( tt == true){ continue; }

				var opt = document.createElement('OPTION');
                opt.value = arr_id[i];
                opt.text = arr_name[i];
                dest.options.add(opt);
		}
	}
}

//删除已添加到的用户
function delUser()
  {
      var dest = document.getElementById('user');
 
      for (var i = dest.options.length - 1; i >= 0 ; i--)
      {
          if (dest.options[i].selected)
          {
              dest.options[i] = null;
          }
      }
  }

function send_coupon_level(tt){

}

function send_coupon_select(){
	var dbarr = [];
	$('.send_message').html("【正在派发中。。。】");
	$('.send_message').show();
	var dest = document.getElementById('user');
	if(dest.options.length>0){
		for(i=0;i<dest.options.length;i++){
			dbarr.push(dest.options[i].value);
		}
	}
	
	if(dbarr.length > 0){
		str = dbarr.join('|');
		$.get('<?php echo ADMIN_URL;?>coupon.php',{type:'couponsend_op',ids:str,tt:'selectuser',type_id:'<?php echo $_GET['type_id'];?>'},function(data){
				if(data !=""){
					$('.send_message').html("【"+data+"】");
				}else{
					$('.send_message').html("【已经完全派发】");
				}
		});
	}else{
		$('.send_message').html("【无效派发】");
	}
}

function send_coupon_userlevel(obj){
	var dbarr = [];
	var dest = document.getElementById('user');
	if(dest.options.length>0){
		for(i=0;i<dest.options.length;i++){
			dbarr.push(dest.options[i].value);
		}
	}
	var level = $(obj).parent().parent().find('select[name="quest_user_rank"]').val();
	
	if(level > 0){
		$('.send_message_level').show();
		$('.send_message_level').html("【正在派发中。。。】");
		$.get('<?php echo ADMIN_URL;?>coupon.php',{type:'couponsend_op',ids:level,tt:'userlevel',type_id:'<?php echo $_GET['type_id'];?>'},function(data){
				if(data !=""){
					$('.send_message_level').html("【"+data+"】");
				}else{
					$('.send_message_level').html("【已经完全派发】");
				}
		});
	}else{
		$('.send_message_level').html("【选择会员等级】");
	}
}
</script>