<div class="contentbox">
<style type="text/css">
.menu_content .tab{ display:none}
.nav .active{
	 /*background: url(<?php echo $this->img('manage_r2_c13.jpg');?>) no-repeat;*/
	 background-color:#F5F5F5;
} 
.nav .other{
	/* background: url(<?php echo $this->img('manage_r2_c14.jpg');?>) no-repeat;*/
	 background-color:#E9E9E9;
} 
h2.nav{ border-bottom:1px solid #B4C9C6;font-size:13px; height:25px; line-height:25px; margin-top:0px; margin-bottom:0px}
h2.nav a{ color:#999999; display:block; float:left; height:24px;width:113px; text-align:center; margin-right:1px; margin-left:1px; cursor:pointer}
.addi{ margin:0px; padding:0px;}
.vipprice td{ border-bottom:1px dotted #ccc}
.vipprice th{ background-color:#EEF2F5}
</style>
<form action="" method="post" enctype="multipart/form-data" name="theForm" id="theForm">
 <div class="menu_content">
 	<!--start 通用信息-->
	 <table cellspacing="2" cellpadding="5" width="100%" id="tab1">
	  <tr>
		<td class="label">商品名称:</td>
		<td><input name="goods_name" id="goods_name"  type="text" size="43" value="<?php echo isset($rt['goods_name']) ? $rt['goods_name'] : '';?>"><span style="color:#FF0000">*</span><span class="goods_name_mes"></span>
		<b>商品条形码:</b><input name="goods_sn" id="goods_sn"  type="text" size="23" value="<?php echo isset($rt['goods_sn']) ? $rt['goods_sn'] : '';?>">
		<b>商品编号:</b><input name="goods_bianhao" id="goods_bianhao"  type="text" size="23" value="<?php echo isset($rt['goods_bianhao']) ? $rt['goods_bianhao'] : '';?>">
		</td>
	  </tr>
	 
	  <tr>
            <td class="label">商品单位：</td>
            <td>
			<input name="goods_unit" value="<?php echo isset($rt['goods_unit']) ? $rt['goods_unit'] : '';?>" size="20" type="text" />
			<b>商品重量：</b><input name="goods_weight" value="<?php echo isset($rt['goods_weight']) ? $rt['goods_weight'] : '0.000';?>" size="20" type="text" /> (克)
			</td>
        </tr>

	<tr>
            <td class="label">商品库存数量：</td>
            <td><input name="goods_number" value="<?php echo isset($rt['goods_number']) ? $rt['goods_number'] : '10';?>" size="20" type="text" />
			<b>库存警告数量：</b><input name="warn_number" value="<?php echo isset($rt['warn_number']) ? $rt['warn_number'] : '1';?>" size="20" type="text"></td>
          </tr>
          <tr>
		<td class="label" width="150"><a href="javascript:;" class="addsubcate">[+]</a>所在分类:</td>
		<td>
		 <select name="cat_id" id="cat_id">
	    <option value="0">--选择分类--</option>
		<?php 
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>" <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
													
						<?php 
						if(!empty($rows['cat_id'])){
						foreach($rowss['cat_id'] as $rowsss){ 
						?>
								<option value="<?php echo $rowsss['id'];?>" <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$rowsss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowsss['name'];?></option>
								
						<?php
						}//end foreach
						}//end if
						?>
							
							
					<?php
					}//end foreach
					}//end if
					?>
			<?php
				}//end foreach
		 		} // end if
			?>
		<?php
		 }//end foreach
		} ?>
	 </select>
	 <em style="color:#FF0000">点击[+]增加一个额外分类</em>		
	 </td>
	  </tr>
	  <?php
	  if(isset($subcatelist)&&!empty($subcatelist)){
		  ?>
		 <tr>
			<td class="label" width="15%">其他子分类:</td>
			<td width="85%">
			<?php 
					foreach($subcatelist as $rr){
					echo '<a href="javascript:;" onclick="return del_subcate(\''.$rr['cat_id'].'\',\''.$rr['goods_id'].'\',this);">'.$rr['cat_name'].'[<font color=red>删除</font>]</a>&nbsp;&nbsp;&nbsp;';
					}
			?>
			</td>
		 </tr>
		  <?php
	  }
	  ?>
	  <tr>
	  	<td class="label">品牌：</td>
		<td>
	  <select name="brand_id">
		<option value="0">--选择品牌--</option>
		 <?php 
		if(!empty($brandlist)){
		 foreach($brandlist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>" <?php if(isset($rt['brand_id'])&&$rt['brand_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['brand_id'])){
				foreach($row['brand_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php if(isset($rt['brand_id'])&&$rt['brand_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['brand_id'])){
					foreach($rows['brand_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php if(isset($rt['brand_id'])&&$rt['brand_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
					<?php
					}//end foreach
					}//end if
					?>
			<?php
				}//end foreach
		 		} // end if
			?>
		<?php
		 }//end foreach
		} ?>
        </select>
		</td>
	  </tr>
	  <tr>
		<td class="label">采购价:</td>
		<td><input name="market_price" id="market_price"  type="text" size="13" value="<?php echo isset($rt['market_price']) ? $rt['market_price'] : '';?>">
		<b>原始价:</b><input name="shop_price" id="shop_price"  type="text" size="13" value="<?php echo isset($rt['shop_price']) ? $rt['shop_price'] : '';?>">
		<b>折扣价:</b><input name="pifa_price" id="pifa_price"  type="text" size="13" value="<?php echo isset($rt['pifa_price']) ? $rt['pifa_price'] : '';?>">
		</td>
	  </tr>
	  <tr>
            <td class="label"><label><input type="checkbox" id="is_jifen" name="is_jifen" value="1" checked="checked"/> 积分商品：</label></td>
            <td>
			所需积分<input name="need_jifen" value="<?php echo isset($rt['need_jifen']) ? $rt['need_jifen'] : '0';?>" size="20" type="text" />
			</td>
       </tr>
	   <tr>
		<td class="label">上传商品主图:</td>
		<td>
		  <?php if(isset($rt['goods_img'])){ ?><img src="<?php echo !empty($rt['goods_img']) ? SITE_URL.$rt['goods_img'] : $this->img('no_picture.gif');?>" width="100" style="padding:1px; border:1px solid #ccc"/><?php } ?>
		  <input name="original_img" id="goods" type="hidden" value="<?php echo isset($rt['original_img']) ? $rt['original_img'] : '';?>"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['original_img'])&&!empty($rt['original_img'])? 'show' : '';?>&ty=goods&files=<?php echo isset($rt['original_img']) ? $rt['original_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
	  <tr>
		<td class="label">商品缩略图:</td>
		<td>
		  <?php if(isset($rt['goods_thumb'])){ ?><img src="<?php echo !empty($rt['goods_thumb']) ? SITE_URL.$rt['goods_thumb'] : $this->img('no_picture.gif');?>" width="70" style="padding:1px; border:1px solid #ccc"/><?php } ?>
		  <input name="goods_thumb" id="goods_thumb" type="hidden" value="<?php echo isset($rt['goods_thumb']) ? $rt['goods_thumb'] : '';?>"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['goods_thumb'])&&!empty($rt['goods_thumb'])? 'show' : '';?>&ty=goods_thumb&tyy=goods&files=<?php echo isset($rt['goods_thumb']) ? $rt['goods_thumb'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe><br /><em>如果留空，那么将以原始图片生成缩略图</em>
		</td>
	  </tr>
	  <?php if(isset($gallerylist) && !empty($gallerylist)){?>
	  <tr>
	  <td class="label">&nbsp;</td>
	  <td>
	  <?php 
		if(!empty($gallerylist)){
		echo "<ul class='gallery'>\n";
		foreach($gallerylist as $row){
			echo '<li style="width:120px; text-align:center; border:1px dashed #ccc; float:left; margin:2px;position:relative;height:140px;overflow:hidden "><img src="'.SITE_URL.$row['img_url'].'" alt="'.$row['img_desc'].'" width="90"/><p align="center">'.$row['img_desc'].'</p><a class="delgallery" id="'.$row['img_id'].'" style="position:absolute; top:2px; right:2px; background-color:#FF3333; display:block; width:35px; height:16px;">删除</a></li>';
		}
		echo "</ul>\n";
		}
	  ?>
	  </td>
	  </tr>
	  <?php } ?>
	  <tr>
		<td class="label" valign="middle"><a href="javascript:;" class="addgallery">[+]</a>相册名称：</td>
		<td> 
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left">
					<input type="text" name="photo_gallery_desc[]" value="" size="23"/>
					<input type="hidden" name="photo_gallery_url[]" id="goodsgallery" value=""/>
					<input name="goods_thumb" id="goods_thumb" type="hidden" value="<?php echo isset($rt['goods_thumb']) ? $rt['goods_thumb'] : '';?>"/></td>
					<td align="right"><b>相册图片：</b></td>
					<td align="left"><iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=&ty=goodsgallery&tyy=goods&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe></td>
				</tr>
			</table>
		</td>
	 	</tr>
		<td class="label" width="150">产品详情:</td>
		<td><textarea name="goods_desc" id="content" style="width:95%;height:400px;display:none;"><?php echo isset($rt['goods_desc']) ? $rt['goods_desc'] : '';?></textarea>
		<script>KE.show({id : 'content',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
		</td>
	  </tr>
	  		<tr>
			<td class="label">Meta关键字:</td>
			<td><textarea name="meta_keys" id="meta_keys" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['meta_keys']) ? $rt['meta_keys'] : '';?></textarea></td>
		  </tr>
		  <tr>
			<td class="label">Meta描述:</td>
			<td><textarea name="meta_desc" id="meta_desc" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['meta_desc']) ? $rt['meta_desc'] : '';?></textarea></td>
		  </tr>
	 </table>
	 <!--end 通用信息-->
	  <p style="text-align:center">
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit" style="cursor:pointer" />
	  </p>
 </div> 
  </form>
</div>

<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
<!--
//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		art_title = $('#goods_name').val();
		if(art_title=='undefined' || art_title==""){
			$('.goods_name_mes').html("标题不能为空！");
			$('.goods_name_mes').css('color','#FE0000');
			return false;
		}
		return true;
	});
	
function show_hide(id){
	len = $('.nav a').length;
	if(len>1){
		for(i=1;i<=len;i++){
			if(i==id) { 
				$($('.nav a')[i-1]).removeClass();
				$($('.nav a')[i-1]).addClass('active');
				$("#tab"+id).css('display','block');
			}else{
				$($('.nav a')[i-1]).removeClass();
				$($('.nav a')[i-1]).attr('class',"other");
				$("#tab"+i).css('display','none');
			}
		}
	}
}


function show_addi_type(obj){
	var upvar = $(obj).parent().parent().find('.select option:selected').attr('id'); //获取下拉选中的id值
	if(upvar=="" || typeof(upvar)=='undefined'){ alert("请先选择"); return false; }
	thisvar = $(obj).val();
	 if(thisvar==1){
		$(obj).parent().find('.addi').html('<input name="attr_addi_list[]" value="" size="40" type="text">附加文本');
	}else if(thisvar==2){
		$(obj).parent().find('.addi').html('<input name="attr_addi_list[]" id="goodsaddi'+upvar+'" value="" type="hidden"><iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=&ty=goodsaddi'+upvar+'&tyy=goodsaddi&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>附加图像');
	}else{
		$(obj).parent().find('.addi').html('<input name="attr_addi_list[]" value="" type="hidden">');
	}

	return true;
}

function setvar(obj){
	var thisvar = $(obj).parent().find('.select option:selected').attr('id');
	var setobj = $(obj).parent().find('input[name="attr_addi_list[]"]');
	if(typeof(setobj)!='undefined'){
		setobj.attr('id','goodsaddi'+thisvar);
	}
}
/*增删加控件*/
$('.addaddi').live('click',function(){
	var upvar = $(this).parent().parent().find('.select').val();
	if(upvar=="" || typeof(upvar)=='undefined'){ alert("请先选择"); return false; }
	str = $(this).parent().parent().html();
	str = str.replace('addaddi','removeaddi');
	str= str.replace('[+]','[-]');
	$(this).parent().parent().after('<tr>'+str+'</tr>');
});

$('.removeaddi').live('click',function(){
	$(this).parent().parent().remove();
	return false;
});

//删除该商品的属性
$('.delattr').click(function(){
	   	ids = $(this).val();
		thisobj = $(this).parent();
		if(confirm("确定删除吗")){
			$('.openwindow').show(200);
			$.post('<?php echo $thisurl;?>',{action:'goods_attr_item_del',id:ids},function(data){
				$('.openwindow').hide(200);
				if(data == ""){
					thisobj.hide(300);
				}else{
					alert(data);	
				}
			});
		}else{
			return false;	
		}
});

//删除相册图片
$('.delgallery').click(function(){
	   	ids = $(this).attr('id');
		thisobj = $(this).parent();
		if(confirm("确定删除吗")){
			$('.openwindow').show(200);
			$.post('<?php echo $thisurl;?>',{action:'delgallery',id:ids},function(data){
				$('.openwindow').hide(200);
				if(data == ""){
					thisobj.hide(300);
				}else{
					alert(data);	
				}
			});
		}else{
			return false;	
		}
});

/*增删相册控件*/
$('.addgallery').live('click',function(){
	rand = generateMixed(4);
	str = $(this).parent().parent().html();
	str = str.replace('addgallery','removegallery');
	str = str.replace('[+]','[-]');
	str = str.replace(/goodsgallery/g,'goodsgallery'+rand); //正则表达式替换多个
	$(this).parent().parent().after('<tr>'+str+'</tr>');
});

$('.removegallery').live('click',function(){
	$(this).parent().parent().remove();
	return false;
});

//产生随机数
function generateMixed(n) {
	var chars = ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
    var res = "";
    for(var i = 0; i < n ; i ++) {
        var id = Math.ceil(Math.random()*35);
        res += chars[id];
    }
    return res;
}

/*增删子分类控件*/
	$('.addsubcate').live('click',function(){
		var upvar = $(this).parent().parent().find('#cat_id').val();
		if(upvar=="0" || typeof(upvar)=='undefined'){ alert("请先选择"); return false; }
		str = $(this).parent().parent().html();
		str = str.replace('addsubcate','removesubcate');
		str = str.replace('点击[+]增加一个','点击[-]减少一个');
		str = str.replace(/cat_id/g,'sub_cat_id[]');
		str= str.replace('[+]','[-]');
		$(this).parent().parent().after('<tr>'+str+'</tr>');
	});
	
	$('.removesubcate').live('click',function(){
		$(this).parent().parent().remove();
		return false;
	});
	
	function del_subcate(cid,gid,obj){
		if(confirm("确定删除吗？")){
		   $.post('<?php echo $thisurl;?>',{action:'del_subcate_id',cid:cid,gid:gid},function(data){
			if(data == ""){
				$(obj).hide(200);
			}else{
				alert(data);
			}
			});
		}else{
			return false;
		}
	}
	
	function handlePromote(checked){
		document.forms['theForm'].elements['promote_price'].disabled = !checked;
		document.forms['theForm'].elements['promote_start_date'].disabled = !checked;
		document.forms['theForm'].elements['promote_end_date'].disabled = !checked;
		if(checked==true){
			$('input[name="promote_price"]').css('background-color','#FFF');
			$('input[name="promote_start_date"]').css('background-color','#FFF');
			$('input[name="promote_end_date"]').css('background-color','#FFF');
		}else{
			$('input[name="promote_price"]').css('background-color','#EDEDED');
			$('input[name="promote_start_date"]').css('background-color','#EDEDED');
			$('input[name="promote_end_date"]').css('background-color','#EDEDED');
		}
      	//document.forms['theForm'].elements['selbtn1'].disabled = !checked;
      	//document.forms['theForm'].elements['selbtn2'].disabled = !checked;
	}
	
	function checkvar(obj){ 
		thisvar = $(obj).val();
		if(thisvar>0){
		}else{
		$(obj).val("0.00");
		}
	}
	
	function handlejifen(checked){
		document.forms['theForm'].elements['need_jifen'].disabled = !checked;
		if(checked==true){
			$('input[name="need_jifen"]').css('background-color','#FFF');
		}else{
			$('input[name="need_jifen"]').css('background-color','#EDEDED');
		}
	}
	

function handleqianggou(checked){
		document.forms['theForm'].elements['qianggou_price'].disabled = !checked;
		document.forms['theForm'].elements['qianggou_start_date'].disabled = !checked;
		document.forms['theForm'].elements['qianggou_end_date'].disabled = !checked;
		if(checked==true){
			$('input[name="qianggou_price"]').css('background-color','#FFF');
			$('input[name="qianggou_start_date"]').css('background-color','#FFF');
			$('input[name="qianggou_end_date"]').css('background-color','#FFF');
		}else{
			$('input[name="qianggou_price"]').css('background-color','#EDEDED');
			$('input[name="qianggou_start_date"]').css('background-color','#EDEDED');
			$('input[name="qianggou_end_date"]').css('background-color','#EDEDED');
		}
      	//document.forms['theForm'].elements['selbtn1'].disabled = !checked;
      	//document.forms['theForm'].elements['selbtn2'].disabled = !checked;
	}
	
	function checkqianggouvar(obj){ 
		thisvar = $(obj).val();
		if(thisvar>0){
		}else{
		$(obj).val("0.00");
		}
	}
	
/*增删加控件*/
$('.addgift_type').live('click',function(){
	str = $(this).parent().parent().html();
	str = str.replace('addgift_type','removeaddgift_type');
	str= str.replace('[+]','[-]');
	$(this).parent().parent().after('<tr class="showgift">'+str+'</tr>');
});

$('.removeaddgift_type').live('click',function(){
	$(this).parent().parent().remove();
	return false;
});
function handlegift(checked){
	if(checked==true){
		//$('.showgift').css('display','block');
		$('.showgift').show();
	}else{
		//$('.showgift').css('display','none');
		$('.showgift').hide();
	}
}

function delgoodsgift(gid,ids,obj){
		if(confirm("确定删除吗？")){
		   $.post('<?php echo $thisurl;?>',{action:'delgoodsgift',goods_id:gid,giftid:ids},function(data){
			if(data == ""){
				$(obj).remove()
			}else{
				alert(data);
			}
			});
		}else{
			return false;
		}
}
//});

function ajax_cate_name(obj){
	va = $(obj).parent().find('.searchval').val();
	$.post('<?php echo $thisurl;?>',{action:'ajax_cate_name',searchval:va},function(data){
		if(data == ""){
			alert("未找到！");
		}else{
			$(obj).parent().find('select').html(data);
		}
	});
}
-->
</script>

	  
	  
	  <!-------------- look修改  结束-------------- ------------------------------------------------->
