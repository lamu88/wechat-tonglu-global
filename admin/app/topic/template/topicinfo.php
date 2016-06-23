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
h2.nav{ border-bottom:1px solid #B4C9C6;font-size:13px; height:25px; line-height:25px; margin-top:0px; padding-top:10px;}
h2.nav a{ color:#999999; display:block; float:left; height:24px;width:113px; text-align:center; margin-right:1px; margin-left:1px; cursor:pointer}
.addi{ margin:0px; padding:0px;}
</style>
<form action="" method="post" enctype="multipart/form-data" name="theForm" id="theForm">
 <h2 class="nav">
 <a class="active" onclick="show_hide('1'); return false;">通用属性</a>  
 <a class="other" onclick="show_hide('2'); return false;">专题商品</a>  
 <a class="other" onclick="show_hide('3'); return false;">专题介绍</a> 
 <span style="float:right"><a href="<?php echo ADMIN_URL;?>topic.php?type=list">返回专题列表</a></span>
</h2>

 <div class="menu_content">
 	<!--start 通用信息-->
	 <table cellspacing="2" cellpadding="5" width="100%" id="tab1">
		 <tr>
          <td class="label" width="150">专题名称</td>
          <td><input name="topic_name" type="text" value="<?php echo isset($rt['topic_name']) ? $rt['topic_name'] : '';?>" size="50" /></td>
        </tr>
        <tr>
          <td class="label">专题页面关键字</td>
          <td><textarea name="meta_keys" id="meta_keys" cols="40" rows="3"><?php echo isset($rt['meta_keys']) ? $rt['meta_keys'] : '';?></textarea></td>
        </tr>
        <tr>
          <td class="label">专题页面描述</td>
          <td><textarea name="meta_desc" id="meta_desc" cols="40" rows="5"><?php echo isset($rt['meta_desc']) ? $rt['meta_desc'] : '';?></textarea></td>
        </tr>
        <tr>
          <td class="label">图片类型</td>
          <td>
		    <select name="topic_type" id="topic_type" onchange="showMedia(this.value)">
       		<option value='0'<?php echo (!isset($rt['topic_type']) || empty($rt['topic_type'])) ? ' selected="selected"' : "";?>>图片</option>
       		<option value='1'<?php echo (isset($rt['topic_type'])&&$rt['topic_type']=='1') ? ' selected="selected"' : "";?>>Flash</option>
       		<option value='2'<?php echo (isset($rt['topic_type'])&&$rt['topic_type']=='2') ? ' selected="selected"' : "";?>>代码</option>
      	 	</select>
	   </td>
       </tr>
          <tr id="con_5">
            <td class="label">顶部图片上传</td>
            <td>
			 <input name="topic_img" id="topic_img" type="hidden" value="<?php echo isset($rt['ad_img']) ? $rt['ad_img'] : '';?>" size="40"/>
		  	 <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['topic_img'])&&!empty($rt['topic_img'])? 'show' : '';?>&ty=topic_img&tyy=topic&files=<?php echo isset($rt['topic_img']) ? $rt['topic_img'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe><span class="uploadfiles_mes"></span> 
			  </td>
          </tr>
		  <tr id="con_4" style="display:none">
            <td  class="label">FLASH上传</td>
            <td>
			 <input name="topic_flash" id="topic_flash" type="hidden" value="<?php echo isset($rt['topic_flash']) ? $rt['topic_flash'] : '';?>" size="40"/>
		  <iframe id="iframe_t" name="iframe_t" border="0" src="uploadfile.php?action=<?php echo isset($rt['ad_file'])&&!empty($rt['ad_file'])? 'show' : '';?>&ty=topic_flash&tyy=topic&files=<?php echo isset($rt['topic_flash']) ? $rt['topic_flash'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe>
			  </td>
          </tr>
<!--          <tr id="con_6">
            <td class="label">或者远程URL地址</td>
            <td><input type="text" name="topic_imgurl" id="url" value="<?php echo isset($rt['topic_imgurl']) ? $rt['topic_imgurl'] : 'http://';?>" size="50" /><em>必须加上http://</em></td>
          </tr>-->
         <tr>
            <td class="label">背景图片</td>
            <td>
			 <input name="topic_bgimg" id="topic_bgimg" type="hidden" value="<?php echo isset($rt['topic_bgimg']) ? $rt['topic_bgimg'] : '';?>" size="40"/>
		  	 <iframe id="iframe_t" name="iframe_t" border="0" src="upload.php?action=<?php echo isset($rt['topic_bgimg'])&&!empty($rt['topic_bgimg'])? 'show' : '';?>&ty=topic_bgimg&tyy=topic&files=<?php echo isset($rt['topic_bgimg']) ? $rt['topic_bgimg'] : '';?>" scrolling="no" width="445" frameborder="0" height="25"></iframe><span class="uploadfiles_mes"></span> <br/><em>默认留空</em>
			  </td>
          </tr>
		  <tr>
            <td class="label">或者背景颜色</td>
            <td>
			 <input name="topic_bgcolor" class="color" id="topic_bgcolor" type="text" value="<?php echo isset($rt['topic_bgcolor']) ? $rt['topic_bgcolor'] : 'FFFFFF';?>" size="40"/>
			 <br/><em>默认留空-点击文本框选择颜色</em>
			  </td>
          </tr>
		  
        <tbody id="con_2" style="display:none">
          <tr>
            <td class="label">链接</td>
            <td><input type="text" name="top_url" id="top_url" value="<?php echo isset($rt['top_url']) ? $rt['top_url'] : 'http://';?>" size="50"/></td>
          </tr>
        </tbody>
 
        <tbody id="con_3" style="display:none">
          <tr>
            <td class="label">内容</td>
            <td><textarea name="topic_imgcode" id="topic_imgcode" cols="50" rows="7"><?php echo isset($rt['topic_imgcode']) ? $rt['topic_imgcode'] : '';?></textarea></td>
          </tr>
        </tbody>
		<tr>
		  <td class="label">活动日期:</td>
		  <td>
		    <input type="text" name="start_time" id="dff" value="<?php echo !empty($rt['start_time']) ? date('Y-m-d',$rt['start_time']) : date('Y-m-d',mktime());?>" onClick="WdatePicker()"/>&nbsp;-&nbsp;
			<input type="text" name="end_time" id="dtt" value="<?php echo !empty($rt['end_time']) ? date('Y-m-d',$rt['end_time']) : date('Y-m-d',mktime()+14*24*3600);?>" onClick="WdatePicker()"/>&nbsp;<em>点击文本选择日期。</em>
		  </td>
		  </tr>
	 </table>
	 <!--end 通用信息-->
	 
	 <!--start 专题商品-->
	 <table cellspacing="2" cellpadding="5" width="100%" id="tab2" class="tab">
        <tr>
          <td colspan="4" class="label" style="text-align:left">专题分类          
		    <select name="topic_class_list" id="topic_class_list" onchange="showTargetList()">
            </select>
            <input name="new_cat_name" type="text" id="new_cat_name" />
            <input name="create_class_btn" type="button" id="create_class_btn" value="添加" class="button" onclick="addClass()" />
            <input name="delete_class_btn" type="button" id="delete_class_btn" value="移除" class="button" onclick="deleteClass()" />          
			</td>
        </tr>
        <tr>
          <td colspan="3"><img src="<?php echo $this->img('icon_search.gif');?>" alt="SEARCH" width="26" border="0" height="22" align="absmiddle">
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
          <th>可选商品</th>
          <th>操作</th>
          <th>已选商品</th>
        </tr>
        <tr>
          <td width="42%"><select name="source_select" id="source_select" size="20" style="width:100%;height:300px;"  ondblclick="addItem(this)">
            </select>          </td>
          <td align="center">
		    <p>
              <input name="button" type="button" class="button" onclick="addAllItem(document.getElementById('source_select'))" value="&gt;&gt;" />
            </p>
            <p>
              <input name="button" type="button" class="button" onclick="addItem(document.getElementById('source_select'))" value="&gt;" />
            </p>
            <p>
              <input name="button" type="button" class="button" onclick="removeItem(document.getElementById('target_select'))" value="&lt;" />
            </p>
            <p>
              <input name="button" type="button" class="button" value="&lt;&lt;" onclick="removeItem(document.getElementById('target_select'), true)" />
            </p></td>
          <td width="42%"><select name="target_select" id="target_select" size="20" style="width:100%;height:300px" multiple="multiple">
            </select>          </td>
        </tr>
	 </table>
	 <!--end 专题商品-->
	 
	 <!--start 专题介绍-->
	 <table cellspacing="2" cellpadding="5" width="100%" id="tab3" class="tab">
	 <tr>
		<td class="label" width="150">详情内容:</td>
		<td>
		<textarea name="topic_desc" id="topic_desc" style="width:95%;height:500px;display:none;"><?php echo isset($rt['topic_desc']) ? $rt['topic_desc'] : '';?></textarea>
		<script>KE.show({id : 'topic_desc',cssPath : '<?php echo ADMIN_URL.'/css/edit.css';?>'});</script>
		</td>
	  </tr>
	  </table>
	  <!--end 商品属性-->
	  
	  <p style="text-align:center">
	    <input  name="topic_data" type="hidden" id="topic_data" value='' />
		<input class="new_save" value="<?php echo $type=='newedit' ? '修改' : '添加';?>保存" type="Submit" onclick="return checkForm()"/>
	  </p>
 </div> 
  </form>
</div>

<?php  $thisurl = ADMIN_URL.'topic.php'; ?>
<script type="text/javascript">
<!--
var move_item_confirm = "已选商品已经转移到\"className\"分类下";

var data = '<?php echo isset($rt['goods_ids']) ? $rt['goods_ids'] : "";?>';
var defaultClass = "无分类";
 
var myTopic = Object();
var status_code = "0"; // 初始页面参数
 
onload = function()
{
  var classList = document.getElementById("topic_class_list");
 
  // 初始化表单项
  initialize_form(status_code);
 
  if (data == "")
  {
    
    classList.innerHTML = "";
    myTopic['default'] = new Array();
    var newOpt    = document.createElement("OPTION");
    newOpt.value  = -1;
    newOpt.text   = defaultClass;
    classList.options.add(newOpt);
    return;
  }
  var temp    = $.parseJSON(data);  //jquery把json格式转化为对象类型

  var counter = 0;
  for (var k in temp)
  {
    if(typeof(myTopic[k]) != "function")
    {
      myTopic[k] = temp[k];
      var newOpt    = document.createElement("OPTION");
      newOpt.value  = k == "default" ? -1 : counter;
      newOpt.text   = k == "default" ? defaultClass : k;
      classList.options.add(newOpt);
      counter++;
    }
  }
  showTargetList();
}

function addItem(sender, value, text)
{
  var target_select = document.getElementById("target_select");
  var sortList = document.getElementById("topic_class_list");
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
  var key = sortList.options[sortList.selectedIndex].value == "-1" ? "default" : sortList.options[sortList.selectedIndex].text;
  
  if(!myTopic[key])
  {
    myTopic[key] = new Array();
  }
  myTopic[key].push(newOpt.text + "|" + newOpt.value);
}

function addClass()
{ 
  var obj = document.getElementById("topic_class_list");
  var newClassName = document.getElementById("new_cat_name");
  var regExp = /^[a-zA-Z0-9]+$/;
  if (newClassName.value == ""){
    alert("请输入分类名称");
    return;
  }
  for(var i=0;i < obj.options.length; i++)
  {
    if(obj.options[i].text == newClassName.value)
    {
      alert("该分类已经存在");
      newClassName.focus(); 
      return;
    }
  }
  var className = document.getElementById("new_cat_name").value;
  document.getElementById("new_cat_name").value = "";
  var newOpt    = document.createElement("OPTION");
  newOpt.value  = obj.options.length;
  newOpt.text   = className;
  obj.options.add(newOpt);
  newOpt.selected = true;
  if ( obj.options[0].value == "-1")
  {
    if (myTopic["default"].length > 0)
      alert(move_item_confirm.replace("className",className));
    myTopic[className] = myTopic["default"];
    delete myTopic["default"];
    obj.remove(0);
  }
  else
  { 
    myTopic[className] = new Array();
    clearOptions("target_select");
  }
}
 
function deleteClass()
{
  var classList = document.getElementById("topic_class_list");
  if (classList.value != "-1")
  {
    delete myTopic[classList.options[classList.selectedIndex].text];
    classList.remove(classList.selectedIndex);
    clearOptions("target_select");
  }
  if (classList.options.length < 1)
  {
    var newOpt    = document.createElement("OPTION");
    newOpt.value  = "-1";
    newOpt.text   = defaultClass;
    classList.options.add(newOpt);
    myTopic["default"] = new Array();
  }
}


function showTargetList()
{
  clearOptions("target_select");
  var obj = document.getElementById("topic_class_list");
  var index = obj.options[obj.selectedIndex].text;  //选中的文本值
  if (index == defaultClass)
  {
    index = "default";
  }
  var options = myTopic[index];
  for ( var i = 0; i < options.length; i++)
  {
    var newOpt    = document.createElement("OPTION");
    var arr = options[i].split('|');
    newOpt.value  = arr[1];
    newOpt.text   = arr[0];
    document.getElementById("target_select").options.add(newOpt);
  }
}


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
  var sortList = document.getElementById("topic_class_list");
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
  var key = sortList.options[sortList.selectedIndex].value == "-1" ? "default" : sortList.options[sortList.selectedIndex].text;
  
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
  var classList = document.getElementById("topic_class_list");
  var key = 'default';
  if (classList.value != "-1")
  {
    key = classList.options[classList.selectedIndex].text;
  }
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

function checkForm()
{
  document.getElementById("topic_data").value = $.toJSON(myTopic);  //解释为JSON格式
 
}



function show_hide(id){
	len = $('.nav a').length;
	if(len>1){
		for(i=1;i<=len;i++){
			if(i==id) { 
				$($('.nav a')[i-1]).removeClass();
				$($('.nav a')[i-1]).addClass('active');
				$("#tab"+id).css('display','block');
			}else{
				$($('.nav a')[i-1]).removeClass();
				$($('.nav a')[i-1]).attr('class',"other");
				$("#tab"+i).css('display','none');
			}
		}
	}
}

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

/**
 * 初始化表单项目
 */
function initialize_form(status_code)
{
  var nt = navigator_type();
  var display_yes = (nt == 'IE') ? 'block' : 'table-row-group';
  var display_yes2 = (nt == 'IE') ? '1' : '2';
  status_code = parseInt(status_code);
  status_code = status_code ? status_code : 0;
  document.getElementById('topic_type').options[status_code].selected = true;
 
  switch (status_code)
  {
    case 0 :  //图片
	  //document.getElementById('con_2').style.display = display_yes;
	  //document.getElementById('con_5').style.display = display_yes;
	  //document.getElementById('con_6').style.display = display_yes;
	  //document.getElementById('con_3').style.display = 'none';
	  //document.getElementById('con_4').style.display = 'none';

	  $('#con_2').show();
	  $('#con_5').show();
	  $('#con_6').show();
	  $('#con_3').hide();
	  $('#con_4').hide();
    break;
		
    case 1 :
/*      document.getElementById('con_2').style.display = display_yes;
	  document.getElementById('con_4').style.display = display_yes;
	  document.getElementById('con_6').style.display = display_yes;
	  document.getElementById('con_3').style.display = 'none';
	  document.getElementById('con_5').style.display = 'none';*/
	  	$('#con_2').show();
		$('#con_4').show();
		$('#con_6').show();
		$('#con_3').hide();
		$('#con_5').hide();
    break;
		
    case 2 :
/*      document.getElementById('con_3').style.display = display_yes;
	  document.getElementById('con_4').style.display = 'none';
	  document.getElementById('con_6').style.display = 'none';
	  document.getElementById('con_2').style.display = 'none';
	  document.getElementById('con_5').style.display = 'none';*/
	  	$('#con_3').show();
		$('#con_4').hide();
		$('#con_6').hide();
		$('#con_2').hide();
		$('#con_5').hide();
    break;
  }
 
	
  return true;
}
 
/**
 * 类型表单项切换
 */
function showMedia(code)
{
  var obj = document.getElementById('topic_type');
 
  initialize_form(code);
}

/**
 * 判断当前浏览器类型
 */
function navigator_type()
{
  var type_name = '';
 
  if (navigator.userAgent.indexOf('MSIE') != -1)
  {
    type_name = 'IE'; // IE
  }
  else if(navigator.userAgent.indexOf('Firefox') != -1)
  {
    type_name = 'FF'; // FF
  }
  else if(navigator.userAgent.indexOf('Opera') != -1)
  {
    type_name = 'Opera'; // Opera
  }
  else if(navigator.userAgent.indexOf('Safari') != -1)
  {
    type_name = 'Safari'; // Safari
  }
  else if(navigator.userAgent.indexOf('Chrome') != -1)
  {
    type_name = 'Chrome'; // Chrome
  }
 
  return type_name;
}

-->
</script>
