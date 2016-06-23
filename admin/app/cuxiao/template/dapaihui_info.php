<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>商品</th>
	</tr>
	<tr>
	   <td class="label">查找关联商品</td>
	   <td><input type="hidden" name="gids" value="<?php echo isset($rt['gids']) ? $rt['gids'] : '0';?>" />
		 <img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
		 <a href="javascript:;" style="padding:5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed; color:#FF0000" onclick="return open_select_goods()">选择商品</a>
		 <span class="return_img">
		 <?php
		 if(!empty($rt['ginfo']))foreach($rt['ginfo'] as $row){
		 echo '<img src="'.SITE_URL.$row['goods_thumb'].'" style="width:90px;padding:1px;border:1px solid #ccc" alt="'.$row['goods_name'].'" onclick="ajax_del_img(this,'.$row['goods_id'].')" />';
		 }
		 ?>
		 </span>
		 <b>【点击图片可提示删除,再保存】</b>		 
		 </td>
	</tr>
		
	<tr>
		<td class="label" width="150">标题:</td>
		<td><input name="title" size="50" value="<?php echo isset($rt['title']) ? $rt['title'] : '';?>" type="text"><span class="require-field">*</span>
		</td>
	  </tr>
	 
		   <tr>
	   <td class="label">日期</td>
	   <td align="left">
	   	  <input type="text" name="start_time" id="df" value="<?php echo isset($rt['start_time'])&&!empty($rt['start_time']) ? date('Y-m-d',$rt['start_time']) : date('Y-m-d',mktime());?>" onClick="WdatePicker()" style="background-color:#FAFAFA"/>
		  	<?php
			 $hs = date('G',$rt['start_time']);
			 $is = date('i',$rt['start_time']);
			 $ss = date('s',$rt['start_time']);
			 ?>
			<select name="xiaoshi_start">
			<?php for($i=0;$i<24;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==$hs ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>：	
			<select name="fen_start">
			<?php for($i=0;$i<60;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==ltrim($is,'0') ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>：
			<select name="miao_start">
			<?php for($i=0;$i<60;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==ltrim($ss,'0') ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>
			&nbsp;-&nbsp;
			<?php
			 $hs = date('G',$rt['end_time']);
			 $is = date('i',$rt['end_time']);
			 $ss = date('s',$rt['end_time']);
			 ?>
	      <input type="text" name="end_time" id="dt" value="<?php echo isset($rt['end_time'])&&!empty($rt['end_time']) ? date('Y-m-d',$rt['end_time']) : date('Y-m-d',mktime()+7*24*3600);?>" onClick="WdatePicker()" style="background-color:#FAFAFA"/>
		  <select name="xiaoshi_end">
			<?php for($i=0;$i<24;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==$hs ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>：	
			<select name="fen_end">
			<?php for($i=0;$i<60;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==ltrim($is,'0') ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>：
			<select name="miao_end">
			<?php for($i=0;$i<60;$i++){?>
			<option value="<?php echo $i;?>"<?php echo $i==ltrim($ss,'0') ? ' selected="selected"' : ''; ?>><?php echo $i;?></option>
			<?php } ?>
	        </select>
		  &nbsp;<em>点击文本选择日期。</em>
	   </td>
	</tr>
  	
	<tr>
		<td class="label">封面:</td>
		<td>
		  <input name="img" id="img" type="hidden" value="<?php echo isset($rt['img']) ? $rt['img'] : '';?>" size="43"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['img'])&&!empty($rt['img'])? 'show' : '';?>&ty=img&tyy=qianggou&files=<?php echo isset($rt['img']) ? $rt['img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		  <span class="notice-span" style="display: block;" id="warn_brandlogo">
			请上传图片，作为产品封面图片！</span>
		</td>
	  </tr>
	
	  <tr>
		<td class="label">是否显示:</td>
		<td>
		<input name="is_show" value="1" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?> type="radio"> 是       
		<input name="is_show" value="0" <?php echo isset($rt['is_show'])&&$rt['is_show']==0 ? 'checked="checked"' : '';?>type="radio"> 否 
		</td>
	  </tr>
	 
	  <tr>
		<td class="label">&nbsp;</td>
		<td>
		<input class="new_save" value="<?php echo $type=='edit' ? '修改' : '添加';?>保存" type="Submit">
		</td>
	  </tr>
	 </table>
	 </form>
</div>

<script type="text/javascript">
function setrun(gname,gid,img){
	gids = $('input[name="gids"]').val()+'-'+gid;
	$('input[name="gids"]').val(gids);
	str = '<img src="'+img+'" style="width:90px;padding:1px;border:1px solid #ccc" alt="'+gname+'" onclick="ajax_del_img(this,'+gid+')" />';
	str = $('.return_img').html()+str;
	$('.return_img').html(str);
}


function open_select_goods(){
	JqueryDialog.Open('','<?php echo ADMIN_URL;?>selectgoods.php',600,350,'frame');
	return false;
}

function ajax_del_img(obj,gid){
	$(obj).remove();
	
	gg = $('input[name="gids"]').val();
	gs = gg.split("-");
	var arr = [];
	for(var i=0;i<gs.length;i++){
	 if(gs[i]==gid){ continue; }
	 arr.push( gs[i] );
	}
	str = arr.join('-');
	$('input[name="gids"]').val(str);
}
</script>