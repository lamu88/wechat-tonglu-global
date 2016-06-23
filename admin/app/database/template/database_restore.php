<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
 <table cellspacing="0" cellpadding="5" width="100%">
 <tr>
    <th colspan="5" align="left">还原备份</th>
  </tr>
  <tr>
    <th width="60"><label><input type="checkbox" class="quxuanall" value="checkbox" />移除</label></th>
    <th>文件名</th>
	<th>时间</th>
    <th>大小</th>
	<th>操作</th>
  </tr>
<?php 
if(!empty($restoredblist)/*&&$_SERVER["HTTP_HOST"]=='weixin.apiqq.com'*/){ 
foreach($restoredblist as $row){
?>
  <tr>
    <td align="left">
		<input type="checkbox" name="quanxuan" value="<?php echo $row['filedir'];?>" class="gids"/>
    </td>
    <td align="left"><a href="<?php echo SITE_URL.'/data/backup/'.$row['filename'];?>" target="_blank"><?php echo $row['filename'];?></a></td>
	<td align="left"><?php echo $row['titme'];?></td>
    <td align="left"><?php echo $row['size'];?></td>
	<td align="left"><a href="javascript:;" class="delbackdb" id="<?php echo $row['filedir'];?>">[删除]</a>&nbsp;<a href="javascript:;" class="importdb" id="<?php echo $row['filedir'];?>">[导入]</a></td>
  </tr>
 <?php 
  } 
 }
 ?>
 <tr>
 	<td colspan="5" style="text-align:left">
 	  <input type="checkbox" class="quxuanall" value="checkbox" />&nbsp;<input type="button" name="bathdel" id="bathdel" class="bathdel" value="批量删除" disabled="disabled"/>
 	</td>
 </tr>
  </table>
</form>
</div>
<?php $this->element('showdiv');?>
<?php  $thisurl = ADMIN_URL.'backdb.php'; ?>
<script type="text/javascript">
 $('.quxuanall').click(function (){
      if(this.checked==true){
         $("input[name='quanxuan']").each(function(){this.checked=true;});
		  document.getElementById("bathdel").disabled = false;
	  }else{
	     $("input[name='quanxuan']").each(function(){this.checked=false;});
		 document.getElementById("bathdel").disabled = true;
	  }
  });
  
   //是删除按钮失效或者有效
  $('.gids').click(function(){ 
  		var checked = false;
  		$("input[name='quanxuan']").each(function(){
			if(this.checked == true){
				checked = true;
			}
		}); 
		document.getElementById("bathdel").disabled = !checked;
  });
  
  $('.bathdel').click(function (){
           var arr = [];
           $('input[name="quanxuan"]:checked').each(function(){
                arr.push($(this).val());
            });
        var str=arr.join('+'); 
		$.post('<?php echo $thisurl;?>',{action:'deldb',filename:str},function(data){
			if(data == "bathdel"){
				location.reload();
			}
		});
  });
	
   $('.delbackdb').click(function(){
        var arr = [];
		if(confirm("确定删除吗！")){
			createwindow();
			arr.push($(this).attr('id'));
			var str=arr.join('+'); 
			thisobj = $(this).parent().parent();
			$.post('<?php echo $thisurl;?>',{action:'deldb',filename:str},function(data){
				removewindow();
				thisobj.hide(300);
			});
		}else{
			return false;
		}
   });
   
   $('.importdb').click(function(){
   		if(confirm("确定导入吗？将会删除已有的数据哦！")){
			createwindow();
			filename = $(this).attr('id');
			$.post('<?php echo $thisurl;?>',{action:'importdb',filename:filename},function(data){
				removewindow();
				if(data ==""){
				$('.black_overlay').show(200);
				$('.white_content').show(200);
				}
			});
		}else{
			return false;
		}
   });
   
</script>
