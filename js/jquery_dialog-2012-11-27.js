<!--
/*****************************************************************************

[作者]
coslay shop  http://www.cosplayprop.com/
Last Update:2012-10-19 00:48


*****************************************************************************/

var JqueryDialog = {
	
	//配置项
	//模态窗口背景色
	"cBackgroundColor"			:	"#ffffff",
	
	//边框尺寸(像素)
	"cBorderSize"				:	4,
	//边框颜色
	"cBorderColor"				:	"#FFDFF3",

	//Header背景色
	"cHeaderBackgroundColor"	:	"#FFDFF3",
	//右上角关闭显示文本
	"cCloseText"				:	"Close",
	//鼠标移上去时的提示文字
	"cCloseTitle"				:	"Close",
	
	//Bottom背景色
	"cBottomBackgroundColor"	:	"#fff",
	//提交按钮文本
	"cSubmitText"				:	"Submit",
	//取消按钮文本
	"cCancelText"				:	"Cancel",
	
	//拖拽效果
	"cDragTime"					:	"100",
	
	//多长时间后自动关闭对话框，默认为0
	"closeTime"					:	0,
	
	Open:function(dialogTitle, iframeSrc, iframeWidth, iframeHeight,type){
		
		//获取客户端页面宽高
		var _client_width = document.body.clientWidth;
		var _client_height = document.documentElement.scrollHeight;
		
		//create shadow
		if(typeof($("#jd_shadow")[0]) == "undefined"){
			//前置
			$("body").prepend("<div id='jd_shadow'>&nbsp;</div>");
			var _jd_shadow = $("#jd_shadow");
			_jd_shadow.css("width", _client_width + "px");
			_jd_shadow.css("height", _client_height + "px");
		}
	
		//create dialog
		if(typeof($("#jd_dialog")[0]) != "undefined"){
			$("#jd_dialog").remove();
		}
		$("body").prepend("<div id='jd_dialog'></div>");
	
		//dialog location
		//left 边框*2 阴影5
		//top 边框*2 阴影5 header30 bottom50
		var _jd_dialog = $("#jd_dialog");
		var _left = (_client_width - (iframeWidth + JqueryDialog.cBorderSize * 2 + 5)) / 2;
		_jd_dialog.css("left", (_left < 0 ? 0 : _left) + document.documentElement.scrollLeft + "px");
		
		var _top = (document.documentElement.clientHeight - (iframeHeight + JqueryDialog.cBorderSize * 2 + 30 + 50 + 5)) / 2;
		//var _top = ((screen.availHeight-h)/2)+'px';
		_jd_dialog.css("top", (_top < 0 ? 0 : _top) + JqueryDialog.getScrollTop() + "px");

		//create dialog shadow
		_jd_dialog.append("<div id='jd_dialog_s'>&nbsp;</div>");
		var _jd_dialog_s = $("#jd_dialog_s");
		//iframeWidth + double border
		_jd_dialog_s.css("width", iframeWidth + JqueryDialog.cBorderSize * 2 + "px");
		//iframeWidth + double border + header + bottom
		_jd_dialog_s.css("height", iframeHeight + JqueryDialog.cBorderSize * 2 + 10 + 50 + "px");

		//create dialog main
		_jd_dialog.append("<div id='jd_dialog_m'></div>");
		var _jd_dialog_m = $("#jd_dialog_m");
		_jd_dialog_m.css("border", JqueryDialog.cBorderColor + " " + JqueryDialog.cBorderSize + "px solid");
		_jd_dialog_m.css("width", iframeWidth + "px");
		_jd_dialog_m.css("background-color", JqueryDialog.cBackgroundColor);
	
		//header
		_jd_dialog_m.append("<div id='jd_dialog_m_h'></div>");
		var _jd_dialog_m_h = $("#jd_dialog_m_h");
		_jd_dialog_m_h.css("background-color", JqueryDialog.cHeaderBackgroundColor);
		
		//header left
		_jd_dialog_m_h.append("<span id='jd_dialog_m_h_l'>" + dialogTitle + "</span>");
		_jd_dialog_m_h.append("<span id='jd_dialog_m_h_r' title='" + JqueryDialog.cCloseTitle + "' onclick='JqueryDialog.Close();'>" + JqueryDialog.cCloseText + "</span>");

		//body
		_jd_dialog_m.append("<div id='jd_dialog_m_b'></div>");
		//iframe 遮罩层
		_jd_dialog_m.append("<div id='jd_dialog_m_b_1'>&nbsp;</div>");
		var _jd_dialog_m_b_1 = $("#jd_dialog_m_b_1");
		_jd_dialog_m_b_1.css("top", "30px");
		_jd_dialog_m_b_1.css("width", iframeWidth + "px");
		_jd_dialog_m_b_1.css("height", iframeHeight + "px");
		_jd_dialog_m_b_1.css("display", "none");
		
		//iframe 容器
		_jd_dialog_m.append("<div id='jd_dialog_m_b_2'></div>");
		//iframe
		if(type=='frame'){
			$("#jd_dialog_m_b_2").append("<iframe id='jd_iframe' src='"+iframeSrc+"' scrolling='no' frameborder='0' width='"+iframeWidth+"' height='"+iframeHeight+"' />");
		}else{
			$("#jd_dialog_m_b_2").append("<table style='font-size:12px;' align='center' height='"+iframeHeight+"' width='"+iframeWidth+"'><tr><td align='center'>"+iframeSrc+"</td></tr></table>");
		}
		//bottom
		_jd_dialog_m.append("<div id='jd_dialog_m_t' style='background-color:"+JqueryDialog.cBottomBackgroundColor+";'><input id='jd_submit' value='"+JqueryDialog.cSubmitText+"' type='button' onclick='JqueryDialog.Ok();' />&nbsp;&nbsp;<input id='jd_cancel' value='"+JqueryDialog.cCancelText+"' type='button' onclick='JqueryDialog.Close();' /></div>");
	
		//register drag
		DragAndDrop.Register(_jd_dialog[0], _jd_dialog_m_h[0]);
		
		if(JqueryDialog.closeTime>0){
			setInterval("JqueryDialog.Close()",(JqueryDialog.closeTime)*1000);	
		}
	},
	
	/********************
	 * 取窗口滚动条滚动高度  
	 ******************/
	getScrollTop:function(){
		  var scrollTop=0;
		  if(document.documentElement&&document.documentElement.scrollTop)
		  {
		  scrollTop=document.documentElement.scrollTop;
		  }
		  else if(document.body)
		  {
		  scrollTop=document.body.scrollTop;
		  }
		  return scrollTop;
	},
	
	/// <summary>关闭模态窗口</summary>
	Close:function(){
		$("#jd_shadow").remove();
		$("#jd_dialog").remove();
	},
	
	/// <summary>提交</summary>
	/// <remark></remark>
	Ok:function(){
		var frm = $("#jd_iframe");	
		if (frm[0].contentWindow.Ok()){
			JqueryDialog.Close() ;
		}
		else{
			frm[0].focus() ;
		}
	},
	
	SubmitCompleted:function(alertMsg, isCloseDialog, isRefreshPage){
		if($.trim(alertMsg).length > 0 ){
			alert(alertMsg);
		}
    	if(isCloseDialog){
			JqueryDialog.Close();
			if(isRefreshPage){
				window.location.href = window.location.href;
			}
		}
	}
};

var DragAndDrop = function(){
	
	//客户端当前屏幕尺寸(忽略滚动条)
	var _clientWidth;
	var _clientHeight;
		
	//拖拽控制区
	var _controlObj;
	//拖拽对象
	var _dragObj;
	//拖动状态
	var _flag = false;
	
	//拖拽对象的当前位置
	var _dragObjCurrentLocation;
	
	//鼠标最后位置
	var _mouseLastLocation;
	
	//使用异步的Javascript使拖拽效果更为流畅
	//var _timer;
	
	//定时移动，由_timer定时调用
	//var intervalMove = function(){
	//	$(_dragObj).css("left", _dragObjCurrentLocation.x + "px");
	//	$(_dragObj).css("top", _dragObjCurrentLocation.y + "px");
	//};
	
	var getElementDocument = function(element){
		return element.ownerDocument || element.document;
	};
	
	//鼠标按下
	var dragMouseDownHandler = function(evt){

		if(_dragObj){
			
			evt = evt || window.event;
			
			//获取客户端屏幕尺寸
			_clientWidth = document.body.clientWidth;
			_clientHeight = document.documentElement.scrollHeight;
			
			//iframe遮罩
			$("#jd_dialog_m_b_1").css("display", "");
						
			//标记
			_flag = true;
			
			//拖拽对象位置初始化
			_dragObjCurrentLocation = {
				x : $(_dragObj).offset().left,
				y : $(_dragObj).offset().top
			};
	
			//鼠标最后位置初始化
			_mouseLastLocation = {
				x : evt.screenX,
				y : evt.screenY
			};
			
			//注：mousemove与mouseup下件均针对document注册，以解决鼠标离开_controlObj时事件丢失问题
			//注册事件(鼠标移动)			
			$(document).bind("mousemove", dragMouseMoveHandler);
			//注册事件(鼠标松开)
			$(document).bind("mouseup", dragMouseUpHandler);
			
			//取消事件的默认动作
			if(evt.preventDefault)
				evt.preventDefault();
			else
				evt.returnValue = false;
			
			//开启异步移动
			//_timer = setInterval(intervalMove, 10);
		}
	};
	
	//鼠标移动
	var dragMouseMoveHandler = function(evt){
		if(_flag){

			evt = evt || window.event;
			
			//当前鼠标的x,y座标
			var _mouseCurrentLocation = {
				x : evt.screenX,
				y : evt.screenY
			};
			
			//拖拽对象座标更新(变量)
			_dragObjCurrentLocation.x = _dragObjCurrentLocation.x + (_mouseCurrentLocation.x - _mouseLastLocation.x);
			_dragObjCurrentLocation.y = _dragObjCurrentLocation.y + (_mouseCurrentLocation.y - _mouseLastLocation.y);
			
			//将鼠标最后位置赋值为当前位置
			_mouseLastLocation = _mouseCurrentLocation;
			
			//拖拽对象座标更新(位置)
			$(_dragObj).css("left", _dragObjCurrentLocation.x + "px");
			$(_dragObj).css("top", _dragObjCurrentLocation.y + "px");
			
			//取消事件的默认动作
			if(evt.preventDefault)
				evt.preventDefault();
			else
				evt.returnValue = false;
		}
	};
	
	//鼠标松开
	var dragMouseUpHandler = function(evt){
		if(_flag){
			evt = evt || window.event;
			
			//取消iframe遮罩
			$("#jd_dialog_m_b_1").css("display", "none");
			
			//注销鼠标事件(mousemove mouseup)
			cleanMouseHandlers();
			
			//标记
			_flag = false;
			
		}
	};
	
	//注销鼠标事件(mousemove mouseup)
	var cleanMouseHandlers = function(){
		if(_controlObj){
			$(_controlObj.document).unbind("mousemove");
			$(_controlObj.document).unbind("mouseup");
		}
	};
	
	return {
		//注册拖拽(参数为dom对象)
		Register : function(dragObj, controlObj){
			//赋值
			_dragObj = dragObj;
			_controlObj = controlObj;
			//注册事件(鼠标按下)
			$(_controlObj).bind("mousedown", dragMouseDownHandler);			
		}
	}

}();

//-->