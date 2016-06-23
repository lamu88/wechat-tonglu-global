<div class="contentbox">
 <table cellspacing="0" cellpadding="5" width="100%" bgcolor="#fafafa">
 <tr>
    <th colspan="7" align="left">
	<!-- start form -->
	总碎片数:<?php echo $num;?>
	<input type="submit" value="开始进行数据表优化" class="startyouhua" />
	<input type= "hidden" name= "num" class="nums" value = "<?php echo $num;?>">
	<!-- end form -->
	</th>
  </tr>
  <tr>
    <th>数据表</th>
    <th>数据表类型</th>
	<th>记录数</th>
    <th>数据</th>
	<th>碎片</th>
	<th>字符集</th>
	<th>状态</th>
  </tr>
<?php 
if($list){ 
foreach($list as $row){
?>
  <tr>
    <td align="left"><?php echo $row['table'];?></td>
    <td align="left"><?php echo $row['type'];?></td>
	<td align="left"><?php echo $row['rec_num'];?></td>
    <td align="left"><?php echo $row['rec_size'];?></td>
	<td align="left"><?php echo $row['rec_chip'];?></td>
	<td align="left"><?php echo $row['charset'];?></td>
	<td align="left"><?php echo $row['status'];?></td>
  </tr>
 <?php 
  } 
 }
 ?>
  </table>
</div>

<?php  $thisurl = ADMIN_URL.'backdb.php'; ?>
<script type="text/javascript">
  $('.startyouhua').click(function (){ 
		numb = $(".nums").val(); 
		createwindow();
		$.post('<?php echo $thisurl;?>',{action:'youhuadb'},function(data){
			removewindow();
			if(data == ""){
				alert("一共清理了"+numb+"KB");
				location.reload();
			}
		});
		
  });
   
</script>
