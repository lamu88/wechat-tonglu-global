<style type="text/css">
.subcates{ margin:0px ; padding:0px;}
.subcates li{ width:150px;  height:30px; border:1px solid #ccc; background-color:#ededed; line-height:30px; float:left; text-align:center; position:relative}
.subcates li a{ display:block}
.subcates li:hover dl{ display:block}
.subcates li dl{ width:150px; height:300px; overflow-x:hidden; overflow-y:scroll; position:absolute; left:0px; top:15px; background-color:#fff; z-index:9999; border:1px solid #ededed; display:none; padding-left:2px; padding-right:2px}
.subcates li dd{ text-align:left; margin:0px; padding:0px; height:26px; line-height:26px; cursor:pointer}
.subcates li dd a{ display:block;}
.subcates li dd:hover{ background-color:#ededed}
.subcates li dd.dd2{ padding-left:8px;}
</style>
<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
<table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><?php echo $type=='cateedit' ? '修改' : '添加';?>商品分类</th>
	</tr>
	<tr>
    <td class="label" width="150">分类名称:</td>
    <td><input name="cat_name" id="cat_name"  maxlength="60" size="30" value="<?php echo isset($rt['cat_name']) ? $rt['cat_name'] : '';?>" type="text"><span class="require-field">*</span><span class="cat_name_mes"></span></td>
  </tr>
    <tr>
    <td class="label">分类标题:</td>
    <td><input name="cat_title"  size="50" value="<?php echo isset($rt['cat_title']) ? $rt['cat_title'] : '';?>" type="text"></td>
  </tr>
  <tr>
  <td class="label">分类广告:</td>
  <td>
   <input name="cat_img" id="cateimg" type="hidden" value="<?php echo isset($rt['cat_img']) ? $rt['cat_img'] : '';?>"/>
   <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['cat_img'])&&!empty($rt['cat_img'])? 'show' : '';?>&ty=cateimg&files=<?php echo isset($rt['cat_img']) ? $rt['cat_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
   </td>
   </tr>
   <tr>
  <td class="label">分类图标:</td>
  <td>
   <input name="cat_icon" id="cateicon" type="hidden" value="<?php echo isset($rt['cat_icon']) ? $rt['cat_icon'] : '';?>"/>
   <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['cat_icon'])&&!empty($rt['cat_icon'])? 'show' : '';?>&ty=cateicon&files=<?php echo isset($rt['cat_icon']) ? $rt['cat_icon'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
   </td>
   </tr>
    <tr>
    <td class="label">分类URL:</td>
    <td><input name="cat_url"  size="50" value="<?php echo isset($rt['cat_url']) ? $rt['cat_url'] : '';?>" type="text"><a href="javascript:;" style="padding:3px 5px 3px 5px; border-bottom:1px solid #ccc;border-right:1px solid #ccc; background:#ededed" onclick="return open_select_url()">从库选择</a></td>
  </tr>
  <tr>
    <td class="label">上级分类:</td>
    <td>
      <select name="parent_id" id="parent_id">
	    <option value="0">顶级分类</option>
		<?php 
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		 	if($type=='edit' && $rt['cat_name']==$row['name']) continue;
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo isset($rt['parent_id'])&&$row['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					if($type=='cateedit' && $rt['cat_name']==$rows['name']) continue;
					?>
					<option value="<?php echo $rows['id'];?>"  <?php echo isset($rt['parent_id'])&&$rows['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
						if($type=='edit' && $rt['cat_name']==$rowss['name']) continue;
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php echo isset($rt['parent_id'])&&$rowss['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
						<?php 
						if(!empty($rows['cat_id'])){
						foreach($rowss['cat_id'] as $rowsss){ 
							if($type=='edit' && $rt['cat_name']==$rowsss['name']) continue;
						?>
								<option value="<?php echo $rowsss['id'];?>"  <?php echo isset($rt['parent_id'])&&$rowsss['id']==$rt['parent_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowsss['name'];?></option>
								
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
	 	<ul class="subcates">
		<?php 
		$k=0;
		if(!empty($catelist)) foreach($catelist as $row){ 
		?>
		<li>
		<a href="javascript:;" id="<?php echo $row['id'];?>"><?php echo $row['name'];?></a>
		<dl>
		<?php if(!empty($row['cat_id']))foreach($row['cat_id'] as $rows){?>
			<dd id="<?php echo $rows['id'];?>"><img src="<?php echo $this->img('check_right.gif');?>" align="absmiddle"/><?php echo $rows['name'];?></dd>
			  <?php if(!empty($rows['cat_id']))foreach($rows['cat_id'] as $rowss){?>
			  <dd class="dd2" id="<?php echo $rowss['id'];?>"><?php echo $rowss['name'];?></dd>
			  <?php } ?>
		<?php } ?>
		</dl>
		</li>
		<?php
		 ++$k;
		 } 
		 ?>
		</ul>
    </td>
  </tr>
   <tr>
  <td class="label">排序:</td>
  <td><input name="sort_order" maxlength="60" size="50" value="<?php echo isset($rt['sort_order']) ? $rt['sort_order'] : '50';?>" type="text"></td>
  </tr>
<!--    <tr>
    <td class="label">是否显示在导航栏:</td>
    <td>
      <input name="show_in_nav" value="1" type="radio" <?php echo $rt['show_in_nav']==1 ? 'checked="checked"' : '';?> /> 是   
	  <input name="show_in_nav" value="0" checked="true" type="radio" <?php echo $rt['show_in_nav']==0 ? 'checked="checked"' : '';?> /> 否    
	  </td>
  </tr>-->
  <tr>
    <tr>
    <td class="label">状态设置:</td>
    <td>
      <label>
		<input name="is_show" value="1" <?php echo !isset($rt['is_show']) || $rt['is_show']==1 ? 'checked="checked"' : '';?> type="radio"> 是 </label>
		 <label><input name="is_show" value="0" <?php echo isset($rt['is_show'])&&$rt['is_show']==0 ? 'checked="checked"' : '';?>type="radio"> 否     </label>  
    </td>
  </tr>
  <tr>
    <td class="label">Meta关键字:</td>
    <td><input name="keywords" maxlength="60" size="50" value="<?php echo isset($rt['keywords']) ? $rt['keywords'] : '';?>" type="text">
    <br><span style="display: block;" id="notice_keywords">关键字为选填项，其目的在于方便外部搜索引擎搜索</span>
    </td>
  </tr>
  <tr>
    <td class="label">Meta描述:</td>
    <td><textarea name="cat_desc" cols="60" rows="4"><?php echo isset($rt['cat_desc']) ? $rt['cat_desc'] : '';?></textarea></td>
  </tr>
  <tr>
  	<td class="label">&nbsp;</td>
    <td align="left"><br>
      <input class="new_save" value=" 确定 " type="submit">
      <input class="button" value=" 重置 " type="reset">
    </td>
  </tr>
  </table>
 </form>
</div>
<?php  $thisurl = ADMIN_URL.'goods.php'; ?>
<script type="text/javascript">
<!--
function setrun(url){
	$('input[name="cat_url"]').val(url);
}

function open_select_url(){
	JqueryDialog.Open('','<?php echo ADMIN_URL;?>selecturl.php',500,300,'frame');
	return false;
}

$('.subcates li dd').click(function(){
	id = $(this).attr('id');
	name = $(this).html();
	
	var s = document.getElementById("parent_id");
	var ops = s.options;
	for(var i=0;i<ops.length; i++){
		var tempValue = ops[i].value;
		if(tempValue == id)   //这里是你要选的值
		{
			ops[i].selected = true;
			break;
		}
	}
	
	
	//alert(name+id);
	//$('select[name="parent_id"]').html('<option value="'+id+'" selected="selected">'+name+'</option>');
	//$('.subcates li dl').css('display','none');
});

$('.subcates li a').click(function(){
	id = $(this).attr('id');
	name = $(this).html();
	
	var s = document.getElementById("parent_id");
	var ops = s.options;
	for(var i=0;i<ops.length; i++){
		var tempValue = ops[i].value;
		if(tempValue == id)   //这里是你要选的值
		{
			ops[i].selected = true;
			break;
		}
	}
});

//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		cname = $('#cat_name').val();
		if(typeof(cname)=="undefined" || cname==""){
		    $('.cat_name_mes').html("分类名称不能为空！");
			$('.cat_name_mes').css('color','#FE0000');
			return false;
		}
		/*ctype = $('select[name="ctype"]').val();
		if(typeof(ctype)!="undefined"){
			parent_id = $('select[name="parent_id"]').val();
			if(parent_id=="0" || typeof(parent_id)=='undefined'){
				ctype = $('select[name="ctype"]').val();
				if(ctype=="0"){
					alert("请选择一个商品分类！");
					return false;
				}
			}
		}*/
		return true;
	});
	

//});
-->
</script>