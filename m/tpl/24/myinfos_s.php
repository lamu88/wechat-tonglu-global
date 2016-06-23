
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<style type="text/css">
body{ background:#FFF !important;}
.pw,.pwt{
height:26px; line-height:26px;
border:none;
border-bottom: 1px solid #F0F0F0;
background-color: #fff; padding-left:5px; padding-right:5px;
}
.pw{ width:95%;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #F0F0F0; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.myuserinfo{ height:36px; line-height:36px;-webkit-box-shadow:0px 2px 2px #ddd}
.myuserinfo a{ display:block; width:33.3%; float:left; text-align:center}
.myuserinfo a.acc{ background:#ddd; color:#FFF}
table tr{height:38px}
</style>
<div id="main" style=" min-height:300px">
	<div class="myuserinfo">
		<a href="<?php echo ADMIN_URL.'user.php?act=myinfos_u';?>">我的注册资料</a>
		<a class="acc" href="<?php echo ADMIN_URL.'user.php?act=myinfos_s';?>">我的收货资料</a>
	</div>
	<div class="clear10"></div>
	
	 <?php if(!empty($rt['userress'])){ 
		foreach($rt['userress'] as $row){
		?>	
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f0f0f0;padding:10px; margin:0px; line-height:24px;">
			  <tr>
				<td >收货人：<?php echo $row['consignee'];?></td>
			  </tr>
			  <tr>
				
				<td>地址：<?php echo $row['provinces'].$row['citys'].$row['districts'].$row['address'];?></td>
			  </tr>
			  <tr>
				<td>电话：<?php echo $row['mobile'];?></td>
			  </tr>
			  <tr>
				<td>
			   <a href="<?php echo ADMIN_URL.'user.php?act=myinfos_s&id='.$row['address_id'];?>"><img src="<?php echo SITE_URL.'theme/images/btu_up.gif';?>" height="26" width="66" border="0" style="cursor:pointer"/></a>&nbsp; <!--onclick="ressinfoop('<?php echo $row['address_id'];?>','showupdate',this)"-->
			   <img src="<?php echo SITE_URL.'theme/images/btu_dell.gif';?>" height="26" width="66" border="0" onclick="ressinfoop('<?php echo $row['address_id'];?>','delete',this)" style="cursor:pointer"/>&nbsp;
			 <!--  <?php if($row['is_default']=='1'){?>
			   <img class="set_quxiao_icon" src="<?php echo ADMIN_URL.'images/quxiaodefaultress.png';?>" height="26" border="0" onclick="ressinfoop('<?php echo $row['address_id'];?>','quxiao',this)" style="cursor:pointer"/>&nbsp;
			   <?php }else{?>
			   <img class="set_quxiao_icon" src="<?php echo ADMIN_URL.'images/setdefaultress.png';?>" height="26" border="0" onclick="ressinfoop('<?php echo $row['address_id'];?>','setdefaut',this)" style="cursor:pointer"/>&nbsp;
			   <?php } ?>-->
			  </td>
			  </tr>
  </table>
  <?php } }  ?>
  
  <div class="add">
    <form action="" method="" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS" >
	<table width="100%" border="0" style="line-height:24px;padding:10px;" cellpadding="0" cellspacing="0">
		<tr>
		<td align="left"><input placeholder="填写收货姓名" name="consignee" class="pw"  value="" type="text"></td>
	  </tr>
	  <tr>
		<td align="left">  
	<select name="province" id="select_province" class="pwt" onchange="ger_ress_copy('2',this,'select_city')">
	<option value="0">选择省</option>
	<?php 
	if(!empty($rt['province'])){
	foreach($rt['province'] as $row){
	?>
	<option value="<?php echo $row['region_id'];?>" <?php echo $rt['userress']['province']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
	<?php } } ?>													
	</select>
	
	<select name="city" id="select_city" class="pwt" onchange="ger_ress_copy('3',this,'select_district')">
	<option value="0">选择城市</option>
	<?php
	if(!empty($rt['city'])){
	foreach($rt['city'] as $row){
	?>
	<option value="<?php echo $row['region_id'];?>" <?php echo $rt['userress']['city']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
	<?php } } ?>	
	</select>
	
	<select <?php echo !isset($rt['userress']['district'])? 'style="display: none;"':"";?> name="district" class="pwt" id="select_district">
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
		<td align="left"><input placeholder="填写详细地址" name="address" class="pw" value="" type="text"/></td>
	  </tr>
	  <tr>
		<td align="left"><input placeholder="填写正确邮箱" type="text" class="pw" name="email"/></td>
	  </tr>
		<tr>
		<td align="left"><input placeholder="填写联系电话" type="text" class="pw" name="mobile"/></td>
	  </tr>
	  <tr>
		<td align="center" style="padding-top:10px;">
		<a href="javascript:;" onclick="ressinfoop('0','add','CONSIGNEE_ADDRESS')" style="display:block;background:#e13935;cursor:pointer;width:110px; height:32px; line-height:32px; font-size:14px; color:#FFF; font-weight:bold; text-align:center;border-radius: 5px;overflow:hidden">确定提交</a>
		</td>
	  </tr>
	</table>
</form>
 </div>
<script type="text/javascript">
function ger_ress_copy(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post(SITE_URL+'user.php',{action:'get_ress',type:type,parent_id:parent_id},function(data){
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

</script>

</div>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>