<?php
if(!defined('InEmpireBak'))
{
	exit();
}

//Database
$phome_db_ver="5.0";
$phome_db_server="localhost";
$phome_db_port="";
$phome_db_username="root";
$phome_db_password="";
$phome_db_dbname="";
$baktbpre="";
$phome_db_char="utf8";

//USER
$set_username="admin";
$set_password="5643c4e413351b2fb1b31fcc1aba5bd9";
$set_loginauth="";
$set_loginrnd="35kwMvKkhGWM9bi2u7iZ8eRRxpX5j6";
$set_outtime="60";
$set_loginkey="1";

//COOKIE
$phome_cookiedomain="";
$phome_cookiepath="/";
$phome_cookievarpre="ebak_";

//LANGUAGE
$langr=ReturnUseEbakLang();
$ebaklang=$langr['lang'];
$ebaklangchar=$langr['langchar'];

//BAK
$bakpath="bdata";
$bakzippath="zip";
$filechmod="1";
$phpsafemod="";
$php_outtime="1000";
$limittype="";
$canlistdb="";

//------------ SYSTEM ------------
HeaderIeChar();
?>