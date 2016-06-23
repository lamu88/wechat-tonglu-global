<div class="contentbox">
	<div class="openwindow"><img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/><br />正在操作，请稍后。。。</div>
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>友情链接</th>
	</tr>
	<tr>
		<td class="label" width="15%">链接名称:</td>
		<td width="85%">
		<input name="link_name" id="link_name"  type="text" size="43" value="<?php echo isset($rt['link_name']) ? $rt['link_name'] : '';?>">
		<span class="require-field">*</span><span class="link_url_mes"></span></td>
	  </tr>
	  <tr>
		<td class="label">链接地址:</td>
		<td><input name="link_url" id="link_url"  type="text" size="43" value="<?php echo isset($rt['link_url']) ? $rt['link_url'] : '';?>"><span class="require-field">*</span><span class="ad_name_mes"></span></td>
	  </tr>
	  <?php if(isset($rt['link_logo'])&&!empty($rt['link_logo'])){?>
	  <tr class="showimg">
	  	<td class="label">LOGO展示:</td>
		<td>
		<img src="../<?php echo $rt['link_logo'];?>" align="LOGO" width="100"/>
		</td>
	  </tr>
	  <?php } ?>
	  <tr>
	  	<td class="label">设置图片大小:</td>
		<td>
		  宽度：<input type="text" name="width" id="width" size="8" value="<?php echo isset($rt['width'])&&$rt['width']!=0 ? $rt['width'] : '';?>"/>
		  高度：<input type="text" name="height" id="height" size="8" value="<?php echo isset($rt['height'])&&$rt['height']!=0 ? $rt['height'] : '';?>"/>
	    </td>
	  </tr>
	  <tr>
		<td class="label">LOGO:</td>
		<td>
		<input name="link_logo" id="friendlogo" value="<?php echo isset($rt['link_logo']) ? $rt['link_logo'] : '';?>" type="hidden" size="43" /><br />
		 <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['link_logo'])&&!empty($rt['link_logo'])? 'show' : '';?>&ty=friendlogo&files=<?php echo isset($rt['link_logo']) ? $rt['link_logo'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe><span class="link_logo_mes"></span>
		</td>
	  </tr>
	  <tr>
		<th style="border-right:1px solid #B4C9C6">&nbsp;</th>
		<td>
		<input name="btn_save" class="button" id="friendlogo_save" value="<?php echo $type=='edit' ? '修改' : '添加';?>保存" type="button">
		<input  type="hidden" class="link_id" value="<?php echo isset($rt['link_id']) ? $rt['link_id'] : "";?>"/>
		</td>
	  </tr>
	 </table>
</div>
<?php $this->element('showdiv');?>
<?php  $thisurl = ADMIN_URL.'friendlink.php'; ?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('#friendlogo_save').click(function(){
		link_n = $('#link_name').val();
		if(link_n=='undefined' || link_n==""){
			$('.link_name_mes').html("友情链接名称不能为空！");
			$('.link_name_mes').css('color','#FE0000');
			return false;
		}
		
		link_u = $('#link_url').val();
		if(link_u=='undefined' || link_u==""){
			$('.link_url_mes').html("友情链接URL不能为空！");
			$('.link_name_mes').html("");
			$('.link_url_mes').css('color','#FE0000');
			return false;
		}
		
		logo = $('#friendlogo').val();
		if(logo=='undefined' || logo==""){
			$('.link_logo_mes').html("请先上传LOGO图片！");
			$('.link_url_mes').html("");
			$('.link_name_mes').html("");
			$('.link_logo_mes').css('color','#FE0000');
			return false;
		}

		lid = $('.link_id').val();
		w = $('#width').val();
		h = $('#height').val();
		
		$('.openwindow').show(200);
		$.post('<?php echo $thisurl;?>',{action:'edit_add',lid:lid,link_logo:logo,link_url:link_u,link_name:link_n,width:w,height:h},function(data){
			  if(data == ""){
					//$('#link_name').val("");
					//$('#link_url').val("");
					//$('#friendlogo').val("");
					$('.showimg img').attr('src','../'+$('#friendlogo').val());
			  		$('.openwindow').hide(200);
					$('.black_overlay').show(200);
					$('.white_content').show(200);
			  }else{
			  		$('.openwindow').hide(200);
			  		alert(data);
			  }
			});
	});
	
//});
-->
</script>