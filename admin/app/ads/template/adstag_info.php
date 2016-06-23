<div class="contentbox">
  <form id="form1" name="form1" method="post" action="">
  <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='adstag_edit' ? '修改' : '添加';?>广告标签</th>
	</tr>
	 <tr>
		<td class="label" width="15%">标签描述：</td>
		<td width="85%"><input name="ad_name" id="ad_name" type="text" value="<?php echo isset($rt['ad_name']) ? $rt['ad_name'] : '';?>"> <span class="require-field">*</span><span class="ad_name_mes"></span></td>
    </tr>
	  <tr>
		<td class="label">状态：</td>
		<td><label><input id="is_show" name="is_show" value="1" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?> type="checkbox">审核</label><span class="require-field"></span></td>
	  </tr>
	  <tr>
		<td class="label">广告位宽：</td>

		<td><input name="ad_width" id="ad_width"  type="text" value="<?php echo isset($rt['ad_width']) ? $rt['ad_width'] : '';?>"> 像素(px) <span class="require-field">*</span><span class="ad_width_mes"></span></td>
	  </tr>
	  <tr>
		<td class="label">广告位高：</td>
		<td><input name="ad_height" id="ad_height" type="text" value="<?php echo isset($rt['ad_height']) ? $rt['ad_height'] : '';?>"> 像素(px) <span class="require-field">*</span><span class="ad_height_mes"></span></td>
	  </tr>

	  <tr>
		<td class="label">其他说明： </td>
		<td><textarea name="ad_desc" id="ad_desc" style="width: 60%; height: 65px; overflow: auto; color: rgb(68, 68, 68);"><?php echo isset($rt['ad_desc']) ? $rt['ad_desc'] : '';?></textarea></td>
	  </tr>
	  <tr>
		<td class="label">&nbsp;</td>
		<td>
		<input name="btn_save" class="button" id="ads_save" value="<?php echo $type=='adstag_edit' ? '修改' : '添加';?>保存" type="button">
		<input  type="hidden" id="tids" value="<?php echo isset($rt['tid']) ? $rt['tid'] : "";?>"/>
		</td>
	  </tr>
  </table>
	 </form>
</div>
<?php $this->element('showdiv');?>
<?php  $thisurl = ADMIN_URL.'ads.php'; ?>
<script type="text/javascript">
//jQuery(document).ready(function($){
	$('#ads_save').click(function (){
		a_name = $('#ad_name').val(); 
		if(typeof(a_name)=='undefined' || a_name==""){
		 $('.ad_name_mes').html("广告标签不能为空！");
		 $('.ad_name_mes').css('color','#fe0000');
		 return false;
		}
		
		a_width = $('#ad_width').val(); 
		if(!(a_width>0)) a_width = 0.00;
		/*if(typeof(a_width)=='undefined' || a_width=="" || !(a_width >0)){
		 $('.ad_name_mes').html("");
		 $('.ad_width_mes').html("请你键入广告宽度！");
		 $('.ad_width_mes').css('color','#fe0000');
		 return false;
		}*/
		
		a_height = $('#ad_height').val(); 
		if(!(a_height>0)) a_height = 0.00;
		/*if(typeof(a_height)=='undefined' || a_height=="" || !(a_height >0)){
		 $('.ad_name_mes').html("");
		 $('.ad_width_mes').html("");
		 $('.ad_height_mes').html("请你键入广告高度！");
		 $('.ad_height_mes').css('color','#fe0000');
		 return false;
		}
		*/

		a_desc = $('#ad_desc').val(); 
		if(typeof(a_desc)=='undefined'){
			a_desc = "";
		}
		isactive  = $('input[name="is_show"]:checked').val();
		if(typeof(isactive)=='undefined'){
		 	isactive = 0;
		}
		
		tid = $('#tids').val(); 
		createwindow();
		$.post('<?php echo $thisurl;?>',{action:'addadtag',a_name:a_name,a_width:a_width,a_height:a_height,a_desc:a_desc,active:isactive,tid:tid},function(data){ 
			removewindow();
			if(data == ""){
				$('.black_overlay').show(200);
				$('.white_content').show(200);
			}else{
				alert(data);
			}
		});
	});
//});
</script>