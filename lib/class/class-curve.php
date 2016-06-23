<?php
class Curve{
/******************************
*      折线图生成函数
* eg:
参数说明：
$value_y -------- 包含你想生成折线图的数组，要求键值从0开始递增。
$width--------生成的折线图的网格宽度（不算白边）
$high--------高度
$strong-------线条粗细（默认为1）
$fix-------数据保留的位数（默认为取整）

for($i=0;$i<100;$i++){
    $value = rand(1,200);
    $value_y[]=$value;
}
line_stats_pic($value_y,500,100,1,1); 

******************************/
function line_stats_pic($value_y,$width,$high,$strong=1,$fix=0)
{
	//y值处理函数
		function line_point_y($num,$width,$high,$max_num_add,$min_num_add,$y_pxdensity){
		  $return=$high-floor(($num-$min_num_add+$y_pxdensity)/(($max_num_add-$min_num_add)/$high));
		  return $return;
		}
	
	//参数处理
	$allnum=sizeof($value_y);
	$max_num=max($value_y);              //最大值
	$min_num=min($value_y);              //最小值
	$limit_m=$max_num-$min_num;            //极差
	$max_num_add=$max_num+$limit_m*0.1;        //轴最大值
	$min_num_add=$min_num-$limit_m*0.1;        //轴最小值
	$limit=$max_num_add-$min_num_add;         //极差-坐标轴y
	$y_pxdensity=($max_num_add-$min_num_add)/$high;  //y轴密度
	$x_pxdensity=floor($width/$allnum);        //x轴密度
	reset($value_y);                 //将数组指针归零
	$i=0;
	foreach($value_y as $val){
	  $point_y[$i]=line_point_y($val,$width,$high,$max_num_add,$min_num_add,$y_pxdensity);
	  $i++;
	}
	$zero_y=line_point_y(0,$width,$high,$max_num_add,$min_num_add,$y_pxdensity);  //零点的y值
	$empty_size_x=(strlen($max_num) > strlen($min_num) ? strlen($max_num) : strlen($min_num))*5+3;          //左边空白
	
	//图片流开始
	header("Content-type:image/png");
	$pic=imagecreate($width+$empty_size_x+10,$high+13);
	imagecolorallocate($pic,255,255,255);     //背景色
	$color_1=imagecolorallocate($pic,30,144,255); //线条色
	$color_2=imagecolorallocate($pic,0,0,0);   //黑色
	$color_3=imagecolorallocate($pic,194,194,194);//灰色
	//绘制网格
	imagesetthickness($pic,1);          //网格线宽
	$y_line_width=floor($width/100);       //纵网格线数目
	$y_line_density=$y_line_width==0 ? 0 :floor($width/$y_line_width); //纵网格线密度
	$point_zero_y=$zero_y > $high ? $high : $zero_y;
	imagestring($pic,1,$empty_size_x-1,$high+4,"0",$color_2); //零点数轴标记
	for($i=1;$i <= $y_line_width;$i++){      //绘制纵网格线
	  imagesetthickness($pic,1);         //网格线宽
	  imageline($pic,$y_line_density*$i+$empty_size_x,0,$y_line_density*$i+$empty_size_x,$high,$color_3);
	  imagesetthickness($pic,2);         //轴点线宽
	  imageline($pic,$y_line_density*$i+$empty_size_x,$point_zero_y-4,$y_line_density*$i+$empty_size_x,$point_zero_y,$color_2);
	  imagestring($pic,1,100*$i+$empty_size_x-5,$high+4,$allnum/$y_line_width*$i,$color_2);  //数轴标记
	}
	$x_line_width=floor($high/30);        //横网格线数目
	$x_line_density=$x_line_width==0 ? 0 :floor($high/$y_line_width);  //横网格线密度
	if($zero_y > $high){             //绘制横网格线
	  imagestring($pic,1,0,$high-3,round($min_num_add,$fix),$color_2); //零点数轴标记
	  for($i=1;$i <= $x_line_width;$i++){
	   imagesetthickness($pic,1);        //网格线宽
	   imageline($pic,0+$empty_size_x,$high-$x_line_density*$i,$width+$empty_size_x,$high-$x_line_density*$i,$color_3);
	   imagesetthickness($pic,2);        //轴点线宽
	   imageline($pic,0+$empty_size_x,$high-$x_line_density*$i,3+$empty_size_x,$high-$x_line_density*$i,$color_2);
	   imagestring($pic,1,0,$high-$x_line_density*$i-3,round($limit/$x_line_width*$i+$min_num_add,$fix),$color_2);  //数轴标记
	  }
	}else{
	  imagestring($pic,1,$empty_size_x-8,$zero_y,"0",$color_2); //零点数轴标记
	  for($i=1;$i <= ceil($x_line_width/2);$i++){
	   imagesetthickness($pic,1);        //网格线宽
	   imageline($pic,0+$empty_size_x,$zero_y-$x_line_density*$i,$width+$empty_size_x,$zero_y-$x_line_density*$i,$color_3);
	   if($zero_y+$x_line_density*$i < $high){
		imageline($pic,0+$empty_size_x,$zero_y+$x_line_density*$i,$width+$empty_size_x,$zero_y+$x_line_density*$i,$color_3);
	   }
	   imagesetthickness($pic,2);        //轴点线宽
	   imageline($pic,0+$empty_size_x,$zero_y-$x_line_density*$i,3+$empty_size_x,$zero_y-$x_line_density*$i,$color_2);
	   if($zero_y+$x_line_density*$i < $high){
		imageline($pic,0+$empty_size_x,$zero_y+$x_line_density*$i,3+$empty_size_x,$zero_y+$x_line_density*$i,$color_2);
	   }
	   imagestring($pic,1,0,$zero_y-$x_line_density*$i-3,round($limit/$x_line_width*$i,$fix),$color_2);   //数轴标记
	   if($zero_y+$x_line_density*$i < $high){
		imagestring($pic,1,0,$zero_y+$x_line_density*$i-3,round(-$limit/$x_line_width*$i,$fix),$color_2);  //数轴标记
	   }
	  }
	}
	//绘制轴线
	imagesetthickness($pic,2);          //轴线宽
	imageline($pic,1+$empty_size_x,0,1+$empty_size_x,$high,$color_2);
	if($zero_y > $high){             //x轴位置
	  imageline($pic,0+$empty_size_x,$high,$width+$empty_size_x,$high,$color_2);
	}else{
	  imageline($pic,0+$empty_size_x,$zero_y,$width+$empty_size_x,$zero_y,$color_2);
	}
	//产生折线
	$point_x=0;
	$j=0;
	imagesetthickness($pic,$strong);       //线条粗细
	while($j+1 < $allnum){
	  imageline($pic,$point_x+2+$empty_size_x,$point_y[$j],$point_x+$x_pxdensity+2+$empty_size_x,$point_y[$j+1],$color_1);
	  $point_x+=$x_pxdensity;
	  $j++;
	}
	imagepng($pic);
	imagedestroy($pic);
	 }//end function 
}

?>