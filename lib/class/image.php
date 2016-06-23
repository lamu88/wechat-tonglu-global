<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version >= 5.0                                                   |
// +----------------------------------------------------------------------+
// | Copyright (c) 2011 Hay                                               |
// +----------------------------------------------------------------------+
// | This class is for image                                              |
// +----------------------------------------------------------------------+
// | Authors: Original Author                                             |
// |          Hay<xiaoguanhai@gmail.com>                                  |
// +----------------------------------------------------------------------+
//
class Image{
    /**
     *
     * @var <object>
     */
    var $src_im;

    /**
     *
     * @var <object>
     */
    var $dst_im;

    /**
     *
     * @var <int>
     */
    var $dst_x = 0;

    /**
     *
     * @var <int>
     */
    var $dst_y = 0;

    /**
     *
     * @var <int>
     */
    var $src_x = 0;

    /**
     *
     * @var <int>
     */
    var $src_y = 0;

    var $src   = array();

    /**
     * @todo adjustment length and width.
     * @param <int> $max_x
     * @param <int> $max_y
     * @param <int> $source_x
     * @param <int> $source_y
     * @return <array> array('max_x'=>value1, 'max_y'=>value2)
     */
    function __adjust($max_x, $max_y, $source_x, $source_y){
        $mult   = $max_x/$source_x;
        $mult_y = $max_y/$source_y;
        //
        if (($mult_y<$mult && $mult_y>0) || ($mult<=0 && $mult_y>0)) {
            $mult = $mult_y;
        }
        //
        if ($mult<=1 && $mult>0) {
            $max_x = $source_x*$mult;
            $max_y = $source_y*$mult;
        }else {
            $max_x = $source_x;
            $max_y = $source_y;
        }
        //
        $this->dst_x = $max_x;
        $this->dst_y = $max_y;
        return array('max_x'=>$max_x, 'max_y'=>$max_y);
    }

    /**
     * @todo sharp photo, get the result to "$this->dst_im" var.
     * @param <int> $degree
     * @param <int> $radius
     */
    function __sharp($degree, $radius = 1){
        $truecolor = imagecreatetruecolor($this->dst_x, $this->dst_y);
        for($x=0; $x<$this->dst_x; $x++){
            for($y=0; $y<$this->dst_y; $y++){
                $src_clr = array();
                //
                for($i=$x-$radius; $i<=$x+$radius; $i++){
                    for($j=$y-$radius; $j<=$y+$radius; $j++){
                        $src_clr[$i][$j] = imagecolorsforindex($truecolor, imagecolorat($this->dst_im, $i, $j));
                    }
                }
                $sum_r = array();
                $sum_g = array();
                $sum_b = array();
                foreach($src_clr as $k1=>$row){
                    foreach($row as $k2=>$val){
                        if($k1 <> $x && $k2 <> $y){
                            $sum_r[] += $val['red'];
                            $sum_g[] += $val['green'];
                            $sum_b[] += $val['blue'];
                        }
                    }
                }
                $avg_r = array_sum($sum_r) / count($sum_r);
                $avg_g = array_sum($sum_g) / count($sum_g);
                $avg_b = array_sum($sum_b) / count($sum_b);
                unset($sum_r, $sum_g, $sum_b);
                //
                $r = intval($src_clr[$x][$y]['red']+ $degree*($src_clr[$x][$y]['red'] - $avg_r));
                $b = intval($src_clr[$x][$y]['blue']+ $degree*($src_clr[$x][$y]['blue'] - $avg_b));
                $g = intval($src_clr[$x][$y]['green']+ $degree*($src_clr[$x][$y]['green'] - $avg_g));
                unset($src_clr);
                $r = min(255,  max($r,  0));
                $g = min(255,  max($g,  0));
                $b = min(255,  max($b,  0));
                if(($dst_clr=imagecolorexact($this->dst_im,  $r,  $g,  $b)) == -1){
                    $dst_clr = imagecolorallocate($this->dst_im,  $r,  $g,  $b);
                }
               imagesetpixel($truecolor, $x, $y, $dst_clr);
            }
       }
       $this->dst_im = $truecolor;
    }

    /**
     * 获取图片信息
     *
     * 获取图片的基本信息, 并根据参数调整图片的大小. 返回的值是一个新的图片对象.
     * 该函数为私有函数, 不建议在外面调用.
     *
     * @param string $from //图片地址
     * @param float $max_x
     * @param float $max_y
     * @param float $degree
     * @return object
     * @access private
     */
    function __read($from, $max_x = 0, $max_y = 0, $degree = 0){
        if(!is_resource($from) && !isset($this->src[md5($from)])) {
            $tmpArr = pathinfo($from);
            if(!isset($tmpArr['extension']) || !$tmpArr['extension']) {
                return false;
            }
            $ext = strtolower($tmpArr['extension']);
            unset($tmpArr);
            switch($ext){
                case 'gif':
                    $this->src_im = @imagecreatefromgif($from);
                    break;
                case 'jpg':
                case 'jpeg':
                    $this->src_im = @imagecreatefromjpeg($from);
                    break;
                case 'png':
                    $this->src_im = @imagecreatefrompng($from);
                    break;
                default :
                    return false;
            }
            $this->src[md5($from)]['src_im'] = $this->src_im;
        }else {
            is_resource($from)
            ? $this->src_im = $from
            : $this->src_im  = $this->src[md5($from)]['src_im'];
        }

        if(!is_resource($this->src_im)) {
            return false;
        }

        $this->src_x = imagesx($this->src_im);
        $this->src_y = imagesy($this->src_im);

        $this->__adjust($max_x, $max_y, $this->src_x, $this->src_y);

        if($this->dst_x <> $this->src_x && $this->dst_y <> $this->src_y){
            $this->dst_im = imagecreatetruecolor($this->dst_x, $this->dst_y);
            $fff = imagecolorallocate($this->dst_im, 0xff, 0xff, 0xff);
            imagefilledrectangle($this->dst_im, 0 ,0, $this->dst_x, $this->dst_y, $fff);
            imagecopyresampled($this->dst_im, $this->src_im, 0, 0, 0, 0, $this->dst_x, $this->dst_y, $this->src_x, $this->src_y);
        }else{
            $this->dst_im = $this->src_im;
        }
        
        if($degree){
            $this->__sharp($degree);
        }
        
        return $this->dst_im;
    }

    /**
     * @todo Read photo file and show out.
     * @param <string> $from, this var is a file path.
     * @param <type> $max_x, this var is max width what you want to.
     * @param <type> $max_y, this var is max height what you want to.
     */
    function read($from, $max_x = 0, $max_y = 0){
        header('Content-type: image/jpg');
        $src = $this->__read($from, $max_x = 0, $max_y = 0);
        imagejpeg($src);
        imagedestroy($src);
    }
    
    /**
     * @todo get photo from string varchar.
     * @param <string> $string
     * @param <bool> $show
     * @param <int> $max_x
     * @param <int> $max_y
     * @return <object>|show out photo
     */
    function readfromstr($string, $max_x = 0, $max_y = 0){
        $src_im = imagecreatefromstring($string);
        $this->read($src_im, $max_x, $max_y);
    }

    /**
     * @todo set photo from string for download it.
     * @param <string> $string. this var is a string which is get from a photo file.
     * @return show out image.
     */
    function downfromstr($string){
        header('Content-type: image/jpeg');
        header("Content-Disposition: attachment; filename=".md5($string).".jpg");
        $src = imagecreatefromstring($string);
        imagejpeg($src);
    }

    function download2client($fn, $max_x = 0, $max_y = 0){
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        //header('Content-type: image/jpeg');
        header('Content-type: application/force-download');
        //header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $lastModified) . ' GMT');
        header("Content-Disposition: attachment; filename=".basename($fn));
        header("Content-Transfer-Encoding: binary");
       // header("Content-Length: ".filesize($fn));
        $ext = pathinfo($fn, PATHINFO_EXTENSION);
        switch($ext){
            case 'gif':
                $this->src_im = @imagecreatefromgif($fn);
                break;
            case 'jpg':
            case 'jpeg':
                $this->src_im = @imagecreatefromjpeg($fn);
                break;
            case 'png':
                $this->src_im = @imagecreatefrompng($fn);
                break;
            default :
                return false;
        }

        if(!is_resource($this->src_im)) {
            return false;
        }
        $this->src_x = imagesx($this->src_im);
        $this->src_y = imagesy($this->src_im);
        $this->dst_x = $max_x;
        $this->dst_y = $max_y;
        $src = imagecreatetruecolor($this->dst_x, $this->dst_y);
        $fff = imagecolorallocate($src, 0xff, 0xff, 0xff);
        imagefilledrectangle($src, 0 ,0, $this->dst_x, $this->dst_y, $fff);
        imagecopyresampled($src, $this->src_im, 0, 0, 0, 0, $this->dst_x, $this->dst_y, $this->src_x, $this->src_y);
        imagejpeg($src);
        imagedestroy($src);
    }

    /**
     * 绘画图片
     *
     * 根据提供的对象图片或图片资源(resource), 对图片进行重绘. 重绘后的图片长度, 宽度可设置.
     * 如果@param string $from为$_FILES['name']['tmp_name'], 那么允许的$_FILES数据格式如下:
     * $_FILES['name'] = array('name'=>value, 'tmp_name'=>value, 'error'=>value, 'size'=>value);
     * $_FILES['name'] = array('name'=>array('0'=>value, '1'=>value, ...), 'tmp_name'=>array('0'=>value, '1'=>value, ...), ...);
     *
     * @since 2.0.1
     * @param <type> $fn      //保存图片地址
     * @param <type> $from    //来源图片或资源
     * @param <type> $max_x   //重绘后的图片最大宽度
     * @param <type> $max_y   //重绘后的图片最大高度
     * @param <type> $quality //保存质量 0-100
     * @param <type> $degree  //优化程度 0至1
     * @return <type>
     */
    function draw($fn, $from, $max_x = 200, $max_y = 200, $quality = 100, $degree = 0){
        if(is_bool(strpos($from, 'http://')) && !is_resource($from)){
            return $this->upload2draw($fn, $from, $max_x, $max_y, $quality, $degree);
                            }
        $src = $this->__read($from, $max_x, $max_y, $degree);
        if(!is_resource($src)){
            return false;
        }
        return imagejpeg($src, $fn, $quality);
    }

    /**
     *
     * 上传并绘图
     *
     * @param <type> $fn
     * @param <type> $files
     * @param <type> $max_x
     * @param <type> $max_y
     * @param <type> $quality
     * @param <type> $degree
     * @return <type>
     */
    function upload2draw($fn, $tmp_name, $max_x = 200, $max_y = 200, $quality = 100, $degree = 0) {
        if(!file_exists($tmp_name)) {
            return false;
        }
        list($width, $height, $type, $tmpArr) = getimagesize($tmp_name);
        if(!$width || !$height || !$type){
            return false;
        }
        switch($type){
            case '1':
                $src_im = imagecreatefromgif($tmp_name);
                break;
            case '2':
                $src_im = imagecreatefromjpeg($tmp_name);
                break;
            case '3':
                $src_im = imagecreatefrompng($tmp_name);
                break;
            default:
                return false;
        }
        $src = $this->__read($src_im, $max_x, $max_y, $degree);
        if(!is_resource($src)){
            return false;
        }
        return imagejpeg($src, $fn, $quality);
    }

    /**
     * 根据图片内容<字符串>保存图片
     *
     * @param <type> $fn //保存图片地址
     * @param <type> $string
     * @param <type> $max_x   //默认200
     * @param <type> $max_y   //默认200
     * @param <type> $quality //默认100
     * @param <type> $degree  //锐化程度. 默认为0, 表示不使用锐化.
     * @return <type>
     */
    function drawfromstr($fn, $string, $max_x = 200, $max_y = 200, $quality = 100, $degree = 0){
        $src_im = imagecreatefromstring($string);
        $src = $this->__read($src_im, $max_x, $max_y, $degree);
        if(!is_resource($src)){
            return false;
        }
        return imagejpeg($src, $fn, $quality);
    }

    /**
     * Mark for photo.
     *
     * @param <type> $fn Where to save the photo
     * @param <type> $from Mart for the photo. It can be path and Object
     * @param <type> $mark Mart file path or Object
     * @param <type> $max_x
     * @param <type> $max_y
     * @param <type> $quality
     * @param <type> $degree
     * @return <type>
     */
    function watermark($fn, $from, $mark, $max_x = 0, $max_y = 0, $quality =  100, $degree = 0){
        $src = $this->__read($from);
        $x = $this->src_x;
        $y = $this->src_y;
        //
        $src2 = $this->__read($mark);
        $src2_x = $this->src_x;
        $src2_y = $this->src_y;
        $sy = (($y-$src2_y))/5*4;
        //
        imagecopyresampled($src, $src2, 0, $sy, 0, 0, $src2_x, $src2_y, $src2_x, $src2_y);
        unset($src2);
        //
        $this->__adjust($max_x, $max_y, $x, $y);
        if($this->dst_x <> $x && $this->dst_y <> $y){
            $src3 = imagecreatetruecolor($this->dst_x, $this->dst_y);
            imagecolorallocatealpha($src3, 255, 255, 255, 100);
            imagecopyresampled($src3, $src, 0, 0, 0, 0, $this->dst_x, $this->dst_y, $x, $y);
            $src = $src3;
            unset($src3);
        }
        return imagejpeg($src, $fn, $quality);
    }

    //You can use this function to interception a photo.
    /**
     *
     * @param <string> $from, a file path.
     * @param <string> $to, the file path where want to save.
     * @param <int> $max_x
     * @param <int> $max_y
     * @param <array> $method @var $method['0'] = left|right|center; $method['1'] = top|middle|bottom
     * @param <int> $dev_x
     * @param <int> $dev_y
     */
    function interception($from, $to, $max_x = 200, $max_y = 200, $method = array('center','center'), $dev_x = 0, $dev_y = 0){
        if (!is_array($method)) {
           $method = array('align'=>'center', 'valign'=>'center');
        }
        $src = $this->__read($from);
        $src_x = $this->src_x;
        $src_y = $this->src_y;
        list($align, $valign) = $method;
        if(is_numeric($align)){
            $t_x = $align;
        }else {
            switch ($align) {
                case 'left':
                    $t_x = 0;
                    break;
                case 'right':
                    $t_x = $src_x-$max_x;
                    break;
                default:
                    $t_x = ($src_x-$max_x)/2;
            }
        }
        if (is_numeric($valign)) {
            $t_y = $valign;
        }else {
            switch ($valign) {
                case 'top':
                    $t_y = 0;
                    break;
                case 'bottom':
                    $t_y = $src_y-$max_y;
                    break;
                default:
                    $t_y = ($src_y-$max_y)/2;
            }
        }
        $t_x -= $dev_x;
        $t_y -= $dev_y;
        //
        $img = imagecreatetruecolor($max_x, $max_y);
        imagecolorallocatealpha($img, 255, 255, 255, 0);
        imagecopyresampled($img, $src, 0, 0, $t_x, $t_y, $max_x, $max_y, $max_x, $max_y);
        return imagejpeg($img, $fn, 100);
    }

    /**
     * 按比例截取图片
     *
     * @param string $from  //图片来源
     * @param string $fn    //保存文件
     * @param string $scale //比例 格式x:y
     * @param int $max_x    //最大宽度
     * @param int $quality  //保存质量, 0至100
     * @param float $degree //是否锐化
     * @return bool
     */
    function scaledraw($from, $fn, $scale = '4:3', $max_x = 300, $quality =  100, $degree = 0){
        $tmp = explode(':', $scale);
        list($scale_x, $scale_y) = $tmp;
        if ($scale_x <= 0) {
            $scale_x = 4;
        }
        if ($scale_y <= 0) {
            $scale_y = 3;
        }
        //
        $src = $this->__read($from);
        $src_x = $this->src_x;
        $src_y = $this->src_y;
        if($src_x <= $max_x && $src_y <= $max_y) {
            return imagejpeg($src, $fn, $quality);
        }
        if ($src_x/$src_y < $scale_x/$scale_y) {
            $src_y = $src_x/$scale_x*$scale_y;
        }
        //
        if ($src_x/$src_y > $scale_x/$scale_y) {
            $src_x = $src_y/$scale_y*$scale_x;
        }
        //
        if ($max_x > $src_x) {
            $max_x = $src_x;
        }
        $max_y = $max_x/$scale_x*$scale_y;
        $img = imagecreatetruecolor($max_x, $max_y);
        imagecolorallocatealpha($img, 255, 255, 255, 0);
        imagecopyresampled($img, $src, 0, 0, 0, 0, $max_x, $max_y, $src_x, $src_y);
        return imagejpeg($img, $fn, 100);
    }
}