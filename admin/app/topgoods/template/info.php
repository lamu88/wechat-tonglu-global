<div class="contentbox">
<style type="text/css">
.menu_content .tab{ display:none}
.nav .active{
	 /*background: url(<?php echo $this->img('manage_r2_c13.jpg');?>) no-repeat;*/
	 background-color:#F5F5F5;
} 
.nav .other{
	/* background: url(<?php echo $this->img('manage_r2_c14.jpg');?>) no-repeat;*/
	 background-color:#E9E9E9;
} 
h2.nav{ border-bottom:1px solid #B4C9C6;font-size:13px; height:25px; line-height:25px; margin-top:0px; margin-bottom:0px}
h2.nav a{ color:#999999; display:block; float:left; height:24px;width:113px; text-align:center; margin-right:1px; margin-left:1px; cursor:pointer}
.addi{ margin:0px; padding:0px;}
.vipprice td{ border-bottom:1px dotted #ccc}
.vipprice th{ background-color:#EEF2F5}
</style>
<?php $cid = (isset($_GET['cid'])&&intval($_GET['cid'])>0) ? intval($_GET['cid']) : 0; if(!($cid>0)){?>
<h2 class="nav">
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=clist">专区列表</a>  
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=cinfo">添加专区</a> 
 <a class="other" href="<?php echo ADMIN_URL;?>topgoods.php?type=lists">产品列表</a> 
 <a class="active" href="<?php echo ADMIN_URL;?>topgoods.php?type=info">添加产品</a> 
</h2>
<?php } ?>
 <div class="menu_content"<?php if($cid>0){ echo ' style="height:450px; overflow-y:scroll"';}?>>
 	<form action="" method="post" enctype="multipart/form-data" name="theForm" id="theForm">
	   <table cellspacing="2" cellpadding="5" width="100%" id="tab2">
        <tr>
          <td class="label" style="text-align:left"><img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
            <select name="cat_id2">
              <option value="0">所有分类</option>
		<?php 
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>" <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php if(isset($rt['cat_id'])&&$rt['cat_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
					<?php
					}//end foreach
					}//end if
					?>
			<?php
				}//end foreach
		 		} // end if
			?>
		<?php
		 }//end foreach
		} ?>          
			</select>
            <select name="brand_id2">
              <option value="0">所有品牌</option>
 <?php 
		if(!empty($brandlist)){
		 foreach($brandlist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>" <?php if(isset($rt['brand_id'])&&$rt['brand_id']==$row['id']){ echo 'selected="selected""'; } ?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['brand_id'])){
				foreach($row['brand_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>"  <?php if(isset($rt['brand_id'])&&$rt['brand_id']==$rows['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['brand_id'])){
					foreach($rows['brand_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>"  <?php if(isset($rt['brand_id'])&&$rt['brand_id']==$rowss['id']){ echo 'selected="selected""'; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
					<?php
					}//end foreach
					}//end if
					?>
			<?php
				}//end foreach
		 		} // end if
			?>
		<?php
		 }//end foreach
		} ?>     
			 </select>
            <input type="text" name="keyword2" value=""/>
            <input name="button" type="button" class="button" onclick="searchGoods('cat_id2', 'brand_id2', 'keyword2')"  value=" 搜索 " />          
			</td>
        </tr>
        <!-- 商品列表 -->
        <tr height="37">
          <th><?php if(empty($rt)){ echo '添加'; }else{ echo "编辑"; }?>商品</th>
        </tr>
        <tr>
          <td width="100%" valign="top">
		  		<table border="0" cellpadding="0" cellspacing="0" width="100%">
				 <tr>
				<td class="label" valign="top" style="width:100px"><a href="javascript:;" class="addgallery"><?php if(empty($rt)){ echo '[+]'; }?></a></td>
				<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="label" valign="top"><div style="width:80px">名称</div></td>
							<td><input type="text" name="photo_name[]" value="<?php echo isset($rt['gname']) ? $rt['gname'] : '';?>" style="width:280px"/>
							<em>如果留空,默认是关联后的产品标题</em>
							</td>
						</tr>
						<tr>
						<td class="label" valign="top">链接</td>
						<td> <input type="text" name="photo_url[]" value="<?php echo isset($rt['url']) ? $rt['url'] : '';?>" style="width:280px"/>
						<em>如果留空,默认是指向到产品的详情页面</em>
						</td>
						</tr>
						<tr>
						<td valign="top" class="label">图片</td>
						<td><input type="hidden" name="photo_img[]" id="goodsgallery" value=""/>
						<iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['img'])&&!empty($rt['img'])? 'show' : '';?>&ty=goodsgallery&tyy=goods&files=<?php echo isset($rt['img']) ? $rt['img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
						<em>如果留空,默认是关联后的产品图片</em>
						</td>
						</tr>
						<tr>
						<td valign="top" class="label">关联</td>
						<td>
							<select name="source_select[]" id="source_select" style="width:280px; height:24px; line-height:24px"><?php if(isset($rt['goods_name'])&&!empty($rt['goods_name'])){?><option selected="selected" value="<?php echo $rt['goods_id'];?>"><?php echo $rt['goods_name'];?></option><?php } ?></select>
							<em>必须查找一个关联产品</em>
						</td>
						</tr>
						<tr>
						<td valign="top" class="label">限量</td>
						<td><input type="text" name="gid_buy_num[]" value="<?php echo isset($rt['maxbuy_num']) ? $rt['maxbuy_num'] : '0';?>" style="width:280px"/>
						<em>每个用户ID限制购买多少件,0为不限制</em>
						</td>
						</tr>
					</table>
				</td>
				</tr>
				</table>
		  </td>
        </tr>
		<?php 
		if(!($cid)>0){
			if(!empty($rt)){
				$cid = $rt['tcid'];
			}
		}
		?>
		 <tr>
    <td class="label" style="text-align:left">
      选择分类:<select name="cat_id">
		<?php 
		if(!empty($catelist2)){
		 foreach($catelist2 as $row){ 
		 	if($type=='edit' && $rt['cat_name']==$row['name']) continue;
		?>
        <option value="<?php echo $row['id'];?>"  <?php echo $row['id']==$cid ? 'selected="selected"' : '';?>><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					if($type=='cateedit' && $rt['cat_name']==$rows['name']) continue;
					?>
					<option value="<?php echo $rows['id'];?>"  <?php echo $rows['id']==$cid ? 'selected="selected"' : '';?>>&nbsp;&nbsp;<?php echo $rows['name'];?></option>
			<?php
				}//end foreach
		 		} // end if
			?>
		<?php
		 }//end foreach
		} ?>
	 </select>&nbsp;&nbsp;&nbsp;<input value=" 马上操作 " type="Submit" style="cursor:pointer; padding:2px; color:#FF0000" onclick="return confirm('选择好产品了吗?')" />
    </td>
  </tr>
	 </table>
	 </form>
 </div> 
 
</div>

<?php  $thisurl = ADMIN_URL.'topgoods.php'; ?>
<script type="text/javascript">
//查找商品
function searchGoods(catId, brandId, keyword)
{
  var elements = document.forms['theForm'].elements;
  var filters = new Object; //以对象方式传递
  filters.cat_id = elements[catId].value;
  filters.brand_id = elements[brandId].value;
  filters.keyword = elements[keyword].value;
  
  createwindow();
  $.ajax({
	   type: "GET",
	   url: "<?php echo $thisurl;?>?type=searchGoods",
	   data: "data=" + $.toJSON(filters), //传送JSON数据到PHP页面接收
	   dataType: "json",
	   success: function(data){
		   	removewindow();
    		clearOptions("source_select");
   			var obj = document.getElementById("source_select");
			
			var opt   = document.createElement("OPTION");
		    opt.value = 0;
		    opt.text  = '选择关联商品';
		    obj.options.add(opt);
						  
			if(data.message!="" || data.message !=null){
				$.each(data.message,
				function(i, item) {
						  var opt   = document.createElement("OPTION");
						  opt.value = item.goods_id;
						  opt.text  = item.goods_name;
						  obj.options.add(opt);
				})
			}
	   }//end sucdess
  }); //end ajax

  return false;
}

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

function clearOptions(id)
{
  var obj = document.getElementById(id);
  while(obj.options.length>0)
  {
    obj.remove(0);
  }
}

function addAllItem(sender)
{
  if(sender.options.length == 0) return false;
  for (var i = 0; i < sender.options.length; i++)
  {
    var opt = sender.options[i];
    addItem(null, opt.value, opt.text);
  }
}
 
function addItem(sender, value, text)
{
  var target_select = document.getElementById("target_select");
  var newOpt   = document.createElement("OPTION");
  if (sender != null)
  {
    if(sender.options.length == 0) return false;
    var option = sender.options[sender.selectedIndex];
    newOpt.value = option.value;
    newOpt.text  = option.text;
  }
  else
  {
    newOpt.value = value;
    newOpt.text  = text;
  }
  if (targetItemExist(newOpt)) return false;
  if (target_select.length>=50)
  {
    alert("item_upper_limit");
  }
  target_select.options.add(newOpt);
  
  var key = 'default';
  
  if(!myTopic[key])
  {
    myTopic[key] = new Array();
  }
  myTopic[key].push(newOpt.text + "|" + newOpt.value);
}


// 商品是否存在
function targetItemExist(opt)
{
  var options = document.getElementById("target_select").options;
  for ( var i = 0; i < options.length; i++)
  {
    if(options[i].text == opt.text && options[i].value == opt.value) 
    {
      return true;
    }
  }
  return false;
}

function removeItem(sender,isAll)
{
  var key = 'default';
  var arr = myTopic[key];
  if (!isAll)
  {
  	if(sender.selectedIndex == -1) return false;
    var goodsName = sender.options[sender.selectedIndex].text;
    for (var j = 0; j < arr.length; j++)
    {
      if (arr[j].indexOf(goodsName) >= 0)
      {
          myTopic[key].splice(j,1);
      }
    }
 
    for (var i = 0; i < sender.options.length;)
    {
      if (sender.options[i].selected) {
        sender.remove(i);
        myTopic[key].splice(i, 0);
      }
      else
      {
        i++;
      }
    }
  }
  else
  {
    myTopic[key] = new Array();
    sender.innerHTML = "";
  }

}

</script>
	  
