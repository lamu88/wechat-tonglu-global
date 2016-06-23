<div class="contentbox">
<div style="margin-left:10px;">
    <form id="form1" name="form1" method="post" action="">
	 <table cellspacing="2" cellpadding="5" width="100%">
	 	<tr>
		<td colspan="3">
		<strong>上传目录图片：</strong>
		</td>
		</tr>
		<?php 
		if(!empty($freecatalog_ptoto)){
		foreach($freecatalog_ptoto as $row){
		?>
		<tr>
		<td><?php echo $row['photoname'];?></td>
		<td colspan="2">
		<input type="hidden" name="photo_desc[]" value="<?php echo $row['photoname'];?>"/>
		<input type="hidden" name="photo_url[]" id="goods<?php echo str_replace(array('/','.'),"",$row['photourl']);?>" value="<?php echo $row['photourl'];?>"/>
		<br />
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=show&ty=goods<?php echo str_replace(array('/','.'),"",$row['photourl']);?>&files=<?php echo $row['photourl'];?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	   </tr>
	  <?php } } ?>
		<tr>
		<td align="left" valign="middle" width="220"><a href="javascript:;" class="addgallery">[+]</a>商品描述:
		  <input type="text" name="photo_desc[]" value=""/>
		  </td>
		<td>上传图片：</td>
		<td align="left">
		<input type="hidden" name="photo_url[]" id="goodsgallery" value=""/>
		<iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=&ty=goodsgallery&tyy=catalog&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	 	</tr>
	</table>	
	<p style="border-bottom:1px solid #ededed;">在下面的文本框输入索取的目录名称</p>
	<label>
	<textarea name="keys" id="keys" cols="40" rows="5" style="text-indent:0px"><?php echo isset($freecatalog)&&!empty($freecatalog)?trim(implode(",",$freecatalog)):"";?>
	</textarea>
	</label>
	<br />
	<em>提示：多个名称用“，”分隔开</em>
	<br />
	<font class="return_mes" style="color:#FF0000"></font>
	<br />
	<input type="Submit" name="Submit" value="提交保存" />
	</form>
</div>
</div>
<script language="javascript" type="text/javascript">
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
</script>
