<?php
if(!defined('DS')){
    define('DS', DIRECTORY_SEPARATOR);
}
require_once(dirname(__FILE__).DS.'class'.DS.'basic.php');

class Import{
    function & instance(){
        static $_this = null;
        if(isset($GLOBALS['__'.__CLASS__.'__'])){
            $_this = $GLOBALS['__'.__CLASS__.'__'];
        }else{
            $_this = & new Import();
            $GLOBALS['__'.__CLASS__.'__'] = $_this;
        }
        $_this->classDir      = dirname(__FILE__).DS.'class';
        $_this->routerDir     = dirname(__FILE__).DS.'router';
        $_this->crawlDir      = dirname(__FILE__).DS.'crawler';
        $_this->verifyCodeDir = dirname(__FILE__).DS.'verifycode';
        return $_this;
    }

    /**
     * Router class
     */
    function & controller(){
        $_this = Import::instance();
        if (!class_exists('Controller')) {
            require_once $_this->routerDir.DS.'controller.php';
        }
        return new Controller();
    }
    function & model(){
        $_this = Import::instance(); 
        if (!class_exists('Database')) {
            require_once($_this->classDir.DS.'db-mysql.php');
        }
        if (!class_exists('Model')) {
            require_once($_this->routerDir.DS.'model.php');
        }
        return new Model();
    }
    function & view(){
        $_this = Import::instance();
        if(!class_exists('View')){
            require_once($_this->routerDir.DS.'view.php');
        }
        return new View();
    }
    function & route(){
        $_this = Import::instance();
        if(!class_exists('Route')){
            require_once($_this->routerDir.DS.'rewrite.php');
        }
        return new Route();
    }

    /**
     * Class
     */
     function & db_mysql(){
        $_this = Import::instance();

        if(!class_exists('Database')){
            require_once($_this->classDir.DS.'db-mysql.php');
        }
        return new Database();
    }

	//备份数据库的对象
	function & backdb(){
	 	$_this = Import::instance();
		if(!class_exists('Database')){
            require_once($_this->classDir.DS.'db-mysql.php');
        }
		
        if(!class_exists('Backdb')){
            require_once($_this->classDir.DS.'class-backdb.php');
        }
        return new Backdb();
	}
	
    function & image(){
        $_this = Import::instance();
        if(!class_exists('Image')){
            require_once($_this->classDir.DS.'image.php');
        }
        return new Image();
    }
	
	function basic(){
		$_this = Import::instance();
		if(!class_exists('Basic')){
            require_once($_this->classDir.DS.'basic.php');
        }
        return new Basic();
	}
	
	function ip(){
		$_this = Import::instance();
		if(!class_exists('IP')){
            require_once($_this->classDir.DS.'class-ip.php');
        }
        return new IP();
	}
	
	//操作excel对象
	function excel($encode="UTF-8"){
        $_this = Import::instance();
        if(!class_exists('Spreadsheet_Excel_Reader')){
            require_once($_this->classDir.DS.'Excel'.DS.'reader.php');
        }
        $excel = & new Spreadsheet_Excel_Reader();
		//设置文本输出编码
		//$excel->setOutputEncoding('CP936');
		$excel->setOutputEncoding($encode);
		return $excel;
    }
	
	//导出excel表对象
	function exportexcel(){
	 	$_this = Import::instance();
        if(!class_exists('PHPExcel')){
            require_once($_this->classDir.DS.'PHPExcel.php');
			require_once($_this->classDir.DS.'PHPExcel'.DS.'IOFactory.php');
        }
		return  new PHPExcel();
	}
	
	function json(){
		$_this = Import::instance();
		if(!class_exists('JSON')){
            require_once($_this->classDir.DS.'json.php');
        }
        return new JSON();
	}
	
	//编码转化对象
	function gz_iconv(){
		$_this = Import::instance();
		if(!class_exists('Chinese')){
            require_once($_this->classDir.DS.'class-iconv.php');
        }
        return new Chinese();
	}
	
	function img(){
		$_this = Import::instance();
		if(!class_exists('ajinimg')){
            require_once($_this->classDir.DS.'class.images.php');
        }
        return new ajinimg();
	}
	
	function fileop(){
		$_this = Import::instance();
		if(!class_exists('FileOp')){
            require_once($_this->classDir.DS.'class.file.php');
        }
        return new FileOp();
	}
	
    function & session($dir = null, $stag = 1, $session_id = null){
        $_this = Import::instance();
        if(!class_exists('Database')){
            require_once($_this->classDir.DS.'db-mysql.php');
        }
        if(!class_exists('Session')){
            require_once($_this->classDir.DS.'session.php');
        }
        return new Session($dir, $stag, $session_id);
    }
    
	//hay 创建的缓存文件
    function & cache($dir = null, $parms = array()){
        $_this = Import::instance();
        if(!class_exists('Cache')){
            require_once($_this->classDir.DS.'class-cache.php');
        }
        return new Cache($dir, $parms);
    }
	
	//这个是我写的缓存文件
   function & ajincache(){
        $_this = Import::instance();
        if(!class_exists('Ajincache')){
            require_once($_this->classDir.DS.'ajin_cache.php');
        }
        return new Ajincache();
    }
	
	//记录错误信息对象
	function & error(){
		$_this = Import::instance();
        if(!class_exists('Error')){
            require_once($_this->classDir.DS.'class-error.php');
        }
        return new Error();
	}
	
	//创建发送email对象
	function smtp(){
		$_this = Import::instance();
        if(!class_exists('Smtp')){
            require_once($_this->classDir.DS.'email.class.php');
        }
        return new Smtp();
	}
	
	//创建发送email对象[ECSHOP内核]
	function ecshop_smtp($data=array()){
		$_this = Import::instance();
        if(!class_exists('Ecshop_smtp')){
            require_once($_this->classDir.DS.'cls_smtp.php');
        }
        return new Ecshop_smtp($data);
	}
	
    function & xmlAmazon($parms){
        $_this = Import::instance();
        if(!class_exists('XMLAmazon')){
            require_once($_this->classDir.DS.'xml-amazon.php');
        }
        return new XMLAmazon($parms);
    }

    function & htmlDOM(){
        $_this = Import::instance();
        if(!class_exists('simple_html_dom')){
            require_once($_this->classDir.DS.'class-html-dom.php');
        }
        return new simple_html_dom();
    }
    
    function & docpro(){
        $_this = Import::instance();
        if(!class_exists('Docpro')){
            require_once($_this->classDir.DS.'docpro.php');
        }
        return new Docpro();
    }

    function & pool($dir = null, $filename = 'pool'){
        $_this = Import::instance();
        if(!class_exists('Pool')){
            require_once($_this->classDir.DS.'pool.php');
        }
        return new Pool($dir, $filename);
    }

	//第一个解压对象
    function & zipfile(){
        $_this = Import::instance();
        if(!class_exists('Zipfile')){
            require_once($_this->classDir.DS.'zip.class.php');
        }
        return new Zipfile();
    }
	
	//第一个解压对象 常用
   function & zip(){
        $_this = Import::instance();
        if(!class_exists('PHPZip')){
            require_once($_this->classDir.DS.'zip.php');
        }
        return new PHPZip();
    }
	
	function & unzip($archive){
        $_this = Import::instance();
        if(!class_exists('PclZip')){
            require_once($_this->classDir.DS.'pclzip.lib.php');
        }
        return new PclZip($archive);
    }
	
    function & phpmailer(){
        $_this = Import::instance();
        if(!class_exists('PHPMailer')){
            require_once($_this->classDir.DS.'phpmailer'.DS.'class-phpmailer.php');
        }
        return new PHPMailer();
    }

    function snoopy(){
        $_this = Import::instance();
        if(!class_exists('Snoopy')){
            require_once($_this->classDir.DS.'class-snoopy.php');
        }
        return new Snoopy();
    }


    /**
     * Crawler Class
     */
    function & crawler(){
        $_this = Import::instance();
        if(!class_exists('Crawler')){
            require_once($_this->crawlDir.DS.'crawler.php');
        }
        return new Crawler();
    }

    function & google(){
        $_this = Import::instance();
        if(!class_exists('Google')){
            require_once($_this->crawlDir.DS.'crawler-google.php');
        }
        return new Google();
    }

    function & baidu(){
        $_this = Import::instance();
        if(!class_exists('Baidu')){
            require_once($_this->crawlDir.DS.'crawler-baidu.php');
        }
        return new Baidu();
    }

    function & yahoo(){
        $_this = Import::instance();
        if(!class_exists('Yahoo')){
            require_once($_this->crawlDir.DS.'crawler-yahoo.php');
        }
        return new Yahoo();
    }

    function & alexa(){
        $_this = Import::instance();
        if(!class_exists('Alexa')){
            require_once($_this->crawlDir.DS.'crawler-alexa.php');
        }
        return new Alexa();
    }

    function & verifycode(){
        $_this = Import::instance();
        if (!class_exists('Verifycode')) {
            require_once($_this->verifyCodeDir.DS.'class-verifycode.php');
        }
        return new Verifycode();
    }
}
?>