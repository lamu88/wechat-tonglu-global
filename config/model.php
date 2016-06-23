<?php
/*
*数据库信息配置
*/
if(!defined('SYS_PATH')) die("请你先配置：SYS_PATH");

include(SYS_PATH.'data'.DS.'config.php'); //配置文件

$_this->DB_HOST = $db_host ? $db_host : 'localhost:3306';
$_this->DB_USER = $db_user ? $db_user : 'jingying_f';
$_this->DB_PASS = $db_pass ? $db_pass : '';
$_this->DB_NAME = $db_name ? $db_name : 'jingying';
$_this->PREFIX_ = $prefix ? $prefix : 'gz_';

$_this->debug(false);
?>