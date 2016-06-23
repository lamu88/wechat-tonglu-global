
<?php $this->element('24/top',array('lang'=>$lang)); ?>

<style type="text/css">
.pw,.pwt{
height:26px; line-height:26px;
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{ width:90%;}
.usertitle{
height:22px; line-height:22px;color:#666; font-weight:bold; font-size:14px; padding:5px;
border-radius: 5px;
background-color: #ededed; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pages{ margin-top:20px;}
.pages a{ background:#ededed; padding:2px 4px 2px 4px; border-bottom:2px solid #ccc; border-right:2px solid #ccc; margin-right:5px;}
#main table td:hover{ background:#fafafa}
#main table td p a{ line-height:18px;display:block; padding:1px 5px 1px 5px; float:left; background:#fafafa; border-bottom:2px solid #d5d5d5;border-right:2px solid #d5d5d5;border-radius:10px; margin-right:5px;border-top:1px solid #ededed;border-left:1px solid #ededed;}
</style>
<div id="main" style="padding:5px; min-height:300px">
	 <table  width="100%" border="0" cellpadding="0" cellspacing="0" style="line-height:25px; border:1px solid #E0E0E0;border-radius:5px; background:#EEE; overflow:hidden">
	<?php if(!empty($rt))foreach($rt as $row){
	$ts = '';
	?>
		<tr>
		<td style="border-bottom:1px solid #E0E0E0; padding-left:5px; padding-right:5px">
		<p style="color:#5286B7">赠送券名:<font color="#60ACDC"><?php echo $row['tname'];?></font></p>
		<!--<p style="color:#5286B7">发放类型:<font color="#60ACDC"><?php echo $row['type_name'];?></font></p>-->
		<?php
		if(!empty($ts)){
		}else{
			$ts = $row['used_time']=='0' ? '未使用' : '已使用';
		}
		?>
		<p style="color:#5286B7">
		<span style="float:left">有效时间:<font color="#60ACDC"><?php  if($row['send_start_date']<mktime() && $row['send_end_date']>mktime()){ echo date('Y-m-d',$row['use_end_date']); }else{ echo '[已过期]'; $ts ='已失效'; } ?></font></span>
		<span style="float:right">卡券状态:<font color="#FF0000"><?php echo $ts;?></font></span>
		</p>
		<p style="height:auto; padding-left:10px; padding-right:10px;">
		<img src="<?php echo $this->img('ka980.gif');?>" style="width:100%;" />
		</p>
		<p style="padding-left:5px; padding-right:5px">
		  <input placeholder="输入密码使用" type="text" value="" name="consignee" style="padding:3px 6px 3px 6px; cursor:pointer; border:1px solid #ccc;border-radius:5px; color:#5286B7; background:#ededed; width:80px">
		  <input name="" type="button" value="确认使用" style="padding:3px 6px 3px 6px; cursor:pointer; border:1px solid #ccc;border-radius:5px; color:#5286B7; background:#ededed;" onclick="ajax_user_ka(<?php echo $row['bonus_id'];?>)"/>
		</p>
		<p style="margin-top:5px; padding-bottom:5px;"></p>
		</td>
	  </tr>
	<?php } ?>
	  </tr>
	</table>

</div>
<script type="text/javascript">
function ajax_user_ka(id){
	$.post('<?php echo ADMIN_URL.'user.php';?>',{action:'ajax_user_ka',id:id},function(data){
				
			window.location.href = '<?php echo Import::basic()->thisurl();?>';
				
	});
}
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

$('.oporder').live('click',function(){
		if(confirm("确定吗？")){
			createwindow();
			id = $(this).attr('id');
			na = $(this).attr('name');
			$.post('<?php echo ADMIN_URL.'user.php';?>',{action:'ajax_order_op_user',id:id,type:na},function(data){
				removewindow();
				if(data == ""){
					window.location.href = '<?php echo Import::basic()->thisurl();?>';
				}else{
					alert(data);
				}
			});
		}
		return false;
});
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>