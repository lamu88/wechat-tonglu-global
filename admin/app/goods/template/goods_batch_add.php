<div class="contentbox">
<style>
.fu_list {
	width:600px;
	background:#ebebeb;
	font-size:12px;
}
.fu_list td {
	padding:5px;
	line-height:20px;
	background-color:#fff;
}
.fu_list table {
	width:100%;
	border:1px solid #ebebeb;
}
.fu_list thead td {
	background-color:#f4f4f4;
}
.fu_list b {
	font-size:14px;
}
/*file容器样式*/
a.files {
	width:90px;
	height:30px;
	overflow:hidden;
	display:block;
	border:1px solid #BEBEBE;
	background:url(<?php echo $this->img('fu_btn.gif');?>) left top no-repeat;
	text-decoration:none;
}
a.files:hover {
	background-color:#FFFFEE;
	background-position:0 -30px;
}
/*file设为透明，并覆盖整个触发面*/
a.files input {
	margin-left:-350px;
	font-size:30px;
	cursor:pointer;
	filter:alpha(opacity=0);
	opacity:0;
}
/*取消点击时的虚线框*/
a.files, a.files input {
	outline:none;/*ff*/
	hide-focus:expression(this.hideFocus=true);/*ie*/
}
</style>
     <table cellspacing="2" cellpadding="5" width="100%">
	 <tr>
		<th align="left">批量添加商品</th>
	</tr>
	  <tr>
		<td>
		<font color="#FF0000">说明：</font><br />
		1.以图片文件名称作为商品的名称。<br />
		2.不能一次选择多张图片，只能一个个添加。<br />
		3.为了顺利快速上传，可以将多张图片压缩为.zip|.rar文件，然后选择上传。<br />
		3.只接受png|gif|png|rar|zip后缀的文件。
		<hr />
		</td>
	  </tr>
	  <tr>
	  <td>
<form id="uploadForm" name="uploadForm" action="<?php echo ADMIN_URL.'inc/';?>upload.php">
  <table border="0" cellspacing="2" class="fu_list">
    <thead>
      <tr>
        <td colspan="2">
		<b>选择商品分类</b>          
		 <select name="cateid">
	    <option value="0">--选择分类--</option>
		<?php 
		if(!empty($catelist)){
		 foreach($catelist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['cat_id'])){
				foreach($row['cat_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>" >&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['cat_id'])){
					foreach($rows['cat_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
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
		<b>选择商品品牌</b>
		<select name="bandid">
		<option value="0">--选择品牌--</option>
 		<?php 
		if(!empty($brandlist)){
		 foreach($brandlist as $row){ 
		?>
        <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
			<?php 
				if(!empty($row['brand_id'])){
				foreach($row['brand_id'] as $rows){ 
					?>
					<option value="<?php echo $rows['id'];?>">&nbsp;&nbsp;<?php echo $rows['name'];?></option>
					<?php 
					if(!empty($rows['brand_id'])){
					foreach($rows['brand_id'] as $rowss){ 
					?>
							<option value="<?php echo $rowss['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowss['name'];?></option>
							
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
		</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align="right" width="15%" style="line-height:35px;">添加文件：</td>
        <td><a href="javascript:void(0);" class="files" id="idFile"></a> <img id="idProcess" style="display:none;" src="<?php echo $this->img('loading.gif');?>"/></td>
      </tr>
      <tr>
        <td colspan="2"><table border="0" cellspacing="0">
            <thead>
              <tr>
                <td>文件路径</td>
                <td width="100"></td>
              </tr>
            </thead>
            <tbody id="idFileList">
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td colspan="2" style="color:gray">温馨提示：最多可同时上传 <b id="idLimit"></b> 个文件，只允许上传 <b id="idExt"></b> 文件。 </td>
      </tr>
      <tr>
        <td colspan="2" align="center" id="idMsg"><input type="button" value="开始上传" id="idBtnupload" disabled="disabled" />
          &nbsp;&nbsp;&nbsp;
          <input type="button" value="全部取消" id="idBtndel" disabled="disabled" />
        </td>
      </tr>
    </tbody>
  </table>
</form>
	  </td>
	  </tr>
	 </table>
</div>
<script type="text/javascript">
var isIE = (document.all) ? true : false;

var $ = function (id) {
    return "string" == typeof id ? document.getElementById(id) : id;
};

var Class = {
  create: function() {
    return function() {
      this.initialize.apply(this, arguments);
    }
  }
}

var Extend = function(destination, source) {
	for (var property in source) {
		destination[property] = source[property];
	}
}

var Bind = function(object, fun) {
	return function() {
		return fun.apply(object, arguments);
	}
}

var Each = function(list, fun){
	for (var i = 0, len = list.length; i < len; i++) { fun(list[i], i); }
};

//文件上传类
var FileUpload = Class.create();
FileUpload.prototype = {
  //表单对象，文件控件存放空间
  initialize: function(form, folder, options) {
	
	this.Form = $(form);//表单
	this.Folder = $(folder);//文件控件存放空间
	this.Files = [];//文件集合
	
	this.SetOptions(options);
	
	this.FileName = this.options.FileName;
	this._FrameName = this.options.FrameName;
	this.Limit = this.options.Limit;
	this.Distinct = !!this.options.Distinct;
	this.ExtIn = this.options.ExtIn;
	this.ExtOut = this.options.ExtOut;
	
	this.onIniFile = this.options.onIniFile;
	this.onEmpty = this.options.onEmpty;
	this.onNotExtIn = this.options.onNotExtIn;
	this.onExtOut = this.options.onExtOut;
	this.onLimite = this.options.onLimite;
	this.onSame = this.options.onSame;
	this.onFail = this.options.onFail;
	this.onIni = this.options.onIni;
	
	if(!this._FrameName){
		//为每个实例创建不同的iframe
		this._FrameName = "uploadFrame_" + Math.floor(Math.random() * 1000);
		//ie不能修改iframe的name
		var oFrame = isIE ? document.createElement("<iframe name=\"" + this._FrameName + "\">") : document.createElement("iframe");
		//为ff设置name
		oFrame.name = this._FrameName;
		oFrame.style.display = "none";
		//在ie文档未加载完用appendChild会报错
		document.body.insertBefore(oFrame, document.body.childNodes[0]);
	}
	
	//设置form属性，关键是target要指向iframe
	this.Form.target = this._FrameName;
	this.Form.method = "post";
	//注意ie的form没有enctype属性，要用encoding
	this.Form.encoding = "multipart/form-data";

	//整理一次
	this.Ini();
  },
  //设置默认属性
  SetOptions: function(options) {
    this.options = {//默认值
		FileName:	"Files[]",//文件上传控件的name，配合后台使用
		FrameName:	"",//iframe的name，要自定义iframe的话这里设置name
		onIniFile:	function(){},//整理文件时执行(其中参数是file对象)
		onEmpty:	function(){},//文件空值时执行
		Limit:		30,//文件数限制，0为不限制
		onLimite:	function(){},//超过文件数限制时执行
		Distinct:	true,//是否不允许相同文件
		onSame:		function(){},//有相同文件时执行
		ExtIn:		["gif","jpg","rar","zip","png"],//允许后缀名
		onNotExtIn:	function(){},//不是允许后缀名时执行
		ExtOut:		[],//禁止后缀名，当设置了ExtIn则ExtOut无效
		onExtOut:	function(){},//是禁止后缀名时执行
		onFail:		function(){},//文件不通过检测时执行(其中参数是file对象)
		onIni:		function(){}//重置时执行
    };
    Extend(this.options, options || {});
  },
  //整理空间
  Ini: function() {
	//整理文件集合
	this.Files = [];
	//整理文件空间，把有值的file放入文件集合
	Each(this.Folder.getElementsByTagName("input"), Bind(this, function(o){
		if(o.type == "file"){ o.value && this.Files.push(o); this.onIniFile(o); }
	}))
	//插入一个新的file
	var file = document.createElement("input");
	file.name = this.FileName; file.type = "file"; file.onchange = Bind(this, function(){ this.Check(file); this.Ini(); });
	this.Folder.appendChild(file);
	//执行附加程序
	this.onIni();
  },
  //检测file对象
  Check: function(file) {
	//检测变量
	var bCheck = true;
	//空值、文件数限制、后缀名、相同文件检测
	if(!file.value){
		bCheck = false; this.onEmpty();
	} else if(this.Limit && this.Files.length >= this.Limit){
		bCheck = false; this.onLimite();
	} else if(!!this.ExtIn.length && !RegExp("\.(" + this.ExtIn.join("|") + ")$", "i").test(file.value)){
		//检测是否允许后缀名
		bCheck = false; this.onNotExtIn();
	} else if(!!this.ExtOut.length && RegExp("\.(" + this.ExtOut.join("|") + ")$", "i").test(file.value)) {
		//检测是否禁止后缀名
		bCheck = false; this.onExtOut();
	} else if(!!this.Distinct) {
		Each(this.Files, function(o){ if(o.value == file.value){ bCheck = false; } })
		if(!bCheck){ this.onSame(); }
	}
	//没有通过检测
	!bCheck && this.onFail(file);
  },
  //删除指定file
  Delete: function(file) {
	//移除指定file
	this.Folder.removeChild(file); this.Ini();
  },
  //删除全部file
  Clear: function() {
	//清空文件空间
	Each(this.Files, Bind(this, function(o){ this.Folder.removeChild(o); })); this.Ini();
  }
}

var fu = new FileUpload("uploadForm", "idFile", { ExtIn: ["gif","jpg","png","rar","zip"],
	onIniFile: function(file){ file.value ? file.style.display = "none" : this.Folder.removeChild(file); },
	onEmpty: function(){ alert("请选择一个文件"); },
	onLimite: function(){ alert("超过上传限制"); },
	onSame: function(){ alert("已经有相同文件"); },
	onNotExtIn:	function(){ alert("只允许上传" + this.ExtIn.join("，") + "文件"); },
	onFail: function(file){ this.Folder.removeChild(file); },
	onIni: function(){
		//显示文件列表
		var arrRows = [];
		if(this.Files.length){
			var oThis = this;
			Each(this.Files, function(o){
				var a = document.createElement("a"); a.innerHTML = "取消"; a.href = "javascript:void(0);";
				a.onclick = function(){ oThis.Delete(o); return false; };
				arrRows.push([o.value, a]);
			});
		} else { arrRows.push(["<font color='gray'>没有添加文件</font>", "&nbsp;"]); }
		AddList(arrRows);
		//设置按钮
		$("idBtnupload").disabled = $("idBtndel").disabled = this.Files.length <= 0;
	}
});

$("idBtnupload").onclick = function(){
	//显示文件列表
	var arrRows = [];
	Each(fu.Files, function(o){ arrRows.push([o.value, "&nbsp;"]); });
	AddList(arrRows);
	
	fu.Folder.style.display = "none";
	$("idProcess").style.display = "";
	$("idMsg").innerHTML = "正在添加文件到您的网盘中，请耐心等待……<br />有可能因为网络问题，出现程序长时间无响应，请点击“<a href='<?php echo ADMIN_URL.'goods.php?type=batch_add';?>'><font color='red'>取消</font></a>”重新上传文件";
	
	fu.Form.submit();
}

//用来添加文件列表的函数
function AddList(rows){
	//根据数组来添加列表
	var FileList = $("idFileList"), oFragment = document.createDocumentFragment();
	//用文档碎片保存列表
	Each(rows, function(cells){
		var row = document.createElement("tr");
		Each(cells, function(o){
			var cell = document.createElement("td");
			if(typeof o == "string"){ cell.innerHTML = o; }else{ cell.appendChild(o); }
			row.appendChild(cell);
		});
		oFragment.appendChild(row);
	})
	//ie的table不支持innerHTML所以这样清空table
	while(FileList.hasChildNodes()){ FileList.removeChild(FileList.firstChild); }
	FileList.appendChild(oFragment);
}


$("idLimit").innerHTML = fu.Limit;

$("idExt").innerHTML = fu.ExtIn.join("，");

$("idBtndel").onclick = function(){ fu.Clear(); }

//cids =document.uploadForm.cateid.value; //获取选择的分类I

//bids =document.uploadForm.bandid.value;;  // 选择的品牌ID

//在后台通过window.parent来访问主页面的函数
function Finish(msg,tt){ if(msg !=""){ alert(msg); }; ar = tt.split("+"); location.href = '<?php echo ADMIN_URL.'goods.php?type=batch_add&op=cachelist';?>'+'&cid='+ar[0]+'&bid='+ar[1]; }

</script>
