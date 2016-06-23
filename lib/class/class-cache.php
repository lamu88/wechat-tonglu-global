<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version >= 5.0                                                   |
// +----------------------------------------------------------------------+
// | Copyright (c) 2011 Hay                                               |
// +----------------------------------------------------------------------+
// | This class is for cache                                              |
// +----------------------------------------------------------------------+
// | Authors: Original Author                                             |
// |          Hay<xiaoguanhai@gmail.com>                                  |
// +----------------------------------------------------------------------+
//
class Cache{
    var $timeout     = 3600;
    var $dir         = '/';
    var $ext         = '.php';
    var $__tmp_data  = array();
    var $refresh     = false;
    var $close       = false;

    /**
     * @todo when it is true, it mean that the cache have been writed and then not need write again.
     * @var <bool>
     */
    var $runned      = false;

    /**
     * @todo if it had data to write, this var will trun to true;
     * @var <bool>
     */
    var $write       = false;

    
    function  __construct($dir, $args = array()){
        $this->dir = $dir;
        $this->args($args);
		$this->close = defined('CACHE_OPEN') ? CACHE_OPEN : false;
		$this->timeout = defined('CACHE_TIME') ? CACHE_TIME : 3600;
    }

    function __destruct(){
        if ($this->runned === false) {
            $this->close();
        }
    }

    /**
     * Refresh the cache data or not.
     * 
     * @param string $fn //cache file
     * @return bool
     */
    function __refresh($fn){
        (file_exists($fn) && mktime() - filemtime($fn) > $this->timeout) || !file_exists($fn)
        ? $this->refresh = true
        : $this->refresh = false;
        if(eregi('http://', $_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == ''){
            $this->refresh = false;
        }
        return $this->refresh;
    }

    /**
     * @todo this function is used to write data to cache file and end cache.
     * @param <string> $method @var w+|a+|w|a
     * @return <bool>
     */
    function args($args = array()){
        if(empty($args) || count($args) < 2) {
            die('Cache parms2 is unable!');
        }
		$t = '';
	    $x = $_SERVER["HTTP_HOST"];
	    $x1 = explode('.',$x);
	    if(count($x1)==2){
		  $t = $x1[0];
	    }elseif(count($x1)>2){
		  $t =$x1[0].$x1[1];
	    }
        $farr[] = array_shift($args).$t;
        $farr[] = array_shift($args);
        $this->uniqueid = md5(var_export($args, true));
        $hex = bin2hex(substr($this->uniqueid, 0, 2));
        $this->cachefn = $this->dir.DS.implode(DS, $farr).DS.$hex.$this->ext;
    }

    /**
     * Close to write cache.
     * @param string $method w+|a+
     * @return bool
     */
    function close($method = 'w+'){
        if ($this->write === false) {
            $this->runned = true;
            return true;
        }
        $info = pathinfo($this->cachefn);
        $dirArr = explode(DS, str_replace($this->dir, '', $info['dirname']));
        $dir = $this->dir;
        foreach($dirArr as $folder){
            $dir .= $folder.DS;
            if(!file_exists($dir))mkdir($dir, 0777);
        }
        unset($dirArr, $info);
        $re = @fopen($this->cachefn, $method);
        if(!is_resource($re)){
            return false;
        }
        $content  = '<?php $__tmp_data='.var_export($this->__tmp_data, true).';';
        $content .= '$__tmp_uniqueid = "'.$this->uniqueid.'"; ?>';
        @fwrite($re, $content);
        @fclose($re);
        @chmod($this->cachefn, 0777);
        $this->runned = true;
    }

    /**
     * write varname to cache file.
     *
     * Set data to __tmp_data var, and write it to cache file when this class
     * is end.
     *
     * @since 2.0.0
     * @param string|array $name Data var name.
     * @param array $data  Data var value.
     */
    function write($name, $data = array()){
        $this->write = true;
        if(is_array($name)){
            foreach($name as $varname=>$val){
                $this->__tmp_data[$varname] = $val;
            }
        }else{
            $this->__tmp_data[$name] = $data;
        }
    }

    /**
     * Read cache data
     * @param int $timeout if $timeout < 0 it will not timeout any time.
     * @return mix
     */
    function & read($timeout = 3600){
        $this->timeout = $timeout;
        if($this->close === true){
            return null;
        }
        if($timeout >= 0 && $this->__refresh($this->cachefn)){
            return null;
        }
        if(file_exists($this->cachefn)){
            $timeoutfn = dirname(dirname($this->cachefn)).'/__timeout__';
            if (file_exists($timeoutfn) && filemtime($timeoutfn) > filemtime($this->cachefn)) {
                return null;  
            }
            $timeoutfn = dirname($this->cachefn).'/__timeout__';
            if (file_exists($timeoutfn) && filemtime($timeoutfn) > filemtime($this->cachefn)) {
                return null;
            }
            include($this->cachefn);
            if(isset($__tmp_timeout) && mktime() > $__tmp_timeout){
                return null;
            }
            if($this->uniqueid <> $__tmp_uniqueid){
                return null;
            }else{
                $this->__tmp_data = & $__tmp_data;
                return $this->__tmp_data;
            }
        }else{
            return null;
        }
    }

    /**
     * Setting cache is timeout or not.
     * @return <bool>
     */
    function timeout($module = null, $function = null, $args = null){
        if($module && $function && is_array($args)) {
            $uniqueid = md5(var_export($args, true));
            $hex      = bin2hex(substr($uniqueid, 0, 2));
            $dir      = $this->dir.DS.$module.DS.$function;
            $fn       = $dir.DS.$hex.$this->ext;
            if(file_exists($fn)) {
                $re = @fopen($fn, 'a+');
                if(!is_resource($re)){
                    return false;
                }
                $content  = "\n".'<?php $__tmp_timeout='.mktime().'; ?>'."\n";
                @fwrite($re, $content);
                @fclose($re);
                @chmod($fn, 0777);
                $this->runned = true;
            }
        }else if($module && $function) {
            $dir = $this->dir.DS.$module.DS.$function;
            if(is_dir($dir) && file_exists($dir)) {
                $fn = $dir.DS.'__timeout__';
                fclose(fopen($fn, 'w+'));
                chmod($fn, 0777);
            }
        }else if($module) {
            $dir = $this->dir.DS.$module;
            if(is_dir($dir) && file_exists($dir)) {
                $fn = $dir.DS.'__timeout__';
                fclose(fopen($fn, 'w+'));
                chmod($fn, 0777);
            }
        }else{
            $info = pathinfo($this->cachefn);
            $dirArr = explode(DS, str_replace($this->dir, '', dirname($this->cachefn)));
            $dirArr = explode(DS, str_replace($this->dir, '', $info['dirname']));
            $dir = $this->dir;
            foreach($dirArr as $folder){
                $dir .= $folder.DS;
                if(!file_exists($dir))mkdir($dir, 0777);
            }
            unset($dirArr, $info);
            $re = @fopen($fn, 'a+');
            if(!is_resource($re)){
                return false;
            }
            $content  = "\n".'<?php $__tmp_timeout='.mktime().'; ?>'."\n";
            @fwrite($re, $content);
            @fclose($re);
            @chmod($fn, 0777);
            $this->runned = true;
        }
    }
}
?>