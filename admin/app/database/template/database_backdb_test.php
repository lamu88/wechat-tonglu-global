<div class="contentbox">
 <table cellspacing="0" cellpadding="5" width="100%">
 <tr>
    <th colspan="2" align="left">备份类型</th>
  </tr>
  <tr>
    <td align="left" width="160"><input type="radio" name="backtype" value="1" checked="checked"/>全部备份</td>
    <td align="left">备份数据库所有表的所有数据</td>
  </tr>

  <tr>
    <td align="left"><input type="radio" name="backtype" value="2" />备份结构</td>
    <td align="left">备份数据库结构</td>
  </tr>
    <tr>
    <td align="left"><input type="radio" name="backtype" value="3" />自定义备份</td>
    <td align="left">根据自行选择备份数据表</td>
  </tr>
   <tr>
    <td colspan="2"><hr /></td>
  </tr>
  <tr>
    <td align="left">分卷备份 - 文件长度限制(kb)</td>
    <td><input type="text" name="vol_size" id="vol_size" value="<?php echo $vol_size;?>"></td>
  </tr>
  <tr>
    <td align="left">备份文件名</td>
    <td><input type="text" name="sql_file_name" id="sql_file_name" value="<?php echo $sql_name;?>"></td>
  </tr>
  <tr>
  	<td colspan="2" align="left" class="load"></td>
  </tr>
  <tr>
  	<td colspan="2" align="left"><input type="button" name="startback" class="startback" value="开始备份"></td>
  </tr>
  </table>
</div>
<?php $this->element('showdiv');?>

<?php  $thisurl = ADMIN_URL.'backdb.php'; ?>
<script type="text/javascript">
	$('.startback').click(function(){

		$('.load').html('<img src="<?php echo $this->img('loading.gif');?>"  align="absmiddle"/>正在备份，请你稍后。。。');
		vol = 1;
		senddata($(this),vol);
	});
	
	function senddata(obj,vol){
			type = $("input[name='backtype']:checked").val(); 
			obj.attr('disabled',true);
			s_f_n = $('#sql_file_name').val();
			v_z = $('#vol_size').val();
		
			$.post('<?php echo $thisurl;?>',{action:'backdb_test',type:type,sql_file_name:s_f_n,vol_size:v_z,vol:vol},function(data){ 
			if(data == ""){
				$('.black_overlay').show(200);
				$('.white_content').show(200);
				$('.load').html("");
			}else if(typeof(data)=='number'&&parseInt(data)>0){ 
				senddata(obj,data);
			}else{
				alert(data);
			}
			obj.attr('disabled',false);
			//$(this).removeAttr("disabled");
		});
	}
	
</script>