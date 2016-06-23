<?php
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
if(!defined('SYS_PATH')) define('SYS_PATH',"");

class Ajincache{
    var $close = false;
    var $timeout = 3600;
    var $dir = "cache";
    var $ext = '.php';
    var $model = '';
    var $function = '';
	var $fpa='';
   // var $off = 0;//1：表示當前關閉狀態

    function  __construct(){
		$this->dir = SYS_PATH."cache";
		$this->close = defined('CACHE_OPEN') ? CACHE_OPEN : false;
		$this->timeout = defined('CACHE_TIME') ? CACHE_TIME : 3600;
    }
    //當前目錄路徑
    function Setpath($path = null){
        if($path){
            $this->dir = $path;
        }
        return $this->dir;
    }
    //檢查緩存並設置
    function checkexp($fn){
        //
        (file_exists($fn) && mktime() - filemtime($fn) > $this->timeout) || !file_exists($fn)
        ? $this->close = true
        : $this->close = false;
        //
        if( $_SERVER['HTTP_USER_AGENT'] == ''){
            $this->close = false;
        }  
		if($this->close == false){
		
		}
        //
        return $this->close;
    }
    //
    function hash2dec($str){
        if(empty($str)){
            $hex = '1000';
        }else{
		    $hex = bin2hex(substr(md5($str), 0, 2));
		}
        return $hex;
    }
	//
	 function SetTimeout($timeout = 0){
        $this->timeout = $timeout;
    }
	//
	function SetFunction($function){
        $this->function = $function;
    }
	//
	function SetMode($mode){
	    $this->model=$mode;
	}
    //
    function SetExt($ext = null){
        if($ext){
            $this->ext = $ext;
        }
        return $this->ext;
    }
	//
	function Setfpa($fpa){
	    if($fpa){
            $this->fpa = $fpa;
        }
        return $this->fpa;
	}
    //
	function GetClose(){
        return $this->close;
    }
    //
    function fpath($args=array()){
        $DIR = $this->dir;
        if($this->model <> ''){
            $DIR .= DS.$this->model;
        }
        if($this->function <> ''){
            $DIR .= DS.$this->function;
        }
        $strmd5=md5($this->model.$this->function.@implode('', $args));
		$this->Setfpa($strmd5);
        $fn = $DIR.DS.$this->hash2dec(@implode('', $args)).$this->ext;
        //
		if($this->close == false){
			$this->checkexp($fn);
			$this->md5isexit($fn);
		}
        //
        return $fn;
    }
    //
	function md5isexit($fp){
	  if(file_exists($fp)){
		   include($fp); 
		   if(trim($__md5)==trim($this->fpa) && !($this->close)){ 
	           $this->close = false;
		   }else{
			   $this->close = true;
		   }
	   }else{
	       $this->close = true;
	   }
	   return $this->close;
    }
   /**************************************/
   function write($fn, $a, $n ,$mode = 'w'){
   		if(empty($fn)) return false;
        $content=$this->php_arr2str_2($a, $n);
        $pathArr = pathinfo($fn);
        $dirArr = explode(DS, $pathArr['dirname']);
        unset($pathArr);
        $dir = null;
        foreach($dirArr as $folder){
            $dir .= $folder.DS;
            if(!@file_exists($dir))@mkdir($dir,0777);
        }
        unset($dirArr);
        if(($mode == "a" || $mode == "a+") && !file_exists($fn)){
            $mode = 'w+';
        }
        $re = @fopen($fn, $mode);
        if(!is_resource($re)){
            return false;
        }
        @fwrite($re, $content);
        @fclose($re);
        @chmod($fn, 0777);
        return true;
    }
	function php_arr2str_2($array, $name){
        $string  = '<?php $'.$name.'='.$this->arr2str_2($array).';'."\n";
		$string .='$__md5="'.$this->fpa.'";?>';
        return $string;
    }
	function arr2str_2($array){
        if(!is_array($array)){
		 // $str="'".addslashes($array)."'";
		  $str="'".str_replace("'","\'",$array)."'";
        }else{
         if(empty($array)) $str= 'array() ;';
		 else{
           foreach($array as $key=>$val){
             $arr[] = $this->arr2str_2($key).'=>'.$this->arr2str_2($val);
           }
		 }
		 $str= 'array('.@implode(',', $arr).')';
	  }
       return $str;
    }
	/***************************************/
	 function wpfile($fn, $content,$mode = 'w'){
	    $content  =$content."\n";
	    $content .='$__md5 = "'.$this->fpa.'";'."\n";
        $pathArr = pathinfo($fn);
        $dirArr = explode(DS, $pathArr['dirname']);
        unset($pathArr);
        $dir = null;
        foreach($dirArr as $folder){
            $dir .= $folder.DS;
            if(!@file_exists($dir))@mkdir($dir,0777);
        }
        unset($dirArr);
        if(($mode == "a" || $mode == "a+") && !file_exists($fn)){
            $mode = 'w+';
        }
        $re = fopen($fn, $mode);
        if(!is_resource($re)){
            return false;
        }
        @fwrite($re, $content);
        @fclose($re);
        @chmod($fn, 0777);
        return true;
    } 
	
  function make_arr_str($arrname,$data,$key='')
  {
	if(is_array($data))
	{
		$rt="\$".$arrname."[".$key."]=array(";
		foreach($data as $k=>$v)
		{
			$rtr[]="\"".$k."\"=> \"".addslashes($v)."\"";
		}	
		$rt.=@join(",",$rtr).");";
	}
	return str_replace(array("\r\n","\n"),"",$rt);
  }
}
/****************************/
//eg:
   function test(){
	   $this->SetFunction(__FUNCTION__);
	   $this->SetMode(__CLASS__);
	   $fn = $this->fpath(func_get_args());
	   if(file_exists($fn)&&!$this->GetClose()){
		    include($fn);
        }
		else{
		    $arr=array('a','d');
            $this->write($fn, $arr,'arr');
        }
        return $arr;
	
	}
?>