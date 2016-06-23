var FixedTips = function(tip, options){
	this.tip = $$(tip);//提示框
	
	this._trigger = null;//触发对象
	this._timer = null;//定时器
	this._onshow = false;//记录当前显示状态
	
	this._setOptions(options);
	//设置Tip样式
	$$D.setStyle(this.tip, {
		position: "absolute", visibility: "hidden", display: "block",
		zIndex: 99, margin: 0,//避免定位问题
		left: "-9999px", top: "-9999px"//避免占位出现滚动条
	});
	
	//offset修正参数
	var iLeft = 0, iTop = 0, p = this.tip;
	while (p.offsetParent) {
		p = p.offsetParent; iLeft += p.offsetLeft; iTop += p.offsetTop;
	};
	this._offsetleft = iLeft;
	this._offsettop = iTop;
	//移入Tip对象时保持显示状态
	$$E.addEvent(this.tip, "mouseover", $$F.bindAsEventListener(function(e){
		//如果是外部元素进入，说明当前是隐藏延时阶段，那么清除定时器取消隐藏
		this._check(e.relatedTarget) && clearTimeout(this._timer);
	}, this));
	//ie6处理select
	if ( $$B.ie6 ) {
		var iframe = document.createElement("<iframe style='position:absolute;filter:alpha(opacity=0);display:none;'>");
		document.body.insertBefore(iframe, document.body.childNodes[0]);
		this._iframe = iframe;
	};
	//用于点击方式隐藏
	this._fCH = $$F.bindAsEventListener(function(e) {
		if (this._check(e.target) && this._checkHide()) {
			this._readyHide(this._isClick(this._trigger.hideDelayType));
		};
	}, this);
	//用于触发方式隐藏
	this._fTH = $$F.bindAsEventListener(function(e) {
		if (this._check(e.relatedTarget) && this._checkHide()) {
			this._readyHide(this._isTouch(this._trigger.hideDelayType));
		};
	}, this);
};
FixedTips.prototype = {
  //设置默认属性
  _setOptions: function(options) {
	this.options = {//默认值
		showType:		"both",//显示方式
		hideType:		"both",//隐藏方式
		showDelayType:	"touch",//显示延迟方式
		hideDelayType:	"touch",//隐藏延迟方式
		//"click":只用点击方式,"touch":只用触发方式,"both":两个都使用,"none":都不使用
		showDelay:		300,//显示延时时间
		hideDelay:		300,//隐藏延时时间
		relative:		{},//定位对象
		onShow:			function(){},//显示时执行
		onHide:			function(){}//隐藏时执行
	};
	$$.extend(this.options, options || {});
  },
  //检查触发元素
  _check: function(elem) {
	//返回是否外部元素（即触发元素和Tip对象本身及其内部元素以外的元素对象）
	return !this._trigger ||
		!(
			this.tip === elem || this._trigger.Elem === elem
				|| $$D.contains(this.tip, elem) || $$D.contains(this._trigger.Elem, elem)
		);
  },
  //准备显示
  _readyShow: function(delay) {
	clearTimeout(this._timer);
	var trigger = this._trigger;
	//触发方式隐藏
	this._isTouch(trigger.hideType) && $$E.addEvent(this._trigger.Elem, "mouseout", this._fTH);
	//点击方式隐藏
	this._isClick(trigger.hideType) && $$E.addEvent(document, "click", this._fCH);
	//显示
	if (delay) {
		this._timer = setTimeout($$F.bind(this.show, this), trigger.showDelay);
	} else { this.show(); };
  },
  //显示
  show: function() {
	clearTimeout(this._timer);
	this._trigger.onShow();//放在前面方便修改属性
	//根据预设定位和自定义定位计算left和top
	var trigger = this._trigger
		,pos = RelativePosition(trigger.Elem, this.tip, trigger.relative)
		,iLeft = pos.Left, iTop = pos.Top;
	//设置位置并显示
	$$D.setStyle(this.tip, {
		left: iLeft - this._offsetleft + "px",
		top: iTop - this._offsettop + "px",
		visibility: "visible"
	});
	//ie6处理select
	if ( $$B.ie6 ) {
		$$D.setStyle(this._iframe, {
			width: this.tip.offsetWidth + "px",
			height: this.tip.offsetHeight + "px",
			left: iLeft + "px", top: iTop + "px",
			display: ""
		});
	};
	//触发方式隐藏
	this._isTouch(trigger.hideType) && $$E.addEvent(this.tip, "mouseout", this._fTH);
  },
  //准备隐藏
  _readyHide: function(delay) {
	clearTimeout(this._timer);
	if (delay) {
		this._timer = setTimeout($$F.bind(this.hide, this), this._trigger.hideDelay);
	} else { this.hide(); };
  },
  //隐藏
  hide: function() {
	clearTimeout(this._timer);
	//设置隐藏
	$$D.setStyle(this.tip, {
		visibility: "hidden", left: "-9999px", top: "-9999px"
	});
	//ie6处理select
	if ( $$B.ie6 ) { this._iframe.style.display = "none"; };
	//处理触发对象
	if (!!this._trigger) {
		this._trigger.onHide();
		$$E.removeEvent(this._trigger.Elem, "mouseout", this._fTH);
	}
	this._trigger = null;
	//移除事件
	$$E.removeEvent(this.tip, "mouseout", this._fTH);
	$$E.removeEvent(document, "click", this._fCH);
  },
  //添加触发对象
  add: function(elem, options) {
	//创建一个触发对象
	var elem = $$(elem), trigger = $$.extend( $$.extend( { Elem: elem }, this.options ), options || {} );
	//点击方式显示
	$$E.addEvent(elem, "click", $$F.bindAsEventListener(function(e){
		if ( this._isClick(trigger.showType) ) {
			if ( this._checkShow(trigger) ) {
				this._readyShow(this._isClick(trigger.showDelayType));
			} else {
				clearTimeout(this._timer);
			};
		};
	}, this));
	//触发方式显示
	$$E.addEvent(elem, "mouseover", $$F.bindAsEventListener(function(e){
		if ( this._isTouch(trigger.showType) ) {
			if (this._checkShow(trigger)) {
				this._readyShow(this._isTouch(trigger.showDelayType));
			} else if (this._check(e.relatedTarget)) {
				clearTimeout(this._timer);
			};
		};
	}, this));
	//返回触发对象
	return trigger;
  },
  //显示检查
  _checkShow: function(trigger) {
	if ( trigger !== this._trigger ) {
		//不是同一个触发对象就先执行hide防止冲突
		this.hide(); this._trigger = trigger; return true;
	} else { return false; };
  },
  //隐藏检查
  _checkHide: function() {
	if ( this.tip.style.visibility === "hidden" ) {
		//本来就是隐藏状态，不需要再执行hide
		clearTimeout(this._timer);
		$$E.removeEvent(this._trigger.Elem, "mouseout", this._fTH);
		this._trigger = null;
		$$E.removeEvent(document, "click", this._fCH);
		return false;
	} else { return true; };
  },
  //是否点击方式
  _isClick: function(type) {
	type = type.toLowerCase();
	return type === "both" || type === "click";	
  },
  //是否触发方式
  _isTouch: function(type) {
	type = type.toLowerCase();
	return type === "both" || type === "touch";	
  }
};