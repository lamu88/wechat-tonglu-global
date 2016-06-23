<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='ads_edit' ? '修改' : '添加';?>广告</th>
	</tr>
	<tr>
		<td class="label" width="15%">广告标签:</td>
		<td width="85%">
		<select name="tids" id="tids">
		<option value="">==请选择==</option>
		<?php 
		if(!empty($rts)){
		 foreach($rts as $row){ 
		?>
		<option value="<?php echo $row['tid'];?>" <?php echo isset($rt['tid'])&&$row['tid']==$rt['tid'] ? 'selected="selected"' : '';?>><?php echo $row['ad_name'];?></option>
		<?php }} ?>
		</select> 
		<span class="require-field">*</span><span class="tids_mes"></span></td>
	  </tr>
	  <tr class="catelist" <?php echo ($rt['type']=='gc'||$rt['type']=='ac')? 'style="display:block"' : 'style="display:none"';?>>
		<td class="label">选择展示分类:</td>
		<td width="85%">
		<input type="hidden" name="type" value="<?php echo $rt['type'];?>"/>
		<select name="cat_id" id="cat_id">
		<?php $this->element('ajax_cate_option',array('catelist'=>$catelist,'cat_id'=>$rt['cat_id']));?>
		</select> 
		</td>
	  </tr>
	  <tr>
		<td class="label">广告名称:</td>
		<td><input name="ad_name" id="ad_name"  type="text" size="43" value="<?php echo isset($rt['ad_name']) ? $rt['ad_name'] : '';?>"><span class="require-field">*</span><span class="ad_name_mes"></span></td>
	  </tr>
	  <?php if(isset($rt['ad_img'])&&!empty($rt['ad_img'])){?>
	  <tr class="showimg">
	  	<td class="label">效果预览：</td>
		<td>
		<img src="../<?php echo $rt['ad_img'];?>" alt="LOGO" width="100"/>
		</td>
	  </tr>
	  <?php } ?>
	  <tr>
		<td class="label">广告类型:</td>
		<td>
		  <select name="ads_type" onchange="if(this.value=='1'){$('.images_type').show();$('.flash_type').hide();}else{$('.flash_type').show();$('.images_type').hide();}">
		  <option value="1">图片</option>
		  <option value="2">FLASH</option>
	      </select>
		</td>
	  </tr>
	  <tr class="images_type">
		<td class="label">上传图片:</td>
		<td>
		  <input name="uploadfiles" id="uploadfiles" type="hidden" value="<?php echo isset($rt['ad_img']) ? $rt['ad_img'] : '';?>" size="43"/>
		  <br />
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['ad_img'])&&!empty($rt['ad_img'])? 'show' : '';?>&ty=uploadfiles&tyy=ads&files=<?php echo isset($rt['ad_img']) ? $rt['ad_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe><span class="uploadfiles_mes"></span>
		</td>
	  </tr>
	  <tr style="display:none" class="flash_type">
		<td class="label">FLASH上传:</td>
		<td>
		  <input name="ad_file" id="ad_files" type="hidden" value="<?php echo isset($rt['ad_file']) ? $rt['ad_file'] : '';?>" size="43"/>
		  <br />
		  <iframe id="iframe_t" name="iframe_t" border="0" src="uploadfile.php?action=<?php echo isset($rt['ad_file'])&&!empty($rt['ad_file'])? 'show' : '';?>&ty=ad_files&tyy=ads&files=<?php echo isset($rt['ad_file']) ? $rt['ad_file'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
	  <tr>
		<td class="label">链接地址:</td>

		<td><input name="ad_url" id="ad_url" value="<?php echo isset($rt['ad_url']) ? $rt['ad_url'] : '';?>" type="text" size="43" style="height:18px; line-height:18px"><a href="javascript:;" style="padding:5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed" onclick="return open_select_url()">从库选择</a><br/><em>前面必须带"htpp://"</em>
		</td>
	  </tr>
	  <tr>
		<td class="label">状态设置:</td>
		<td><input id="is_show" name="is_show" value="1" type="checkbox" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?>>审核</td>
	  </tr>
	  <tr>

		<td class="label">备注说明:</td>
		<td><textarea name="remark" id="remark" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['remark']) ? $rt['remark'] : '';?></textarea></td>
	  </tr>
	  <tr>
		<td class="label"></td>
		<td>
		<input name="btn_save" class="ads_save" value="<?php echo $type=='ads_edit' ? '修改' : '添加';?>保存" type="button">
		<input  type="hidden" class="pid" value="<?php echo isset($rt['pid']) ? $rt['pid'] : "";?>"/>
		</td>
	  </tr>
	 </table>
</div>
<?php $this->element('showdiv');?>
<?php  $thisurl = ADMIN_URL.'ads.php'; ?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('.ads_save').click(function(){
		tid = $('#tids').val();
		if(tid=='undefined' || tid==""){
			$('.tids_mes').html("广告标签不能为空！");
			$('.tids_mes').css('color','#FE0000');
			return false;
		}
		
		a_name = $('#ad_name').val();
		if(a_name=='undefined' || a_name==""){
			$('.ad_name_mes').html("广告名称不能为空！");
			$('.tids_mes').html("");
			$('.ad_name_mes').css('color','#FE0000');
			return false;
		}
		
		uploadfile = $('#uploadfiles').val();
		/*if(uploadfile=='undefined' || uploadfile==""){
			$('.uploadfiles_mes').html("请先上传广告图片！");
			$('.tids_mes').html("");
			$('.ad_name_mes').html("");
			$('.uploadfiles_mes').css('color','#FE0000');
			return false;
		}*/
		
		ad_f = $('#ad_files').val();
		
		a_url = $('#ad_url').val();
		isactive  = $('input[name="is_show"]:checked').val();
		if(typeof(isactive)=='undefined'){
			isactive = 0;
		}
		mark = $('#remark').val();
		pids = $('.pid').val();
		cid = $("#cat_id").find("option:selected").val(); 
		types  = $('input[name="type"]').val();
		createwindow();
			$.post('<?php echo $thisurl;?>',{action:'addads',tid:tid,a_name:a_name,uploadfile:uploadfile,ad_file:ad_f,ad_url:a_url,active:isactive,mark:mark,pids:pids,cat_id:cid,type:types},function(data){
			  removewindow();
			  if(data == ""){
			  		if(uploadfile !=""){
						$('.showimg img').attr('src','../'+uploadfile);
					}
					$('.black_overlay').show(200);
					$('.white_content').show(200);
			  }else{
			  		alert(data);
			  }
			});
	});
	
	$('#tids').change(function(){
		// w = $('#cate_left').width(); alert(w);
		 text =  $("#tids").find("option:selected").text(); 
		 if(text=="文章分类广告"){
		 	$.post('<?php echo $thisurl;?>',{action:'getcateoption',type:'ac'},function(data){
				$('select[name="cat_id"]').html(data);
			});
		 	$('input[name="type"]').val('ac');
			$('.catelist').css('display','block');
		 }else if(text=="商品分类广告"){
		 	$.post('<?php echo $thisurl;?>',{action:'getcateoption',type:'gc'},function(data){
				$('select[name="cat_id"]').html(data);
			});
		 	$('input[name="type"]').val('gc');
			$('.catelist').css('display','block');
		 }else{
			$('.catelist').css('display','none');
		 }
		 //$('#cate_left').width(w);
		 //alert(text);
	});
//});


function setrun(url){
	$('input[name="ad_url"]').val(url);
}

function open_select_url(){
	JqueryDialog.Open('','<?php echo ADMIN_URL;?>selecturl.php',600,350,'frame');
	return false;
}
-->
</script>