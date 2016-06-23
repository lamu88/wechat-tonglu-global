<?php
/**
 *基础类
 */
if(!defined('DS'))  define('DS',DIRECTORY_SEPARATOR);
class Basic{
    
    function & instance(){
       /* if (!isset($GLOBALS[__CLASS__])) {
            $GLOBALS[__CLASS__] = & new Basic();
        }
        return $GLOBALS[__CLASS__];*/
		return new Basic();
    }

    function charfilter($char, $filter) {
        return str_replace(array('\\', $filter), array('\\\\','\\'.$filter), $char);
    }

    function arr2json($array, $prefix = false){
        $_this = & Basic::instance();
        $i = 0;
        foreach($array as $k=>$row) {
            if(!is_array($row)) {
                $arr[] = '"'.$_this->charfilter($k, '"').'":"'.$_this->charfilter($row, '"').'"';
            }else {
                $i == $k
                ? $arr[] = $_this->arr2json($row)
                : $arr[] = '"'.$_this->charfilter($k, '"').'":'.$_this->arr2json($row, true);
            }
            $i ++;
        }
        $html = ($prefix ? '[' : '{').implode(',', $arr).($prefix ? ']' : '}');
        unset($arr);
        return $html;
    }

    function arr2str($array){
        if(!is_array($array)){
            return '"'.str_replace(array('\\','"'), array('\\\\','\"'),$array).'"';
        }
        if(empty($array))return 'array()';
        foreach($array as $key=>$val){
            $arr[] = Basic::arr2str($key).'=>'.Basic::arr2str($val);
        }
        return 'array('.implode(',', $arr).')';
    }

    function obj2arr($data){
        if(is_object($data))$data = get_object_vars($data);
        if(is_array($data))foreach($data as $k=>$var){
            $data[$k] = Basic::obj2arr($var);
        }
        return $data;
    }

    function php_arr2str($array, $name){
        return '<?php $'.$name.'='.var_export($array, true).'; ?>';
    }

    function arr2iconv($in_chars, $out_chars, $var){
        if(is_array($var)){
            foreach($var as $key=>$value){
                $var[$key] = Basic::arr2iconv($in_chars, $out_chars, $value);
            }
            $output = $var;
        }else{
            $output = iconv($in_chars, $out_chars, $var);
        }
        return $output;
    }

    function & str2arr($con, $s = ',') {
        $rt = array();
		$carr = explode("\n", $con);
		if(is_array($carr)){
				foreach($carr as $row){
					$row = str_replace(array("\r", "\r\n"), "", $row);
					if($row != ''){
						$rt[]= explode($s, $row);
					}
				}
			}
		return $rt;
    }
	
    function cmd($var, $in_chars = 'UTF-8', $out_chars = 'GBK'){
        if(is_array($var)){
            print_r(Basic::arr2iconv($in_chars, $out_chars, $var));
        }else{
            echo Basic::arr2iconv($in_chars, $out_chars, $var);
        }
        echo "\n";
    }

	//发送Email
	 function sendemail($arr=array()){
	    /*
		$arr['isdebug']:是否顯示發送的調試信息，true | false
		$arr['smtpserver']:SMTP服務器 EG:smtp.163.com
		$arr['smtpserverport']:SMTP服務器端口 EG:默認是25
		$arr['ischeck']:是否使用身份驗證 true | false
		$arr['smtpuser']:SMTP服務器用戶帳號 EG:jin.ge
		$arr['smtppass']:SMTP服務器的用戶密碼 EG:ABCDE
		$arr['smtpemailto']:發送給誰
		$arr['smtpusermail']:SMTP服務器的用戶信箱 EG:jin.ge@163.com
		$arr['mailsubject']:發送的標題
		$arr['mailbody']:發送的內容
		$arr['mailtype']:發送的格式  郵件格式（HTML/TXT）,TXT為文本郵
		
		Eg：
		   $arr['isdebug']=false;
		   $arr['smtpserver']='smtp.163.com';
		   $arr['smtpserverport']=25;
		   $arr['ischeck']=true;
		   $arr['smtpuser']='jin.ge';
		   $arr['smtppass']='ABCDE123.';
		   //$arr['smtpemailto']='459926518@qq.com';
		   $arr['smtpusermail']='jin.ge@163.com';
		   $arr['mailsubject']='ajin的测试EMAIL标题';
		   $arr['mailbody']='ajin的测试EMAIL内容';
		   $arr['mailtype']='HTML';
		*/
		
	    $result=false;
		$smtp=Import::smtp(); 
		$smtp->debug = $arr['isdebug'];
		$smtp->setsmtp($arr['smtpserver'],$arr['smtpserverport'],$arr['ischeck'],$arr['smtpuser'],$arr['smtppass']);
	    if($smtp->sendmail($arr['smtpemailto'], $arr['smtpusermail'], $arr['mailsubject'], $arr['mailbody'], $arr['mailtype'])){
		        $result=true;
		 }else{
				$result=false;
		}
		return $result;
	}
	
	/*
	 * ECSHOP 核心 Email发送
	 * @param: $name[string]        接收人姓名
	 * @param: $email[string]       接收人邮件地址
	 * @param: $subject[string]     邮件标题
	 * @param: $content[string]     邮件内容
	 * @param: $type[int]           0 普通邮件， 1 HTML邮件
	 * @param: $notification[bool]  true 要求回执， false 不用回执
	 *
	 * 统一UTF8编码
	 * 依赖全局变量
	 *  
	 	$GLOBALS['LANG']['smtp_mail'] = 'jin.ge@163.com';
		$GLOBALS['LANG']['smtp_host'] = 'smtp.163.com';
		$GLOBALS['LANG']['smtp_port'] = '25';
		$GLOBALS['LANG']['smtp_user'] = 'jin.ge';
		$GLOBALS['LANG']['smtp_pass'] = 'ABCDE123.';
		$GLOBALS['LANG']['mail_service'] = 1; //0:内部邮箱服务器发送 1:外部邮箱服务器发送
	  
	 * @return boolean
	*/
	function ecshop_sendemail($data=array()){
		$name = $data['name'];
		$email = $data['email'];
		$subject = $data['subject'];
		$content = $data['content'];
		$type = $data['type'];
		$notification = $data['notification'];
		
		$charset = "utf-8";
		 /**
		 * 使用mail函数发送邮件
		 */
		if ($GLOBALS['LANG']['mail_service'] == 0 && function_exists('mail'))
		{
			/* 邮件的头部信息 */
			$content_type = ($type == 0) ? 'Content-Type: text/plain; charset=' . $charset : 'Content-Type: text/html; charset=' . $charset;
			$headers = array();
			$headers[] = 'From: "' . '=?' . $charset . '?B?' . base64_encode($GLOBALS['LANG']['site_name']) . '?='.'" <' . $GLOBALS['LANG']['smtp_mail'] . '>';
			$headers[] = $content_type . '; format=flowed';
			if ($notification)
			{
				$headers[] = 'Disposition-Notification-To: ' . '=?' . $charset . '?B?' . base64_encode($GLOBALS['LANG']['site_name']) . '?='.'" <' . $GLOBALS['LANG']['smtp_mail'] . '>';
			}
	
			$res = @mail($email, '=?' . $charset . '?B?' . base64_encode($subject) . '?=', $content, implode("\r\n", $headers));

			if (!$res)
			{
				Import::error()->add("邮件发送失败");
	
				return false;
			}
			else
			{	
				return true;
			}
		}
		/**
		 * 使用smtp服务发送邮件
		 */
		else
		{
			/* 邮件的头部信息 */
			$content_type = ($type == 0) ?
				'Content-Type: text/plain; charset=' . $charset : 'Content-Type: text/html; charset=' . $charset;
			$content   =  base64_encode($content);
	
			$headers = array();
			$headers[] = 'Date: ' . gmdate('D, j M Y H:i:s') . ' +0000';
			$headers[] = 'To: "' . '=?' . $charset . '?B?' . base64_encode($name) . '?=' . '" <' . $email. '>';
			$headers[] = 'From: "' . '=?' . $charset . '?B?' . base64_encode($GLOBALS['LANG']['site_name']) . '?='.'" <' . $GLOBALS['LANG']['smtp_mail'] . '>';
			$headers[] = 'Subject: ' . '=?' . $charset . '?B?' . base64_encode($subject) . '?=';
			$headers[] = $content_type . '; format=flowed';
			$headers[] = 'Content-Transfer-Encoding: base64';
			$headers[] = 'Content-Disposition: inline';
			if ($notification)
			{
				$headers[] = 'Disposition-Notification-To: ' . '=?' . $charset . '?B?' . base64_encode($GLOBALS['LANG']['shop_name']) . '?='.'" <' . $GLOBALS['LANG']['smtp_mail'] . '>';
			}
	
			/* 获得邮件服务器的参数设置 */
			$params['host'] = $GLOBALS['LANG']['smtp_host'];
			$params['port'] = $GLOBALS['LANG']['smtp_port'];
			$params['user'] = $GLOBALS['LANG']['smtp_user'];
			$params['pass'] = $GLOBALS['LANG']['smtp_pass'];
	
			if (empty($params['host']) || empty($params['port']))
			{
				// 如果没有设置主机和端口直接返回 false
				Import::error()->add("邮件服务器的参数设置错误！");
	
				return false;
			}
			else
			{
				// 发送邮件
				if (!function_exists('fsockopen'))
				{
					//如果fsockopen被禁用，直接返回
					Import::error()->add("fsockopen被禁用");
	
					return false;
				}
	
				
	
				$send_params['recipients'] = $email;
				$send_params['headers']    = $headers;
				$send_params['from']       = $GLOBALS['LANG']['smtp_mail'];
				$send_params['body']       = $content;
				
				$smtp = Import::ecshop_smtp($params);
				
				if (!isset($smtp))
				{
					include_once(SYS_PATH . 'lib/class/cls_smtp.php');
					
					$smtp = new Ecshop_smtp($params);
				}

				if ($smtp->connect() && $smtp->send($send_params))
				{  
					return true;
				}
				else
				{
					$err_msg = $smtp->error_msg();
					if (empty($err_msg))
					{
						Import::error()->add('Unknown Error');
					}
					else
					{  
						if (strpos($err_msg, 'Failed to connect to server') !== false)
						{
							Import::error()->add("SMTP连接失败-".$params['host'] . ':' . $params['port']);
						}
						else if (strpos($err_msg, 'AUTH command failed') !== false)
						{
							Import::error()->add("SMTP登录失败");
						}
						elseif (strpos($err_msg, 'bad sequence of commands') !== false)
						{
							Import::error()->add("SMTP拒绝错误");
						}
						else
						{ print_r($err_msg); echo "run..........";
							Import::error()->add($err_msg);
						}
					}
	
					return false;
				}
			}
		}
	
	} //END FUNCTION 
	 /*
	  函数：remote_file_exists
	  功能：判断远程文件是否存在
	  参数： $url_file -远程文件URL
	  返回：存在返回true，不存在或者其他原因返回false
	  
     * 测试代码
     * $str_url = 'http://www.phpx.com/viewarticle.php?id=119617';
     * $exits = remote_file_exists($str_url);
     * echo $exists ? "Exists" : "Not exists";
 	 */
    function remote_file_exists($url_file){
           //检测输入
           $url_file = trim($url_file);
           if (empty($url_file)) { return false; }
           $url_arr = parse_url($url_file);
           if (!is_array($url_arr) || empty($url_arr)){return false; }

           //获取请求数据
           $host = $url_arr['host'];
           $path = $url_arr['path'] ."?".$url_arr['query'];
           $port = isset($url_arr['port']) ?$url_arr['port'] : "80";

           //连接服务器
           $fp = fsockopen($host, $port, $err_no, $err_str,30);
           if (!$fp){ return false; }

           //构造请求协议
           $request_str = "GET ".$path."HTTP/1.1\r\n";
           $request_str .= "Host:".$host."\r\n";
           $request_str .= "Connection:Close\r\n\r\n";

           //发送请求
           fwrite($fp,$request_str);
           $first_header = fgets($fp, 1024);
           fclose($fp);

           //判断文件是否存在
           if (trim($first_header) == ""){ return false;}
           if (!preg_match("/200/", $first_header)){
                  return false;
           }
           return true;
    }

	 
    /**
     * 显示脚本执行时间和占用内存情况
     *
     * @param $show为true时结果将会直接显示, 为false时会以<!-- XXX -->这种注释的形式显示.
     * 
     * @param boolean $show
     */
    function spend($show = false){
        $_this = & Basic::instance();
        $starttime = array_sum( split(' ', microtime() ) );
        register_shutdown_function(array(& $_this , '__spend'), $starttime, $show);
    }

    /**
     * <函数调用>
     *
     * @access private
     * @see spend
     * @param double $starttime
     * @param boolean $show
     */
    function __spend($starttime, $show){
        echo $show ? '': "\n<!--\n";
        echo "Cost:".number_format((array_sum(explode(" ",microtime()))-$starttime), 6 , '.', '')."s Use:".number_format((memory_get_usage()/1024), 2, '.', '')."k\n";
        echo $show ? '' : "-->";
    }
    
    function siteurl($root = null, $type='http'){
        $dir = str_replace(array(realpath($_SERVER['DOCUMENT_ROOT']), '\\'), array('', '/'), realpath(dirname($_SERVER["SCRIPT_FILENAME"])));
        return $type."://".$_SERVER["HTTP_HOST"].str_replace('//', '/', '/'.$dir.'/');
    }

    function thisurl($argc = array(), $type = 'http'){
        empty($argc)
        ? $url = $type.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
        : $url = $type.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].Basic::uri2str($argc);
        return $url;
    }
	
    //刷新 重新装载
    function redirect($url = '', $time = 0,$mes = ''){
	 	if(empty($url)) $url = $this->thisurl();
        if($time || headers_sent() || !empty($mes)){
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            $time *= 1000;
            echo '<script type="text/javascript">'."\n";
            if($mes){
                echo 'alert("'.str_replace(array("\r\n", "\n","\r"), '', $mes).'");'."\n";
            }
            echo 'function redirect(){'."\n";
            echo 'window.location="'.$url.'"'."\n";
            echo '}';
            echo 'setTimeout("redirect()",'.$time.');'."\n";
            echo '</script>'."\n";
        }else{
            header('location:'.$url);
        }
        exit;
    }
	
    //获取客户端IP
    function getip(){
        if(isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']){
            return $_SERVER['HTTP_CLIENT_IP'];
        }else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)){
            foreach ($matches[0] AS $ip){
                if (!preg_match("#^(10|172\.16|192\.168)\.#", $ip)){
                    return $ip;
                    break;
                }
            }
        }else if(isset($_SERVER['HTTP_FROM'])){
            return $_SERVER['HTTP_FROM'];
        }else{
            return $_SERVER['REMOTE_ADDR'];
        }
    }
	
	//服务器IP地址
	function serverIP(){   
	   return gethostbyname($_SERVER["SERVER_NAME"]);    
	   
	 } 

	/**
	 * 获得客户端的操作系统
	 *
	 * @access  private
	 * @return  void
	 */
	function get_os()
	{
		if (empty($_SERVER['HTTP_USER_AGENT']))
		{
			return 'Unknown';
		}
	
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$os    = '';
	
		if (strpos($agent, 'win') !== false)
		{
			if (strpos($agent, 'nt 5.1') !== false)
			{
				$os = 'Windows XP';
			}
			elseif (strpos($agent, 'nt 5.2') !== false)
			{
				$os = 'Windows 2003';
			}
			elseif (strpos($agent, 'nt 5.0') !== false)
			{
				$os = 'Windows 2000';
			}
			elseif (strpos($agent, 'nt 6.0') !== false)
			{
				$os = 'Windows Vista';
			}
			elseif (strpos($agent, 'nt') !== false)
			{
				$os = 'Windows NT';
			}
			elseif (strpos($agent, 'win 9x') !== false && strpos($agent, '4.90') !== false)
			{
				$os = 'Windows ME';
			}
			elseif (strpos($agent, '98') !== false)
			{
				$os = 'Windows 98';
			}
			elseif (strpos($agent, '95') !== false)
			{
				$os = 'Windows 95';
			}
			elseif (strpos($agent, '32') !== false)
			{
				$os = 'Windows 32';
			}
			elseif (strpos($agent, 'ce') !== false)
			{
				$os = 'Windows CE';
			}
		}
		elseif (strpos($agent, 'linux') !== false)
		{
			$os = 'Linux';
		}
		elseif (strpos($agent, 'unix') !== false)
		{
			$os = 'Unix';
		}
		elseif (strpos($agent, 'sun') !== false && strpos($agent, 'os') !== false)
		{
			$os = 'SunOS';
		}
		elseif (strpos($agent, 'ibm') !== false && strpos($agent, 'os') !== false)
		{
			$os = 'IBM OS/2';
		}
		elseif (strpos($agent, 'mac') !== false && strpos($agent, 'pc') !== false)
		{
			$os = 'Macintosh';
		}
		elseif (strpos($agent, 'powerpc') !== false)
		{
			$os = 'PowerPC';
		}
		elseif (strpos($agent, 'aix') !== false)
		{
			$os = 'AIX';
		}
		elseif (strpos($agent, 'hpux') !== false)
		{
			$os = 'HPUX';
		}
		elseif (strpos($agent, 'netbsd') !== false)
		{
			$os = 'NetBSD';
		}
		elseif (strpos($agent, 'bsd') !== false)
		{
			$os = 'BSD';
		}
		elseif (strpos($agent, 'osf1') !== false)
		{
			$os = 'OSF1';
		}
		elseif (strpos($agent, 'irix') !== false)
		{
			$os = 'IRIX';
		}
		elseif (strpos($agent, 'freebsd') !== false)
		{
			$os = 'FreeBSD';
		}
		elseif (strpos($agent, 'teleport') !== false)
		{
			$os = 'teleport';
		}
		elseif (strpos($agent, 'flashget') !== false)
		{
			$os = 'flashget';
		}
		elseif (strpos($agent, 'webzip') !== false)
		{
			$os = 'webzip';
		}
		elseif (strpos($agent, 'offline') !== false)
		{
			$os = 'offline';
		}
		else
		{
			$os = 'Unknown';
		}
	
		return $os;
	}
	
	/**
	 * 获得浏览器名称和版本
	 *
	 * @access  public
	 * @return  string
	 */
	function get_user_browser()
	{
		if (empty($_SERVER['HTTP_USER_AGENT']))
		{
			return '';
		}
	
		$agent       = $_SERVER['HTTP_USER_AGENT'];
		$browser     = '';
		$browser_ver = '';
	
		if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs))
		{
			$browser     = 'Internet Explorer';
			$browser_ver = $regs[1];
		}
		elseif (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs))
		{
			$browser     = 'FireFox';
			$browser_ver = $regs[1];
		}
		elseif (preg_match('/Maxthon/i', $agent, $regs))
		{
			$browser     = '(Internet Explorer ' .$browser_ver. ') Maxthon';
			$browser_ver = '';
		}
		elseif (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs))
		{
			$browser     = 'Opera';
			$browser_ver = $regs[1];
		}
		elseif (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs))
		{
			$browser     = 'OmniWeb';
			$browser_ver = $regs[2];
		}
		elseif (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs))
		{
			$browser     = 'Netscape';
			$browser_ver = $regs[2];
		}
		elseif (preg_match('/safari\/([^\s]+)/i', $agent, $regs))
		{
			$browser     = 'Safari';
			$browser_ver = $regs[1];
		}
		elseif (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs))
		{
			$browser     = '(Internet Explorer ' .$browser_ver. ') NetCaptor';
			$browser_ver = $regs[1];
		}
		elseif (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs))
		{
			$browser     = 'Lynx';
			$browser_ver = $regs[1];
		}
	
		if (!empty($browser))
		{
		   return addslashes($browser . ' ' . $browser_ver);
		}
		else
		{
			return 'Unknow browser';
		}
	}


    /**
     * 随机返回字符串
     * @param <type> $number
     * @param <type> $type 0表示字符中可能包含符号, 1表示字符只能为大写字母与数字, 2表示只能为数字
     * @return <type>
     */
    function random($number = 10, $type=0){
        switch ($type) {
            case '0':
                $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890`~!@#$%^&*()_+-';
                break;
            case '1':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
            case '2':
                $str = '1234567890';
                break;
        }
        $len = strlen($str);
        $output = substr($str, rand(0, $len-1), 1);
        for($i=1; $i<$number; $i++){
            $output .= substr($str, rand(0, $len-1), 1);
        }
        return $output;
    }

    /**
     * Sub string of english.
     *
     * @param string $str
     * @param int $start
     * @param int $number
     * @param string $end
     * @return string
     */
    function subeng($str, $start, $number, $end = null){
        $str     = strip_tags($str);
        $str     = eregi_replace('[ ]+', ' ', $str);
        $str     = trim($str);
        $parr    = array(',','.','?','!',' ');
        $i       = 0;
        $j       = 0;
        $start2  = 0;
        $len     = strlen($str);
        $charlen = 0;
        $prelen  = 0;
        $preend  = 0;
        while($j<$number && $i<$len){
            $char     = substr($str, $i, 1);
            $i       += 1;
            $charlen += 1;
            $prelen  += 1;
            if(!empty($end) && $end == $char){
                $prelen = 0;
                $preend = $i;
            }
            if($j == $start){
                $start2 = $i-$charlen;
            }
            if(in_array($char, $parr)){
                $j += 1;
                $charlen = 0;
            }
        }
        if($prelen <> 0 && !empty($end)){
            $i -= $prelen;
        }
        return substr($str, $start2, $i);
    }

    /**
     * Sub string of GBK
     *
     * @param string $string
     * @param int $length
     * @param string $end
     * @param string $char
     * @return string
     */
    function subzh($string, $length , $end = null, $char = 'UTF-8') {
        $string = iconv($char, 'GBK', $string);
        if (is_numeric($length)) {
            if (strlen($string) > $length) {
                if ($end) {
                    $length = $length - 3;
                    $addstr = '...';
                }
                for($i = 0; $i < $length; $i++) {
                    if (ord($string[$i]) > 127) {
                        $wordscut .= $string[$i].$string[$i + 1];
                        $i++;
                   }else {
                       $wordscut .= $string[$i];
                   }
                }
                return iconv('GBK', $char, $wordscut.$addstr);
            }
        }else {
            $len = strlen($string);
            $length = iconv($char, 'GBK', $length);
            $len2 = strlen($length);
            $tmpOrd = array();
            $strlen = 0;
            for($i=0; $i<$len2; $i++){
                $tmpOrd[] = ord($length{$i});
            }
            $size = count($tmpOrd);
            $ppArr = array();
            for($i = 0; $i < $len; $i++) {
                $strlen = $i+1;
                if (array_values($ppArr) == $tmpOrd) {
                    break 1;
                }else if(count($ppArr) >= $size) {
                    array_shift($ppArr);
                }
                $ppArr[] = ord($string{$i});
                //
                if (ord($string{$i}) > 127) {
                    if (array_values($ppArr) == $tmpOrd) {
                        break 2;
                    }else if(count($ppArr) >= $size) {
                        array_shift($ppArr);
                    }
                    $ppArr[] = ord($string{$i+1});
                    $wordscut .= $string{$i}.$string{$i + 1};
                    $i++;
               }else {
                   $wordscut .= $string{$i};
               }
            }
            unset($ppArr, $tmpOrd);
            if($end){
                $wordscut = substr($wordscut, 0, $strlen-$size);
            }
            return iconv('GBK', $char, $wordscut);
        }
        return iconv('GBK', $char, $string);
    }
	
	//截取字符
	function get_substr($con="",$len=20,$chart='utf-8'){
		if(!empty($con)){
			return  mb_substr($con,0,$len,$chart);
			//return (strlen($con) > $len*2) ? mb_substr($con,0,$len,$chart) : $con;
		}else{
			return "";
		}
	}
	
	//更好一点的字符串截图函数。编码:Utf-8
	function wordcut($str,$len,$add=true){
		if(strlen($str) < $len)
			{
				return $str;
			}
		else{
            $i = 0;
            $newword = '';
            while($i<$len){
                    if(ord($str[$i]) > 224){
                            $newword .= $str[$i].$str[$i+1].$str[$i+2];
                            $i = $i +3;
                    }
                    else if(ord($str[$i] > 192)){
                            $newword .= $str[$i].$str[$i+1];
                            $i = $i +2;
                    }
					else{
							$newword .= $str[$i];
							$i++;
					}
                }
            if($add){
                    return $newword.'...';
            }
            else
                return $newword;
        }
	}

    /**
     * Clear string
     *
     * Clear the html tag and "\n" char. and change "\n" to '<br/>';
     *
     * @param string $con
     * @return string
     */
    function html2br($con){
        $con = eregi_replace( '<br[ /]*>', "\n", trim($con) );
        $con = strip_tags($con);
        $con = eregi_replace( '[ ]+', ' ', $con );
        $con = eregi_replace( "[\n]+", "\n", $con );
        $con = trim($con);
        $con = nl2br($con);
        return $con;
    }

    /**
     * Change the array var to query string.
     *
     * @param array $arr The $_GET value.
     * @return string @example ?q=xxx&b=xxxx
     */
    function uri2str($arr){
        $uri = array();
        foreach($arr as $k=>$val){
            if(is_array($val)) {
                foreach($val as $k2=>$val2){
                    $uri[] = "{$k}[{$k2}]={$val2}";
                }
            }else {
                $uri[] = "{$k}={$val}";
            }
        }
        return '?'.implode('&', $uri);
    }

    /**
     * Check persent of english char in the string.
     *
     * Check the char is a-z or A-Z. and  return the persent of.
     *
     * @param string $string
     * @return int 0-100. this results is a persent.
     */
    function eng($string){
        $m = 0;
        $strlen = strlen($string);
        for($i=0;$i<$strlen;$i++){
            $ord = ord($string{$i});
            if( $ord <= 127 && $ord >= 0 )$m++;
        }
        return ceil($m / $strlen * 100);
    }

    /**
     * 过滤字符串
     *
     * 过滤一些非法的字符, "a-z", "A-Z", "0-9", "_" and "-"除外.
     *
     * @param string $str
     * @param string $ds
     * @return string
     */
    function slug($str, $ds = ''){
        return eregi_replace('[^a-zA-Z0-9_-]+', $ds, $str);
    }
    /**
     * Check string is a date or not.
     * @param string|date $date
     * @return bool
     */
    function isDate($date){
        $tmp = date('Y-m-d', strtotime($date));
        if($tmp == '1970-01-01') {
            return false;
        }else {
            return true;
        }
    }

    function is404($msg = null){
        header("HTTP/1.0 404 Not Found");
        if($msg){
            echo 'HTTP/1.0 404 Not Found';
        }
    }
	
    /*
     * 带"？"的分页函数
     */
    function getpage($tt = 0, $list = 500, $page = 1,$p='?page=',$ts=false){
		if ($tt <= 0) {
            /* $rt['showmes'] = '<a href="javascript:;">当前第'.$page.'/'.($tpn<1? $tpn+1 : $tpn).'页</a>';
             $rt['first']='<a href="javascript:;">首页</a>';
             $rt['prev']='<a href="javascript:;">上一页</a>';
             $rt['next']= '<a href="javascript:;">下一页</a>';
             $rt['Last']= '<a href="javascript:;">尾页</a>';
             return $rt;*/
			 return array();
        }
        $tpn = ceil($tt / $list);
		
        if ($tpn <= 1 || $tt<=$list) { 
            /* $rt['showmes'] = '<a href="javascript:;">当前第'.$page.'/'.($tpn<1? $tpn+1 : $tpn).'页</a>';
             $rt['first']='<a href="javascript:;">首页</a>';
             $rt['prev']='<a href="javascript:;">上一页</a>';
             $rt['next']= '<a href="javascript:;">下一页</a>';
             $rt['Last']= '<a href="javascript:;">尾页</a>';
             return $rt;*/
			  return array();
        }
        //
        $get=$_SERVER["REQUEST_URI"];
        if(@eregi('page',$get)){
           $p=@eregi_replace('page='.$_GET['page'],'page=',$get);
        }else{
           $ss=$_SERVER["QUERY_STRING"];
           $p= (empty($ss)) ? $get.'?page=' : $get.'&page=';
        }

        $fpage=($page-5 > 0 )? $page-5 : 1;
        if($page + 5 < $tpn){
                $lpage = $page + 5 ;
        }else{
                $lpage =$tpn;
                $fpage=$tpn-9;
        }
        $lpage = ($fpage == 1)? 10 : $lpage ;
		
		$rt['showmes'] = '<a class="pagepn">第'.$page.'/'.$tpn.'页</a>';
        $rt['first']='<a href="'.$p.'1">首页</a>';
        $rt['previ']=($page>=2) ? '<a href="'.$p.($page-1).'">上一页</a>' : '<a href="'.$p.'1">上一页</a>';
        for($i=(($fpage > 0)?$fpage : 1);$i<=$lpage;$i++){
            $tl= $page==1 ? 'class="this"' : '';
            $thisclass=($i==$page) ? 'class="this"' : $tl;
            if($ts ==true){
                 $rt['list'][$i]=$p.$i;
            }else{
                $rt[$i]='<a href="'.$p.$i.'" '.$thisclass.'>'.$i.'</a>';
            }
			  
       	}
        $rt['next']=($page != $tpn) ? '<a href="'.$p.($page+1).'">下一页</a>' : '<a href="'.$p.$tpn.'">下一页</a>';
        $rt['Last']='<a href="'.$p.$tpn.'">尾页</a>';
        if($ts ==true){
                return $rt;
        }else{
        return implode('  ',$rt);
        }
   }
   
   /*静态分页,用于ecshop*/
   function gethtmlpage($tt = 0, $list = 20, $page = 1,$p=''){
   	if(!$p) return $rt;
	if ($tt <= 0) {
            return $rt;
        }
        $tpn = ceil($tt / $list);
		
        if ($tpn <= 1) { 
            return $rt;
        }
        if($tt<=$list){
           return $rt;
        }
        //
        $fpage=($page-5 > 0 )? $page-5 : 1;
        if($page + 5 < $tpn){
                $lpage = $page + 5 ;
        }else{
                $lpage =$tpn;
                $fpage=$tpn-9;
        }
        $lpage = ($fpage == 1)? 10 : $lpage ;
		
	$rt['showmes'] = '<a class="pagepn">当前第'.$page.'/'.$tpn.'页</a>';
        $rt['first']='<a href="'.sprintf($p,1).'">首页</a>';
        $rt['prev']=($page>=2) ? '<a href="'.sprintf($p,$page-1).'">上一页</a>' : "<a>上一页</a>";
        for($i=(($fpage > 0)?$fpage : 1);$i<=$lpage;$i++){
           $rt['list'][$i]= sprintf($p,$i);
        }
        $rt['next']= ($page != $tpn) ? '<a href="'.sprintf($p,$page+1).'">下一页</a>' : "<a>下一页</a>";  
        $rt['Last']= '<a href="'.sprintf($p,$tpn).'">尾页</a>'; 
	return $rt;
      //  return implode('  ',$rt);
   }
   
   //用于ajax的分页 @$args 为参数 @type array
   function ajax_page($tt = 0, $list = 10, $page = 1,$ty='getpage',$args=array()){
   	$tpn = ceil($tt / $list);
   	if ($tt <= 0 || $tpn <= 1 || $tt<=$list) {
                $rt['showmes'] = '<a href="javascript:;">第'.$page.'/'.($tpn<1? $tpn+1 : $tpn).'页</a>';
                $rt['first']='<a href="javascript:;">首页</a>';
                $rt['prev']='<a href="javascript:;">上一页</a>';
                $rt['next']= '<a href="javascript:;">下一页</a>';
                $rt['last']= '<a href="javascript:;">尾页</a>';
                //return $rt;
				return array();
        }
        if(!empty($args)){
             $st =",'".implode("','",$args)."'";
        }else{
             $st = "";
        }
        $fpage=($page-5 > 0 )? $page-5 : 1;
        if($page + 5 < $tpn){
                $lpage = $page + 5 ;
        }else{
                $lpage =$tpn;
                $fpage=$tpn-9;
        }
        $lpage = ($fpage == 1)? 10 : $lpage ;
		
	$rt['showmes'] = '<a href="javascript:;">第'.$page.'/'.$tpn.'页</a>';
        $rt['first']= ($page==1) ? '<a href="javascript:;">首页</a>' : '<a href="javascript:;" onclick="'.$ty.'(\'1\''.$st.')">首页</a>';
        $rt['prev']=($page>=2) ? '<a href="javascript:;" onclick="'.$ty.'(\''.($page-1).'\''.$st.')">上一页</a>' : '<a href="javascript:;">上一页</a>';
        for($i=(($fpage > 0)?$fpage : 1);$i<=$lpage;$i++){
           $rt['list'][$i]= '<a href="javascript:;" '.($page==$i?'class="pagelist thispage"':'class="pagelist"').' onclick="'.$ty.'(\''.$i.'\''.$st.')">'.$i.'</a>';
        }
        $rt['next']= ($page != $tpn) ? '<a href="javascript:;" onclick="'.$ty.'(\''.($page+1).'\''.$st.')">下一页</a>' : '<a href="javascript:;">下一页</a>';
        $rt['last']= ($page==$tpn) ? '<a href="javascript:;">尾页</a>' : '<a href="javascript:;" onclick="'.$ty.'(\''.$tpn.'\''.$st.')">尾页</a>';
	return $rt;
   }
   
   //copy =>function gethtmlpage
    function copyhtmlpage($tt = 0, $list = 500, $page = 1,$p=''){
   	   if(!$p) return "";
		if ($tt <= 0) {
            return null;
        }
        $tpn = ceil($tt / $list);
		
        if ($tpn <= 1) { 
            return null;
        }
        if($tt<=$list){
           return null;
        }
        //
        $fpage=($page-5 > 0 )? $page-5 : 1;
        if($page + 5 < $tpn){
                $lpage = $page + 5 ;
        }else{
                $lpage =$tpn;
                $fpage=$tpn-9;
        }
        $lpage = ($fpage == 1)? 10 : $lpage ;
		
        $rt['First']='<a href="'.sprintf($p,1).'">First</a>';
        $rt['Prev']=($page>=2) ? '<a href="'.sprintf($p,$page-1).'">Previous</a>' : '<a href="'.sprintf($p,1).'">Previous</a>';
        for($i=(($fpage > 0)?$fpage : 1);$i<=$lpage;$i++){
           $thisclass="";
           $thisclass=($i==$page) ? 'class="this"' : "";
           $rt[$i]='<a href="'.sprintf($p,$i).'" '.$thisclass.'>'.$i.'</a>';
        }
        $rt['Next']=($page != $tpn) ? '<a href="'.sprintf($p,$page+1).'">Next</a>' : '<a href="'.sprintf($p,$tpn).'">Next</a>';
        $rt['Last']='<a href="'.sprintf($p,$tpn).'">Last</a>';
        return implode('  ',$rt);
   }
   /*二维数组的排序函数*/
	function array_sort($arr,$keys,$type='asc'){
			if(empty($arr)) return array();
			$keysvalue = $new_array = array();
			foreach ($arr as $k=>$v){
				$keysvalue[$k] = $v[$keys];
			}
			if($type == 'asc'){
				asort($keysvalue);
			}else{
				arsort($keysvalue);
			}
			reset($keysvalue);
			foreach ($keysvalue as $k=>$v){
				$new_array[$k] = $arr[$k];
			}
			return $new_array;
	}
	
	//html ==> String
	function html2str($html){
    return str_replace(array('\"', "\'", '<', '>'), array('&quot;', '&quoc;', '&lt;', '&gt;'), $html);
    }
   //string ==> Html
	function str2html($str){
		return str_replace(array('&quot;', '&quoc;', '&lt;', '&gt;'), array('"', "'", '<', '>'), $str);
	}
	
	function clearhtml($str=""){
		$str = str_replace(array(':','：','。','.','“','？','!','！','$','%','>','<','&','》','《','*','^','@','~','	',' '),'',$str);
		$str = strip_tags($str);
		$str = htmlspecialchars($str);
		return $str;
	}
	
	//中文转为拼音函数
	################################
	function Pinyin($_String, $_Code='UTF-8') 
	{
            if(empty($_String)) return "";
			$_String = $this->clearhtml($_String);
            $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
                            "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
                            "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
                            "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
                            "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
                            "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
                            "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
                            "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
                            "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
                            "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
                            "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
                            "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
                            "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
                            "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
                            "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
                            "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";

            $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
                            "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
                            "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
                            "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
                            "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
                            "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
                            "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
                            "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
                            "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
                            "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
                            "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
                            "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
                            "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
                            "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
                            "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
                            "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
                            "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
                            "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
                            "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
                            "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
                            "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
                            "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
                            "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
                            "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
                            "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
                            "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
                            "|-10270|-10262|-10260|-10256|-10254";
            $_TDataKey   = explode('|', $_DataKey);
            $_TDataValue = explode('|', $_DataValue);

            $_Data = (PHP_VERSION>='5.0') ? array_combine($_TDataKey, $_TDataValue) : $this->_Array_Combine($_TDataKey, $_TDataValue);
            arsort($_Data);
            reset($_Data);

            if($_Code != 'gb2312') $_String = $this->_U2_Utf8_Gb($_String);
            $_Res = '';
            for($i=0; $i<strlen($_String); $i++)
            {
                $_P = ord(substr($_String, $i, 1));
                if($_P>160){
                        $_Q =  ord(substr($_String, ++$i, 1));
                        $_P = $_P*256 + $_Q - 65536;
                }

                $_Res .=$this->_Pinyin($_P, $_Data);
            }

            $cls_str=array("!","@","#","$","%","^","&","*","(",")","~","<",">","{","}","[","]","?",",",".","/","\\","|","+"," ");
            $str = str_replace($cls_str,"",$_Res);//preg_replace("/[^a-z0-9]*/", '', $_Res);
			return (strlen($str) > 40) ? substr($str,0,40) : $str;
	} 
	
	function _Pinyin($_Num, $_Data) 
	{ 
			if    ($_Num>0      && $_Num<160   ) return chr($_Num); 
			elseif($_Num<-20319 || $_Num>-10247) return ''; 
			else { 
					foreach($_Data as $k=>$v){ if($v<=$_Num) break; } 
				   // return "_".$k;
				   return $k;
			} 
	} 
	
	function _U2_Utf8_Gb($_C) 
	{ 
			$_String = ''; 
			if($_C < 0x80) $_String .= $_C; 
			elseif($_C < 0x800) 
			{ 
					$_String .= chr(0xC0 | $_C>>6); 
					$_String .= chr(0x80 | $_C & 0x3F); 
			}elseif($_C < 0x10000){ 
					$_String .= chr(0xE0 | $_C>>12); 
					$_String .= chr(0x80 | $_C>>6 & 0x3F); 
					$_String .= chr(0x80 | $_C & 0x3F); 
			} elseif($_C < 0x200000) { 
					$_String .= chr(0xF0 | $_C>>18); 
					$_String .= chr(0x80 | $_C>>12 & 0x3F); 
					$_String .= chr(0x80 | $_C>>6 & 0x3F); 
					$_String .= chr(0x80 | $_C & 0x3F); 
			} 
			return iconv('UTF-8', 'GB2312', $_String); 
	} 
	
	function _Array_Combine($_Arr1, $_Arr2) 
	{ 
			for($i=0; $i<count($_Arr1); $i++) $_Res[$_Arr1[$i]] = $_Arr2[$i]; 
			return $_Res; 
	} 

//调用:echo Pinyin('你好吗'); //默认是gb编码

/*
*验证、过滤方法
*/
	//验证用户名是否是数字或字符串
	function username_preg($username=""){
		if(empty($username)) return false;
		if (preg_match("#^[a-zA-Z0-9]{6,16}$#", $username)) { 
		    return true;
		}else{
			return false;
		}
	}
	//email验证
	function email_preg($email=""){
		if(empty($email)) return false;
		if (preg_match("#\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*#", $email)) { 
		    return true;
		}else{
			return false;
		}
	}
	
	//sql语句过滤
	function sql_check($str){   // 进行过滤方法
		if(eregi('select|insert|update|delete|and|execute|count|chr|mid|master|truncate|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file
		|outfile', $str)){
			return false;
		}else{
			return true;
		}
   }
   

	//正整数
	function int_preg($d=0){
		if(empty($d)) return false;
		if (preg_match("#^[0-9]*[1-9][0-9]*$#", $d)) { 
		    return true;
		}else{
			return false;
		}
	}
	
	//IP
	function ip_preg($ip=0){
		if(empty($ip)) return false;
		if (preg_match("#(\d+)\.(\d+)\.(\d+)\.(\d+)#", $ip)) { 
		    return true;
		}else{
			return false;
		}
	}
	
	//电话
	function tel_preg($tel=""){
		if(empty($tel)) return false;
		if (preg_match("#^((\+?[0-9]{2,4}\-[0-9]{3,4}\-)|([0-9]{3,4}\-))?([0-9]{7,8})(\-[0-9]+)?$#", $tel)) { 
		    return true;
		}else{
			return false;
		}
	}
	
	//URL
	function url_preg($url=""){
		if(empty($url)) return false;
		if (preg_match("#^[a-zA-z]+://(\\w+(-\\w+)*)(\\.(\\w+(-\\w+)*))*(\\?\\S*)?$#", $url)) { 
		    return true;
		}else{
			return false;
		}
	}
	//日期
	function date_preg($date=""){
		if(empty($date)) return false;
		if (preg_match("#^((0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)?[0-9]{2})*$#", $date)) { 
		    return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 将JSON传递的参数转码
	 *
	 * @param string $str
	 * @return string
	 */
	function json_str_iconv($str)
	{
		if(!defined(SITE_CHARSET)) return $str; 
		
		if (SITE_CHARSET != 'utf-8')
		{
			$iconv = Import::gz_iconv(); //编码对象
			if (is_string($str))
			{
				return $iconv->ec_iconv('utf-8', SITE_CHARSET, $str);
			}
			elseif (is_array($str))
			{
				foreach ($str as $key => $value)
				{
					$str[$key] = Basic::json_str_iconv($value);
				}
				return $str;
			}
			elseif (is_object($str))
			{
				foreach ($str as $key => $value)
				{
					$str->$key = Basic::json_str_iconv($value);
				}
				return $str;
			}
			else
			{
				return $str;
			}
		}
		return $str;
	}
	
	/**
	 * 将对象成员变量或者数组的特殊字符进行转义
	 *
	 * @access   public
	 * @param    mix        $obj      对象或者数组
	 * @author   Xuan Yan
	 *
	 * @return   mix                  对象或者数组
	 */
	function addslashes_deep_obj($obj)
	{ 
		if (is_object($obj) == true)
		{
			foreach ($obj AS $key => $val)
			{
				$obj->$key = Basic::addslashes_deep($val);
			}
		}
		else
		{
			$obj = Basic::addslashes_deep($obj);
		}
	
		return $obj;
	}
	
	//转义字符
	//if(!function_exists('addslashes_deep')){
	/*function addslashes_deep($value)
	{
		if (empty($value)||!is_array($value))
		{
			return $value;
		}
		else
		{ 
			return is_array($value) ? Basic::addslashes_deep($value) : addslashes($value);
		}
	}*/
	//}
	function addslashes_deep($value){
        if(!is_array($value)){
		  	return (!empty($value)) ? addslashes($value) : $value;
        }else{
			 if(empty($value)) return $value;
			 else{
			   foreach($value as $key=>$val){
				 $arr[$key] = (!empty($val)) ? Basic::addslashes_deep($val) : $val;
			   }
			 }
			 return $arr;
	  	}
    }

	//错误信息记录
	function log_write($arg, $file = '', $line = '')
	{
	
		$str = "\r\n-- ". date('Y-m-d H:i:s'). " --------------------------------------------------------------\r\n";
		$str .= "FILE: $file\r\nLINE: $line\r\n";
	
		if (is_array($arg))
		{
			$str .= '$arg = array(';
			foreach ($arg AS $val)
			{
				foreach ($val AS $key => $list)
				{
					$str .= "'$key' => '$list'\r\n";
				}
			}
			$str .= ")\r\n";
		}
		else
		{
			$str .= $arg;
		}
	
		file_put_contents(SYS_PATH .'data/log.txt', $str);
	}
	
	/**	
	*判断是否是通过手机访问	
	*/	
	function isMobile(){	
		
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备	
		
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {	
		
			return true;	
		
		}	
		
		//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息	
		
		if (isset ($_SERVER['HTTP_VIA'])) {	
		
			//找不到为flase,否则为true	
		
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;	
		
		}	
		
		//判断手机发送的客户端标志,兼容性有待提高	
		
		if (isset ($_SERVER['HTTP_USER_AGENT'])) {	
		
			$clientkeywords = array (	
			
			'nokia',	
			
			'sony',	
			
			'ericsson',	
			
			'mot',	
			
			'samsung',	
			
			'htc',	
			
			'sgh',	
			
			'lg',	
			
			'sharp',	
			
			'sie-',	
			
			'philips',	
			
			'panasonic',	
			
			'alcatel',	
			
			'lenovo',	
			
			'iphone',	
			
			'ipod',	
			
			'blackberry',	
			
			'meizu',	
			
			'android',	
			
			'netfront',	
			
			'symbian',	
			
			'ucweb',	
			
			'windowsce',	
			
			'palm',	
			
			'operamini',	
			
			'operamobi',	
			
			'openwave',	
			
			'nexusone',	
			
			'cldc',	
			
			'midp',	
			
			'wap',	
			
			'mobile'	
			
			);	
		
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字	
		
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {	
		
				return true;	
			}	
	
		}	
	
		//协议法，因为有可能不准确，放到最后判断	
	
		if (isset ($_SERVER['HTTP_ACCEPT'])) {	
	
			// 如果只支持wml并且不支持html那一定是移动设备	
			
			// 如果支持wml和html但是wml在html之前则是移动设备	
			
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) 
			&& (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || 
			(strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < 
			strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {	
				return true;	
			}	
		
		}	
	
		return false;	
	}

}
?>