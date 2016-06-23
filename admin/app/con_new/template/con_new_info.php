<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th colspan="2" align="left"><span style="float:left"><?php echo $type=='newedit' ? '修改' : '添加';?>商家</span><span style="float:right"><a href="con_new.php?type=newlist">返回商家列表</a></span></th>
	</tr>
	 <tr>
		<td class="label" width="15%">电话:</td>
		<td><input name="author" id="author"  type="text" size="43" value="<?php echo isset($rt['author']) ? $rt['author'] : '';?>"></td>
	  </tr>
	   <tr>
		<td class="label" width="15%">旺旺:</td>
		<td><input name="wangwang" id="wangwang"  type="text" size="43" value="<?php echo isset($rt['wangwang']) ? $rt['wangwang'] : '';?>"></td>
	  </tr>
	   <tr>
		<td class="label" width="15%">微信:</td>
		<td><input name="weixin" id="weixin"  type="text" size="43" value="<?php echo isset($rt['weixin']) ? $rt['weixin'] : '';?>"></td>
	  </tr>
	<tr style="display:none">
		<td class="label" width="15%">所在分类:</td>
		<td width="85%">
		<select name="cat_id" id="cat_id">
		<?php 
		if(!empty($catids)){
		 foreach($catids as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo $row['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php echo $rows['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php echo $rowss['id']==$rt['cat_id'] ? 'selected="selected"' : '';?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
					<?php
					}//end foreach
					}//end if
				}//end foreach
		 		} // end if
		 }//end foreach
		} ?>
		</select> 
		</td>
	  </tr>
	  <tr>
		<td class="label" width="15%">名称:</td>
		<td><input name="article_title" id="article_title"  type="text" size="43" value="<?php echo isset($rt['article_title']) ? $rt['article_title'] : '';?>"><span style="color:#FF0000">*</span><span class="article_title_mes"></span></td>
	  </tr>
	 
	  <?php if(isset($rt['article_img'])&&!empty($rt['article_img'])){?>
	  <tr class="showimg">
	  	<td class="label">封面：</td>
		<td>
		<img src="../<?php echo $rt['article_img'];?>" alt="封面" width="100"/>
		</td>
	  </tr>
	  <?php } ?>
	  <tr>
		<td class="label">封面:</td>
		<td>
		  <input name="article_img" id="articlephoto" type="hidden" value="<?php echo isset($rt['article_img']) ? $rt['article_img'] : '';?>" size="43"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['article_img'])&&!empty($rt['article_img'])? 'show' : '';?>&ty=articlephoto&files=<?php echo isset($rt['article_img']) ? $rt['article_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
		</td>
	  </tr>
	 
	  <tr>
			<td class="label">地区：</td>
			<td>
			<select name="province" id="select_province" onchange="ger_ress_copy('2',this,'select_city')">
			<option value="0">选择省</option>
			<?php 
			if(!empty($rt['provinces'])){
			foreach($rt['provinces'] as $row){
			?>
			<option value="<?php echo $row['region_id'];?>" <?php echo $rt['province']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
			<?php } } ?>													
			</select>
			
			<select name="city" id="select_city" onchange="ger_ress_copy('3',this,'select_district')">
			<option value="0">选择城市</option>
			<?php
			if(!empty($rt['citys'])){
			foreach($rt['citys'] as $row){
			?>
			<option value="<?php echo $row['region_id'];?>" <?php echo $rt['city']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
			<?php } } ?>	
			</select>
			
			<select <?php echo !isset($rt['districts'])? 'style="display: none;"':"";?> name="district" id="select_district">
			<option value="0">选择区</option>	
			<?php 
			if(!empty($rt['districts'])){
			foreach($rt['districts'] as $row){
			?>
			<option value="<?php echo $row['region_id'];?>" <?php echo $rt['district']==$row['region_id']? 'selected="selected"' :"";?>><?php echo $row['region_name'];?></option>	
			<?php } } ?>													
			</select>
			 </td>
		</tr>
		<tr>
			<td class="label">详细地址：</td>
			<td>
			<input class="tx" name="address" value="<?php echo isset($rt['address'])&&!empty($rt['address']) ? $rt['address'] : "";?>" size="40" type="text" />
			 </td>
		</tr>
		<tr>
			<td class="label">导航设置：</td>
		  <td><?php $ld = isset($rt['s_ld'])&&!empty($rt['s_ld']) ? explode('|',$rt['s_ld']) : array('113.30765','23.120049');?>
			经度：<input class="tx2" name="jingdu" value="<?php echo $ld[0];?>" type="text" />&nbsp;&nbsp;
			维度：<input class="tx2" name="weidu" value="<?php echo $ld[1];?>" type="text" />
			<input type="button" value="先填写地址后获取经度维度" style="cursor:pointer; padding:3px;" onclick="searchByStationName();"/>
			</td>
		</tr>
	 <!--
	  <tr>
		<td class="label">新闻摘要:</td>
		<td><textarea name="about" id="about" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['about']) ? $rt['about'] : '';?></textarea></td>
	  </tr>-->
	  <tr>
		<td class="label">简介:</td>
		<td><textarea name="content" id="content" style="width:95%;height:500px;display:none;"><?php echo isset($rt['content']) ? $rt['content'] : '';?></textarea>
		<script>KE.show({id : 'content',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
		</td>
	  </tr>
	 <!-- <tr>
		<td class="label">Meta关键字:</td>
		<td><textarea name="meta_keys" id="meta_keys" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['meta_keys']) ? $rt['meta_keys'] : '';?></textarea></td>
	  </tr>
	  <tr>
		<td class="label">Meta描述:</td>
		<td><textarea name="meta_desc" id="meta_desc" style="width: 60%; height: 65px; overflow: auto;"><?php echo isset($rt['meta_desc']) ? $rt['meta_desc'] : '';?></textarea></td>
	  </tr>-->
	  <tr>
		<td>&nbsp;</td>
		<td align="left">
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit">
		</td>
	  </tr>
	 </table>
	 </form>
</div>

<?php  $thisurl = ADMIN_URL.'con_new.php'; ?>
<script type="text/javascript">
<!--
function ger_ress_copy(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post('user.php',{action:'get_ress',type:type,parent_id:parent_id},function(data){
		if(data!=""){
			$(obj).parent().find('#'+seobj).html(data);
			if(type==3){
				$(obj).parent().find('#'+seobj).show();
			}
			if(type==2){
				$(obj).parent().find('#select_district').hide();
				$(obj).parent().find('#select_district').html("");
			}
		}else{
			alert(data);
		}
	});
}
//jQuery(document).ready(function($){
	$('.new_save').click(function(){
		count = $('#viewcount').val();
		if(count=='undefined' || !(count > 0)){
			$('#viewcount').val('10');
		}
		art_title = $('#article_title').val();
		if(art_title=='undefined' || art_title==""){
			$('.article_title_mes').html("文章标题不能为空！");
			$('.article_title_mes').css('color','#FE0000');
			return false;
		}
		return true;
	});
//});
-->
</script>

<script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.0&services=true"></script>
<div style=" display:none;width: 1px; height: 1px;" id="container"></div>
<script type="text/javascript">
	var point = 0;
	var map = new BMap.Map("container");
	map.centerAndZoom(new BMap.Point(121.480, 31.220), 6);
	var localSearch = new BMap.LocalSearch(map, {
		renderOptions : {
			pageCapacity : 8,
			autoViewport : true,
			selectFirstResult : false
		}
	});
	localSearch.enableAutoViewport();
	function searchByStationName() {
			var pr = $('select[name="province"]').find("option:selected").text();
			var cy = $('select[name="city"]').find("option:selected").text();
			var di = $('select[name="district"]').find("option:selected").text();
			var prid = $('select[name="province"]').val();
			var cyid = $('select[name="city"]').val();
			var diid = $('select[name="district"]').val();
			
			var ad = $('input[name="address"]').val();
			var str = '';
			if(prid>0){
				str +=pr+'省';
			}
			if(cyid>0){
				str +=cy+'市';
			}
			if(diid>0){
				str +=di;
			}
			if(ad!="" && typeof(ad)!="undefined"){
				str +=ad;
			}
			
		var keyword = str;
		localSearch.setSearchCompleteCallback(function(searchResult) {
		var poi = searchResult.getPoi(0);
		//alert(poi.point.lng + "   " + poi.point.lat);
			$('input[name="jingdu"]').val(poi.point.lng);
			$('input[name="weidu"]').val(poi.point.lat);
			
			map.centerAndZoom(poi.point, 8);
		});

		localSearch.search(keyword);
	}
</script>

