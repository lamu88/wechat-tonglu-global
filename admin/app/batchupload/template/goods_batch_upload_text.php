<div class="contentbox">
	<div style="padding:10px; margin:10px;background-color:#EFEFEF; border:1px solid #ccc">
			<h4 style="color:#FF0000">先在“批量传图”上传图片</h4>
			<p style="color:#FF0000">
			说明：请先把图片上传到空间，然后通过以下方式上传商品。<br />
			注意：保证路径的正确性才能上传！如果文件路径不存在，请手动创建文件夹后上传图片文件。<br />
			</p>
	</div>
	
 	<div style="padding:10px; margin:10px;background-color:#EFEFEF; border:1px solid #ccc">
		<h4>第一步：下载商品Excel文件</h4>
		<div style="height:30px; line-height:30px;width:100px; background-color:#e2e8eb; border-bottom:3px solid #bec6ce; border-right:3px solid #bec6ce; text-align:center; cursor:pointer" onclick="location.href='<?php echo ADMIN_URL;?>goods.php?type=download_tpl';return false;">马上下载</div>
	</div>
	
	<div style="padding:10px; margin:10px;background-color:#EFEFEF; border:1px solid #ccc">
		<h4>第二步：填写CSV文件</h4>
		<div style="height:30px; line-height:30px;width:400px; background-color:#e2e8eb; border-bottom:3px solid #bec6ce; border-right:3px solid #bec6ce; text-align:left; padding-left:10px">打开Excel文件，在里面对应写入上传商品的内容。</div>
	</div>
	
	<div style="padding:10px; margin:10px;background-color:#EFEFEF; border:1px solid #ccc">
		<h4>第三步：上传填写好的Excel文件</h4>
		<div style="height:60px; line-height:20px;width:500px; background-color:#e2e8eb; border-bottom:3px solid #bec6ce; border-right:3px solid #bec6ce; text-align:left; padding-left:10px"><br />
		<input name="upload_file" id="upload_file" type="hidden" value="" size="43"/>
		<iframe id="iframe_t" name="iframe_t" border="0" src="uploadfile.php?action=&ty=upload_file&tyy=excle&files=" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</div>
	</div>
		
 </div>