//删除购物商品
$('.delcartid').live('click',function(){
	if(confirm('您确实要把该商品移出购物车吗？')){
		ids = $(this).attr('id');
		thisobj = $(this);
		createwindow();
		$.post(SITE_URL+'ajaxfile/mycart.php',{action:'delcartid',id:ids},function(data){
				removewindow();
				if($(thisobj).attr("rel")=='mycarttop'){
					$(thisobj).parent().parent().fadeOut();
				}else{
					$('.MYCART').hide();
					if(data !=""){
						$('.MYCART').html(data);
						$('.MYCART').fadeIn("slow");
					}
				}
		});
		return true;
	}else{
		return false;
	}
});

//改变商品价格
function change_number(obj){
	price = $(obj).attr('lang');
	id = $(obj).attr('id');
	numbers = $(obj).val();
	if(!(numbers>0)){
	 	numbers = 1;
	 	$(obj).val('1');
	}
	createwindow();
	$.post(SITE_URL+'ajaxfile/mycart.php',{action:'changeprice',id:id,number:numbers},function(data){ 
		removewindow();
		if(data.error==0){
			obj.parent().parent().parent().parent().parent().parent().find('.raturnprice').html('￥'+(price*numbers).toFixed(2));
			if(typeof($('.totalprice'))=='object'){ $('.totalprice').html(data.message);}
			if(typeof($('.offmoney'))=='object'){ $('.offmoney').html(data.offprice);}
			if(typeof($('.shippingprice'))=='object'){ $('.shippingprice').html(data.shippingprice);}
		}else if(data.error==1){
			$('.MYCART').hide();
			$('.MYCART').html(data.message);
			$('.MYCART').fadeIn("slow");
		}
	}, "json");
	return true;
}

//数量减1
$('.jian').live('click',function(){
	ob = $(this).parent().parent().parent();
	numobj = ob.find('input[name="goods_number"]');
	vall = $(numobj).val();
	if(!(vall>0)){
		ob.val('1');
		return false;
	}
	if(vall>1){
		$(numobj).val((parseInt(vall)-1));
	}
	change_number(numobj);
});
//数量加1
$('.jia').live('click',function(){
	ob = $(this).parent().parent().parent();
	numobj = ob.find('input[name="goods_number"]');
	vall = $(numobj).val();
	if(!(vall>0)){
		$(ob).val('1');
		return false;
	}
	$(numobj).val((parseInt(vall)+1));
	change_number(numobj);
});

//改变商品价格
$('input[name="goods_number"]').live('blur',function(){
	change_number($(this));
});

function m(thisObj,Num){
	if(thisObj.className == "active")return;
	var tabObj = thisObj.parentNode.id;
	var tabList = document.getElementById(tabObj).getElementsByTagName("li");
	for(i=0; i <tabList.length; i++)
	{
	  if (i == Num)
	  {
	   thisObj.className = "a"; //active
		  document.getElementById(tabObj+""+i).style.display = "block";
	  }else{
	   tabList[i].className = "n"; //normal
	   document.getElementById(tabObj+""+i).style.display = "none";
	  }
	} 
}

  function show_hide_cart(tt){
   		if(tt=='hide'){
			$.cookie('GZ_HIDE','');
			$('.floatTips').width('32px');
			$('#floatTips .mycartshowbox').hide();
			$('#floatTips .mycarthidebox').show();
		}else{
			$('.floatTips').width('300px');
			$('#floatTips .mycarthidebox').hide();
			$('#floatTips .mycartshowbox').show();
			$.cookie('GZ_HIDE','GZ_HIDE');
		}
   }