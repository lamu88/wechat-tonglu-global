<?php
define('DS', DIRECTORY_SEPARATOR);
error_reporting(0);
if(extension_loaded('zlib')) {
	@ini_set('zlib.output_compression', 'On');
	@ini_set('zlib.output_compression_level', '3');
}
if(function_exists('ob_gzhandler')){
	@ob_start('ob_gzhandler');
}else{
	@ob_start();
}

ini_set("memory_limit","64M");
@header('Content-Type:text/html; charset=UTF-8');

define('SYS_PATH',dirname(__FILE__).DS);
define('SYS_PATH_THEME',SYS_PATH.'theme'.DS);
define('APP_PATH', SYS_PATH_THEME.'app');
define('CFG_PATH', SYS_PATH.'config');
define('SESS_PATH', SYS_PATH.'sess'.DS);
define('SYS_PATH_PHOTOS',SYS_PATH.'photos'.DS); //图片存放目录
define('LAYOUT_PATH', SYS_PATH_THEME.'layout');
define('THIS_PATH',dirname(__FILE__).DS);

$x = $_SERVER["HTTP_HOST"];
$x1 = explode('.',$x);
if(count($x1)==2){
  $t = $x1[0];
}elseif(count($x1)>2){
  $t =$x1[0].$x1[1];
}
if(!empty($t)){
	$ld = array('A','B','C','D','E','F','G','H','I','J');
	for($i=0;$i<10;$i++){
		$t = str_replace($i,$ld[$i],$t);
	}
}
define('CFGH', (!empty($t) ? strtoupper($t) : 'F'));

if(is_file(SYS_PATH.'data/basic_config.php')){
	//require_once(SYS_PATH.'data/basic_config.php');
	//$GLOBALS['LANG'] = $basic_config;
}

//邮件服务器发送配置文件
if(is_file(SYS_PATH.'data/email_config.php')){
	require_once(SYS_PATH.'data/email_config.php');
}

define('CACHE_OPEN',($GLOBALS['LANG']['is_cache']=='1' ? false : true)); //是否关闭缓存 true：关闭 false：开启
define('CACHE_TIME',$GLOBALS['LANG']['cache_time']); 

require_once(SYS_PATH.'lib/load_obj.php');

//转义字符
function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}

/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }

    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}

//定义网站链接
if(in_array($_SERVER['HTTP_HOST'],array('127.0.0.1','127.0.0.1:8080','localhost','localhost:8080','192.168.1.189:8080'))){
    define('SITE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/wxfx20158/');
}else{
    define('SITE_URL', str_replace('ajaxfile/',"",Import::basic()->siteurl()));
}
/*
$app = Import::controller();
$app->App = Import::model();
*/
//define('SITE_URL', Import::basic()->siteurl()); //标识：相对网站根目录链接
define('ROOT_URL', SITE_URL); //标识：运行根目录链接

$app = Import::controller();
$app->App = Import::model();
?>