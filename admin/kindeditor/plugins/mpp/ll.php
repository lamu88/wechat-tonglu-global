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
define('SYS_PATH',dirname(dirname(dirname(dirname(dirname(__FILE__))))).DS);
define('SYS_PATH_ADMIN',dirname(__FILE__).DS.'the'.DS);
define('APP_PATH', SYS_PATH_ADMIN.'app');
define('CFG_PATH', SYS_PATH.'config');
define('SESS_PATH', SYS_PATH.'sess'.DS);
define('SYS_PATH_PHOTOS',SYS_PATH.'photos'.DS); //图片存放目录
define('LAYOUT_PATH', SYS_PATH_ADMIN.'layout');
define('THIS_PATH',dirname(__FILE__).DS);
require_once(SYS_PATH.'lib/load_obj.php');
define('ADMIN_URL', Import::basic()->siteurl());
$app = Import::controller();
$app->App = Import::model();
?>