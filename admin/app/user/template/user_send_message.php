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
		 	  <iframe id="iframe_t" name="iframe_t" border="0" src="<?php echo ADMIN_URL;?>user.php?type=send_message_frame" scrolling="no" width="95%" frameborder="0" height="700"></iframe>
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

</script>