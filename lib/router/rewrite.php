<?php
/**
 * @name re-route.php
 * @copyright 2010
 * @version 1.01
 * @since 2010-08-31 22:00
 * @package Tmper 1.01 rec
 */
if(!defined('DS')){
    define('DS', DIRECTORY_SEPARATOR);
}
if(!defined('SYS_PATH')){//SYSTEM DIR
    define('SYS_PATH', dirname(dirname(dirname(__FILE__))));
}
if(!defined('WEB_ROOT')){//WEBSITE DIR
    define('WEB_ROOT', SYS_PATH);
}
if(!defined('APP_PATH')){//APP DIR
    define('APP_PATH', dirname(__FILE__).DS.'app');
}
/*if(!defined('SITE_URL')){
    define('SITE_URL', Basic::siteurl(SYS_PATH));
}*/
/**
 * Router Class
 */
class Route{
    var $module     = null;
    var $function   = null;
    var $parms      = array();
    var $rewrite      = false;
    var $rewrite_arr  = array();
    var $url          = null;

    /**
     * @todo this function can change the uri address to array();
     * @example
     * http://www.tmper.com/page/index/1/?page=242
     * =>
     * $this->module   = 'page';
     * $this->function = 'index';
     * $this->parms    = array('0'=>'1');
     * @example
     * http://www.tmper.com/page/index/?page=242
     * =>
     * $this->module   = 'page';
     * $this->function = 'index';
     * $this->parms    = array();
     */
     function  __construct() {
        $url = eregi_replace(preg_quote(dirname($_SERVER['PHP_SELF']).'/'), '', $_SERVER['REDIRECT_URL']);
		/*$url = "";
		$urlarr = array();
		if(!empty($_SERVER['QUERY_STRING'])){
			$url_rt = explode('=',trim($_SERVER['QUERY_STRING']));
			if(count($url_rt)>1){
				$url = $url_rt[1];
			}
			unset($url_rt);
		}*/
		if(!empty($url)){
			$urlarr = explode('/', $url);
			foreach($urlarr as $k=>$val){
				if($val=='')unset($urlarr[$k]);
			}
		}
        if(!empty($urlarr)){
            $this->module = array_shift($urlarr);
        }
        if(!empty($urlarr)){
            $this->function = array_shift($urlarr);
        }
		if(!empty($urlarr)){
			$this->parms = array_map('urldecode', $urlarr);
		}
        if(empty($this->module) && empty($this->function)){
            $this->module   = 'page';
            $this->function = 'index';
        }
		//get arg
		if(!empty($_SERVER['REQUEST_URI'])&&stristr($_SERVER['REQUEST_URI'],'?')){
			$get = explode('?',$_SERVER['REQUEST_URI']);
			if(isset($get[1]) && !empty($get[1])){
				$get2 = explode('&',str_replace('&&','&',$get[1]));
				foreach($get2 as $str){
					if(!empty($str) && stristr($str,'=')){
						$get3 = explode('=',trim($str));
						$_GET[$get3[0]] = $get3[1];
					}
				}
				unset($get,$get2);
			}
		}
    }

    /**
     * @todo return array([module],[function], [parm1], [parm2], ......);
     * if uri is a file, it will show out and end.
     * @return <array>
     */
    function load(){
        $cfile = APP_PATH.DS.$this->module.DS.'controller.php';
        if(empty($this->function) && !file_exists($cfile)){
            $this->function = $this->module;
            $this->module = 'page';
            $cfile = APP_PATH.DS.$this->module.DS.'controller.php';
        }//
        if(file_exists($cfile)){
            require_once($cfile);
        }else{
            $cfile = dirname(__FILE__).DS.'app'.DS.$this->module.DS.'controller.php';
            if(file_exists($cfile)){
                require_once($cfile);
            }else{
                header("HTTP/1.0 404 Not Found");
                die("Error:miss ".$this->module.' module!');
                exit;
            }
        }
        $classname_C = ucwords($this->module).'Controller';
        $methods = get_class_methods($classname_C);
        if(!in_array($this->function, $methods)){
            $fn = APP_PATH.DS.$this->module.DS.$this->function.DS.implode(DS, $this->parms);
            if(is_file($fn)){
                $pathArr = pathinfo($fn);
                switch($pathArr['extension']){
                    case 'js':{
                        header('Content-type:text/javascript;charset=UTF-8');
                        echo file_get_contents($fn);
                    };
                    break;
                    case 'jpg':
                    case 'png':
                    case 'gif':{
                        header('Content-type:image/jpeg');
                        echo file_get_contents($fn);
                    };
                    break;
                    case 'php':{
                        header('HTTP/1.1 403 Forbidden');
                        die('HTTP/1.1 403 Forbidden');
                    }
                    case 'css':{
                        header('Content-type:text/css;charset=UTF-8');
                        echo file_get_contents($fn);
                    };
                    break;
                    default:{
                        header('HTTP/1.1 403 Forbidden');
                        die('HTTP/1.1 403 Forbidden');
                    }
                }
                exit;
            }
            array_unshift($this->parms, $this->function);
            $this->function = 'index';
            if(!in_array($this->function, $methods)){
                header("HTTP/1.0 404 Not Found");
                die('Error:miss index function!');
                exit;
            }
        }
        $args = $this->parms;
        array_unshift($args, $this->module, $this->function);
        return $args;
    }


    /**
     * @todo format uri to function
     * @param <type> $preg
     * @param <type> $module
     * @param <type> $func
     * @return <type> 
     */
    function rewrite($preg, $module, $func){
        if(!$this->rewrite ){
            preg_match_all($preg, $_SERVER['REQUEST_URI'], $rt);
            if(isset($rt['0']['0']) && $rt['0']['0'] <> ''){
                if(isset($rt['1'])){
                    $args = $rt['1'];
                }
                $this->module   = $module;
                $this->function = $func;
                if(!empty($args)){
                    $this->parms = array_merge($args, $this->parms);
                }
                $this->rewrite = true;
            }
        }
        return $this->rewrite;
    }
}

/**
 * Running
 */
$app = Import::controller();
$app->App = Import::model();
$re = Import::route();
call_user_func_array(array($app, 'action'), $re->load());
?>