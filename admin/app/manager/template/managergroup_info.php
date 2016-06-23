<div class="contentbox"><style type="text/css">label{ cursor:pointer}</style>
	<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%" align="left">
	 	<tr>
			<th colspan="2" align="left"><?php echo $type=='edit' ? '修改' : '添加';?>权限组</th>
		</tr>
	   <tr>
		<td class="label" width="15%">组名：</td>
		<td  width="85%"><input name="groupname" class="groupname" type="text" value="<?php echo isset($rts['groupname']) ? $rts['groupname'] : "";?>"><span class="require-field">*</span><em>(唯一的)</em></td>
	  </tr>
	  <tr>
		<td class="label">设置：</td>
		<td><label><input  name="active" class="active" value="1" <?php echo isset($rts['active'])&&$rts['active']==1 ? 'checked="checked"' : "";?> type="checkbox">
		激活</label>
		</td>
	  </tr>
	  <tr>
		<td class="label">备注说明： </td>

		<td><textarea name="remark" class="remark" style="width: 60%; height: 65px; overflow: auto; color: rgb(68, 68, 68);"><?php echo isset($rts['remark']) ? $rts['remark'] : "";?></textarea></td>
	  </tr>
	  <tr>
	  	<td class="label">操作权限： </td>
		<td>
		<?php
		 if(!empty($groupname_arr)){
		?>
		   <table cellspacing="0" cellpadding="5" border="0">
		    <tr>
			<?php 
			    $i=0;
				foreach($groupname_arr as $key=>$var){
				if($i==4) echo "</tr><tr>";
			?>
			  <td valign="top">
			  <label><input class="quanxuan" name="quanxuan" value="<?php echo $key;?>" type="checkbox" <?php echo in_array($key,$option_group_arr)&&$type=='edit' ? 'checked="checked"' : '';?>/><?php echo $var;?></label>
			  <?php if(isset($groupname_arr2_sub[$key])){
			  echo "<hr/>";
			  echo "<div>";
			  foreach($groupname_arr2_sub[$key] as $v=>$n){?>
			  	<p><label><input class="subquanxuan" name="quanxuan" value="<?php echo $v;?>" type="checkbox" <?php echo in_array($v,$option_group_arr)&&$type=='edit' ? 'checked="checked"' : '';?>/><?php echo $n;?></label></p>
			  <?php } echo "</div>"; } ?>
			  </td>
			<?php } ?>
			</tr>
		   </table>
		   <?php }  ?>
		</td>
	  </tr>
	  <tr>
	  	<th style="border-right:1px solid #B4C9C6">&nbsp;</th>
	  	<td>
			<label><input type="checkbox" name="checkbox" class="quxuanall" value="checkbox" />全选</label>&nbsp;&nbsp;
	  	    <input type="button" name="button" value="<?php echo $type=='edit' ? '修改' : '添加';?>"  class="addgroup"/>&nbsp;&nbsp;
  	        <input type="reset" name="Submit2" value="重置" />
			<input  type="hidden" class="groupid" value="<?php echo isset($rts['gid']) ? $rts['gid'] : "";?>"/>
        </td>
	  </tr>
     </table>
	</form>
	<div class="clear">&nbsp;</div>
</div>
<?php $this->element('showdiv');?>
<?php  $thisurl = ADMIN_URL.'manager.php'; ?>
<script type="text/javascript">
//jQuery(document).ready(function($){
	 $('.quxuanall').click(function (){
		  if(this.checked==true){
			 $("input[name='quanxuan']").each(function(){this.checked=true;});
		  }else{
			 $("input[name='quanxuan']:checked").each(function(){this.checked=false;});
		  }
	  });
	  
   	  $('.quanxuan').click(function (){
		  if(this.checked==true){
			 $(this).parent().parent().find("input[name='quanxuan']").each(function(){this.checked=true;});
		  }else{
			 $(this).parent().parent().find("input[name='quanxuan']:checked").each(function(){this.checked=false;});
		  }
	  });
	  
	$('.addgroup').click(function(){
		gname  = $('.groupname').val();
		if(gname == ""){
			alert("组名不能为空！");
			return false;
		}
		isactive  = $('input[class="active"]:checked').val();
		mark  = $('.remark').val();
		if(typeof(isactive)=='undefined'){
		 	isactive = 0;
		}
		var arr = [];
	    $('input[name="quanxuan"]:checked').each(function(){
			arr.push($(this).val());
		});
       var str=arr.join('+'); 
       gid  = $('.groupid').val();
	  	createwindow();
		$.post('<?php echo $thisurl;?>',{action:'addgroup',gname:gname,gid:gid,active:isactive,remark:mark,groupvar:str},function(data){ 
			removewindow();
			if(data == ""){
				$('.black_overlay').show(200);
				$('.white_content').show(200);
				if(gid==""){
					$('.groupname').val("");
					$('.remark').val("");
					$('input[name="quanxuan"]:checked').each(function(){
						this.checked=false;
					});
				}
				
			}else{
				$('.openwindow').hide(200);
				alert(data);
			}
		});
	});
	
//});
</script>