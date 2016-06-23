<style type="text/css">
	table td li{ position:relative; margin-right:10px; cursor:pointer; width:251px; height:495px; float:left;overflow:hidden; margin-bottom:10px}
	table td li p{ padding:0px; margin:0px; display:none}
	table td li .bgs{ width:200px; height:24px; line-height:24px; position:absolute; top:1px; left:1px; z-index:1;background:#ccc;filter:alpha(opacity=70); -moz-opacity:0.7; -khtml-opacity:0.7;opacity:0.7;}
	table td li .font{ text-align:center; font-size:14px; color:#FFF; width:200px;height:24px; line-height:24px; position:absolute; top:1px; left:1px; z-index:2}
</style>
<div class="contentbox">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th align="left">模板选择</th>
	</tr>
	
	 <tr>
	 	<td align="left" valign="top">
		<ul>
		<?php foreach($arr as $k=>$row){?>
			<li id="<?php echo $k;?>" style="background:url(<?php echo $row['img'];?>) center top no-repeat">
				<p class="bgs"></p>
				<p class="font"><?php echo $k==$thismubanid ? '当前选择模板' : '点击选择模版';?></p>
			</li>
		<?php } ?>
		</ul>
		</td>
	 </tr>
	 </table>
	 <?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<?php  $thisurl = ADMIN_URL.'muban.php'; ?>
<script type="text/javascript">
  $("td li").hover(
	  function () {
		$(this).find('p').show();
	  },
	  function () {
		$(this).find('p').hide();
	  }
	);
	
   	$('td li').live('click',function(){
		id = $(this).attr('id'); 
		obj = $(this);
		$.post('<?php echo $thisurl;?>',{action:'ajax_save_muban',id:id},function(data){
			$(obj).find('.font').html(data);
		});
	});
	
	
</script>