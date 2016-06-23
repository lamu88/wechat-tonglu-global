<style type="text/css">
.contentbox table a.searchAA{color:#222; border-bottom:2px solid #ccc; border-right:2px solid #ccc; padding:3px; background-color:#FAFAFA;}
.contentbox table.ajaxsenduser th{ background-color:#EEF2F5}
.contentbox table.ajaxsenduser td{ border:1px solid #EEF2F5}
.contentbox table.ajaxsenduser td.ajaxpage a{ padding:3px; margin-right:3px; border-bottom:2px solid #ccc; border-right:2px solid #ccc; background-color:#ededed}
</style>

<div class="contentbox">
<p style="padding:50px; font-size:16px; text-align:center">客服群发接口努力升级中</p>
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