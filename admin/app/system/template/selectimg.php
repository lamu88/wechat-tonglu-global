<style type="text/css">
.contentbox li{ width:25%; text-align:left; line-height:26px; float:left}
</style>
<div class="contentbox" style="height:350px; overflow:hidden; overflow-y:auto">
   <table cellspacing="1" cellpadding="5" width="100%">
	 <tr>
		<th align="left">点击图标选择</th>
		<td>
		<iframe id="iframe_t" name="iframe_t" border="0" src="<?php echo SITE_URL;?>uploadajax/" scrolling="no" width="140" frameborder="0" height="36"></iframe>
		</td>
	</tr>
	</table>
	<div style="padding:0px 10px 0px 10px">
		<?php foreach($simg as $img){?>
		<p style="width:44px; text-align:center; float:left; background:#ededed"><img src="<?php echo SITE_URL.$img;?>" width="30" height="30" style="margin:5px; padding:1px; border:1px solid #ededed; margin-bottom:0px;"  onclick="seturl('<?php echo $img;?>')"/>
		<a href="javascript:;" style="color:#FF0000; text-align:center; display:block" onclick="alert('原始素材无法删除');">删除</a>
		</p>
		<?php } ?>
		<?php foreach($lists as $row){?>
		
		<p style="width:44px; text-align:center; float:left; background:#ededed"><img src="<?php echo SITE_URL.$row['img'];?>" width="30" height="30" style="margin:5px; padding:1px; border:1px solid #ededed; margin-bottom:0px;"  onclick="seturl('<?php echo $row['img'];?>')"/>
		<a href="javascript:;" style="color:#FF0000; text-align:center; display:block" onclick="return ajax_del_photos(<?php echo $row['id'];?>,this);">删除</a>
		</p>
		<?php } ?>
		<div style="clear:both"></div>
	</div>
	
	<div style="clear:both"></div>
	<?php $this->element('page',array('pagelink'=>$pagelink));?>
</div>
<script type="text/javascript">
function ajax_del_photos(id,obj){
	if(id > 0){
		if(confirm("确定删除吗")){
			$.post('<?php echo ADMIN_URL.'selectimg.php';?>',{action:'ajax_del_photos',id:id},function(data){
					$(obj).parent().remove();
			});
		}
	}
	return false;
}

function run(img){
	if(img!=""){
		$.post('<?php echo ADMIN_URL.'selectimg.php';?>',{action:'ajax_upload_img',img:img},function(data){
				window.location.href='<?php echo ADMIN_URL.'selectimg.php';?>';
		});
	}
}

function seturl(url){
	window.parent.setrunimg(url);
}
</script>
	