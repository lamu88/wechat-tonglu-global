//创建一个等待窗口
function createwindow(){
	obj = $('.openwindow');
	if(typeof(obj)!='undefined') $(obj).remove();
	h = getScrollTop();
	var str = '<div class="openwindow"><img src="./images/loadings.gif"  align="absmiddle"/></div>';
	$("body").append(str);
	$('.openwindow').css('position','absolute');
	$('.openwindow').css('left',((screen.availWidth-750)/2)+'px');
	$('.openwindow').css('top',((screen.availHeight-200)/2)+'px');
	$('.openwindow').css('margin-top',(h-50)+'px');
	$('.openwindow').css('margin-top',(h-50)+'px');
	$('.openwindow').show();
}

//移除一个窗口
function removewindow(){
	$('.openwindow').remove();
}

//创建一个带有关闭的窗口
function meswindow(mes,title,ww,hh){
	if(ww==null || ww=="" || typeof(ww)=="undefined") ww = 300;
	if(hh==null || hh=="" || typeof(hh)=="undefined") hh = 100;
	if(title==null || title=="" || typeof(title)=="undefined") title = "头部";
	obj = $('.meswindow');
	if(typeof(obj)!='undefined') $(obj).remove();
	if(mes==""||typeof(mes)=='undefined') mes = '操作成功!';
	h = getScrollTop();   
	var str = '<div class="meswindow"><p class="p_hear"><span>'+title+'</span><a onclick="closewindow(this)" href="javascript:;">关闭</a></p>'+mes+'</div>';
	$("body").append(str);
	//$('.window_box').css('height',document.body.scrollHeight);
	//$('.window_box').css('width',document.body.scrollWidth);
	
	$('.meswindow').css('position','absolute');
	$('.meswindow').css('height',hh);
	$('.meswindow').css('width',ww);
	
	$('.meswindow').css('left',((screen.availWidth-ww)/2)+'px');
	$('.meswindow').css('top',((screen.availHeight-h)/2)+'px');
	$('.meswindow').css('margin-top',(h)+'px');
	$('.meswindow').show();
	return true;
}

/********************
 * 取窗口滚动条滚动高度  
 ******************/
function getScrollTop()
{
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
}




//关闭窗口
function closewindow(obj){
	$(obj).parent().parent().hide("slow");
	$(obj).parent().parent().remove();
}

/**
 * 获得表单单元元素
 */
function getFormAttrs(formObj)
{
  var sp_arr = new Object();

  for (i = 0; i < formObj.elements.length; i ++ )
  { 
    if(((formObj.elements[i].type == 'radio' || formObj.elements[i].type == 'checkbox') && formObj.elements[i].checked) || formObj.elements[i].tagName == 'SELECT' ||  formObj.elements[i].type == 'hidden' || formObj.elements[i].type=='text' || formObj.elements[i].type=='textarea')
    {
      sp_arr[formObj.elements[i].name] = formObj.elements[i].value;
    }
	  
  }
  return sp_arr;
}

function loadimg(obj,w,h){
	if(w==0 || typeof(w)=='undefined' || w==null){ 
		w = 160;
		h = 160;
	}
	$(obj).attr("style","");
	var sh = $(obj).height();
	var sw = $(obj).width();
	
	var hh = parseInt((w*sh)/sw);
	if(hh>h){ 
		var www = parseInt((sw*h)/sh); 
		if(www>w){
			$(obj).width(w);
			$(obj).height((sh*w)/sw);
		}else{
			$(obj).height(h);
			$(obj).width((sw*h)/sh);
		}
	}else{
		
		var hhh = parseInt((sh*w)/sw);
		if(hhh>h){
			$(obj).height(h);
			$(obj).width((sw*h)/sh);
		}else{
			$(obj).width(w);
			$(obj).height((sh*w)/sw);
		}
	}
	
	
	
	return true;
}