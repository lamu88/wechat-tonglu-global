var RelativePosition = function(){
	function getLeft( align, rect, rel ){
		var iLeft = 0;
		switch (align.toLowerCase()) {
			case "left" :
				return rect.left - rel.offsetWidth;
			case "clientleft" :
				return rect.left;
			case "center" :
				return ( rect.left + rect.right - rel.offsetWidth ) / 2;
			case "clientright" :
				return rect.right - rel.offsetWidth;
			case "right" :
			default :
				return rect.right;
		};
	};
	function getTop( valign, rect, rel ){
		var iTop = 0;
		switch (valign.toLowerCase()) {
			case "top" :
				return rect.top - rel.offsetHeight;
			case "clienttop" :
				return rect.top;
			case "center" :
				return ( rect.top + rect.bottom - rel.offsetHeight ) / 2;
			case "clientbottom" :
				return rect.bottom - rel.offsetHeight;
			case "bottom" :
			default :
				return rect.bottom;
		};
	};
	//定位元素 相对定位元素
	return function ( fix, rel, options ) {
		//默认值
		var opt = $$.extend({
			align:			"clientleft",//水平方向定位
			vAlign:			"clienttop",//垂直方向定位
			customLeft:		0,//自定义left定位
			customTop:		0,//自定义top定位
			percentLeft:	0,//自定义left百分比定位
			percentTop:		0,//自定义top百分比定位
			adaptive:		false,//是否自适应定位
			reset:			false//自适应定位时是否重新定位
		}, options || {});
		//定义参数
		var rect = $$D.clientRect(fix)
			,iLeft = getLeft(opt.align, rect, rel) + opt.customLeft
			,iTop = getTop(opt.vAlign, rect, rel) + opt.customTop;
		//自定义百分比定位
		if (opt.percentLeft) { iLeft += .01 * opt.percentLeft * fix.offsetWidth; };
		if (opt.percentTop) { iTop += .01 * opt.percentTop * fix.offsetHeight; };
		//自适应视窗定位
		if (opt.adaptive) {
			//修正定位参数
			var doc = fix.ownerDocument
				,maxLeft = doc.clientWidth - rel.offsetWidth
				,maxTop = doc.clientHeight - rel.offsetHeight;
			if (opt.reset) {
				//自动重新定位
				if (iLeft > maxLeft || iLeft < 0) {
					iLeft = getLeft(2 * iLeft > maxLeft ? "left" : "right", rect, rel) + opt.customLeft;
				};
				if (iTop > maxTop || iTop < 0) {
					iTop = getTop(2 * iTop > maxTop ? "top" : "bottom", rect, rel) + opt.customTop;
				};
			} else {
				//修正到适合位置
				iLeft = Math.max(Math.min(iLeft, maxLeft), 0);
				iTop = Math.max(Math.min(iTop, maxTop), 0);
			};
		};
		//加上滚动条
		iLeft += $$D.getScrollLeft(fix); iTop += $$D.getScrollTop(fix);
		//返回定位参数
		return { Left: iLeft, Top: iTop };
	};
}();