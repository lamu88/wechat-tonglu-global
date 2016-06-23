// JavaScript Document
function showFocus(num)
{
for(var id = 1;id<=4;id++)
{
var fpid="tab"+id;
var fnid="focusnav"+id;
if(id==num){
   try{document.getElementById(fpid).style.display="block"}catch(e){};
   try{document.getElementById(fnid).className="active"}catch(e){};
}else{
   try{document.getElementById(fpid).style.display="none"}catch(e){};
   try{document.getElementById(fnid).className=""}catch(e){};
}
} 
}
$( function(){
			$(".car_list_li").hover(
			function(){
				is = $(this).attr('id');
				$(this).children("div .cat_show").css('top',(is*120)-36);
				$(this).children("div .cat_show").show();
			},
			function(){
				$(this).children("div .cat_show").hide();
			}
			)
})