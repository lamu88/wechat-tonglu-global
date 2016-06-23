<style type="text/css">
.contentbox table td ul li{ float:left; height:25px; line-height:25px; width:200px; border-bottom:1px dotted #ededed}
</style>
<div class="contentbox">
 <table cellspacing="0" cellpadding="5" width="100%">
 <tr>
    <th colspan="2" align="left">备份类型</th>
  </tr>
  <tr>
    <td align="left" width="150"><label><input type="radio" name="backtype" value="1" checked="checked" onclick="$('.show_tablename').hide();"/>全部备份</label></td>
    <td align="left">备份数据库所有表的所有数据</td>
  </tr>

  <tr>
    <td align="left"><label><input type="radio" name="backtype" value="2" onclick="$('.show_tablename').hide();"/>备份结构</label></td>
    <td align="left">备份数据库结构</td>
  </tr>
  <tr>
    <td align="left"><label><input type="radio" name="backtype" value="3" onclick="$('.show_tablename').show();"/>自定义备份</label></td>
    <td align="left">根据自行选择备份数据表</td>
  </tr>
  <tr style="display:none" class="show_tablename">
  	<td>&nbsp;</td>
	<td>
	 <?php if(!empty($tables)){?>
	<ul>
	<?php foreach($tables as $var){?>
	<li>
	  <label>
	  <input type="checkbox" name="tablename" value="<?php echo $var;?>" /><?php echo $var;?>
	  </label>
	  </li>
	<?php } ?>
	</ul>
	<div style=" clear:both">&nbsp;</div>
	<p><label><input type="checkbox" class="quxuanall" value="checkbox" />全选</label></p>
	  <?php } ?>
	</td>
  </tr>
   <tr>
    <td align="left">备份路径</td>
    <td align="left"><input  name="backdir" class="backdir" value="" readonly="readonly" size="60"/></td>
  </tr>
  <tr>
  	<td colspan="2" align="left" class="load"></td>
  </tr>
  <tr>
  	<td colspan="2" align="left"><input type="button" name="startback" class="startback" value="开始备份" /></td>
  </tr>
  </table>
</div>
<?php $this->element('showdiv');?>

<?php  $thisurl = ADMIN_URL.'backdb.php'; ?>
<script type="text/javascript">
	  $('.quxuanall').click(function (){
		  if(this.checked==true){
			 $("input[name='tablename']").each(function(){this.checked=true;});
		  }else{
			 $("input[name='tablename']:checked").each(function(){this.checked=false;});
		  }
	  });
	  
	$('.startback').click(function(){
		obj = $(this);
		obj.attr('disabled',true);
		type = $("input[name='backtype']:checked").val(); 
		tables = "";
		if(type==3){
			var arr = [];
			$('input[name="tablename"]:checked').each(function(){
				arr.push($(this).val());
			});
			if(arr != ""){
				tables = arr.join('++');
			}else{
				obj.attr('disabled',false);
				alert("请选择备份的表！");
				return false;
			}
		}
		
		//$('.load').html('<img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/>正在备份，请你稍后。。。')
		createwindow();
		$.post('<?php echo $thisurl;?>',{action:'backdb',type:type,tables:tables},function(data){
			removewindow();
			if(typeof(data) == "string"){
				$('.black_overlay').show(200);
				$('.white_content').show(200);
				$('.backdir').val(data);
				$('.load').html("");
			}else{
				alert(data);
			}
			obj.attr('disabled',false);
		});
	});
	
</script>