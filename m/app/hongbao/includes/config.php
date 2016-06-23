<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
date_default_timezone_set("asia/shanghai");
define("DBHOST","localhost:3306");//服务器地址
define("DBUSER","root");//数据库用户名
define("DBPASS","123123!@");//数据库登陆密码
define("DBDATA","testfenxiao");//数据库名字
$db=mysql_connect(DBHOST,DBUSER,DBPASS) or die("数据库连接错误，请与管理员联系");
mysql_query("SET NAMES 'GBK'");
mysql_select_db(DBDATA,$db);

define('APPID',"wx1559595cd8992e28");//APPID
define('APPSECRET',"1afe9ca4b0b5882ea2c81ff2a554bfb3");//APPSECRET
define('PARTNERKEY',"10000000000000000000000000001234");//秘钥
define('MCHID',"1249974901");//商户号
define('URL',"http://nxn.8208111.com");//域名地址 最后不要跟/
?>