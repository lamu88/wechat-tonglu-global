<?php
define('DS', DIRECTORY_SEPARATOR);
error_reporting(0);

ob_start('ob_gzhandler');
ini_set("memory_limit","64M");
@header('Content-Type:text/html; charset=UTF-8');

define('SYS_PATH',dirname(dirname(__FILE__)).DS); //系统跟目录
define('SYS_PATH_ADMIN',SYS_PATH.'admin'.DS); //后台跟目录
define('APP_PATH', SYS_PATH_ADMIN.'app'); //模块目录
define('CFG_PATH', SYS_PATH.'config'); //配置文件
define('SESS_PATH', SYS_PATH.'sess'.DS); //session目录
define('LAYOUT_PATH', SYS_PATH_ADMIN.'layout'); //框架目录
define('SYS_PATH_PHOTOS',SYS_PATH.'photos'.DS); //图片存放目录
define('THIS_PATH',dirname(__FILE__).DS); //lib包用到的 一定要配置下

define('IS_TRUE',true); //如果没有定义这个的文件，不能单独访问

if(is_file(SYS_PATH.'data/basic_config.php')){
	require_once(SYS_PATH.'data/basic_config.php');
	$GLOBALS['LANG'] = $basic_config;
}

//邮件服务器发送配置文件
if(is_file(SYS_PATH.'data/email_config.php')){
	require_once(SYS_PATH.'data/email_config.php');
}

define('CACHE_OPEN',($GLOBALS['LANG']['is_cache']=='1' ? false : true)); //是否关闭缓存 true：关闭 false：开启 [用户后台生成静态页面只

require_once(SYS_PATH.'lib/load_obj.php');  //装在lib

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
        //$_POST = addslashes_deep($_POST);
    }

    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}


define('ADMIN_URL', Import::basic()->siteurl());
define('SITE_URL', dirname(ADMIN_URL).'/'); 
define('SYS_PHOTOS_URL',str_replace('/'.basename(dirname(__FILE__)),'',ADMIN_URL).'photos/'); //网站链接

$app = Import::controller();
$app->App = Import::model();
 
//判断用户是否登录
if(!($app->action('manager','is_login')) && !defined('LOGIN')){
 Import::basic()->redirect(ADMIN_URL.'login.php'); exit;
}

/*/用户权限管理end*/
?>