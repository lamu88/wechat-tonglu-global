<?php
class tools 
{
	function txt_substr($str,$start,$len)//截取字符串
	{
		$strlen=$start+$len;
		for($i=0;$i<$strlen;$i++)
		{
			if(ord(substr($str,$i,1))>0xa0)
			{
				$tmpstr.=substr($str,$i,2);
				$i++;
			}
			else 
			{
				$tmpstr.=substr($str,$i,1);
			}
		}
		return $tmpstr;
	}
	function txt_substr_length($string,$length = 80,$etc = '...') //截取字符串，超过部分用...显示
	{
		if ($length == 0) return '';
		if (strlen($string) >$length) 
		{
			$length -= min($length,strlen($etc));
			for($i = 0;$i <$length ;$i++) 
			{
				$strcut .= ord($string[$i]) >127 ?$string[$i].$string[++$i] : $string[$i];
			}
			return $strcut.$etc;
		}
		else 
		{
			return $string;
		}
	}
	function html_replace($content)//替换字符串，替换掉html代码
	{
		$content=htmlspecialchars($content);
		$content=str_replace(chr(13),"<br>",$content);
		$content=str_replace(chr(32),"&nbsp;",$content);
		$content=str_replace("[_[","<",$content);
		$content=str_replace(")_)",">",$content);
		$content=str_replace("|_|","",$content);
		return trim($content);
	}
	function sql_mag_gpc($str) //sql魔法标签
	{
		if(get_magic_quotes_gpc()==1) return $str;
		else return addslashes($str);
	}
	function get_random ($length) //获取指定长度的随机字符串
	{
		$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$result = '';
		$l = strlen($str);
		for($i = 0;$i <$length;$i++) 
		{
			$num = rand(0,$l-1);
			$result .= $str[$num];
		}
		return $result;
	}
	function create_dirs($dir)//创建目录
	{
		return is_dir($dir) or ($this->create_dirs(dirname(__FILE__)) and mkdir($dir,0777));
	}
	function copy_file($fileUrl,$aimUrl) //复制文件
	{
		if (!file_exists($fileUrl)) 
		{
			return false;
		}
		if(file_exists($aimUrl)) 
		{
			@unlink($aimUrl);
		}
		copy($fileUrl,$aimUrl);
		return true;
	}
	function get_files_endname($file_name) //获取扩展名
	{
		$extend =explode(".",$file_name);
		$va=count($extend)-1;
		return $extend[$va];
	}
	function get_file_starname($file_name) //获取文件名（去除扩展名）
	{
		$extend =explode(".",$file_name);
		return $extend[0];
	}
	function upload_img($img,$imgname,$filepath,$maxfilesize=2)//上传图片
	{
		$fileType=array('jpg','gif','png','JPG','GIF','PNG');
		if(!in_array(substr($img['name'],-3,3),$fileType)) die("<script>alert('不允许上传该类型的文件！');history.back();</script>");
		if(strpos($img['type'],'image')===false) die("<script>alert('不允许上传该类型的文件！');history.back();</script>");
		if($img['size']>$maxfilesize*1024000) die( "<script>alert('文件过大！');history.back();</script>");
		if($img['error'] !=0) die("<script>alert('未知错误，文件上传失败！');history.back();</script>");
		if(@move_uploaded_file($img['tmp_name'],$filepath.$imgname))
		{
			$string='图片上传成功！';
		}
		else 
		{
			$string= '图片上传失败';
		}
	}
	function get_ip()//获取客户IP
	{
		if($_SERVER['HTTP_CLIENT_IP'])
		{
			$onlineip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif($_SERVER['HTTP_X_FORWARDED_FOR'])
		{
			$onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$onlineip=$_SERVER['REMOTE_ADDR'];
		}
		if($onlineip=='::1')
		{
			$onlineip='127.0.0.1';
		}
		return $onlineip;
	}
	function get_url_query()//
	{
		$urls = parse_url($_SERVER['REQUEST_URI']);
		$url = $urls['path'];
		$urlquery = $urls['query'];
		return $urlquery;
	}
	function get_url_self()
	{
		$php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
		return $php_self;
	}
	function get_img_erweima($chl,$widhtHeight ='150',$EC_level='L',$margin='0') 
	{
		$chl = urlencode($chl);
		echo '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl.'" alt="QR code" widhtHeight="'.$widhtHeight.'" widhtHeight="'.$widhtHeight.'"/>';
	}
	function check_is_weixin()//检查是否是微信端访问
	{
		if ( strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false ) 
		{
			return true;
		}
		return false;
	}
	function http_curl_get($url) //发送curl请求，get方式
	{
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_TIMEOUT,5000);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt ($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
	function http_curl_post($url,$data = null) //发送curl请求，post方式
	{
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		if (!empty($data))
		{
			curl_setopt($curl,CURLOPT_POST,1);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	function get_dirs_img($path)//获取路径下所有的图片，返回数组
	{
		if(!is_dir($path)) return;
		$handle = opendir($path);
		$files = array();
		while(false !== ($file = readdir($handle)))
		{
			if($file != '.'&&$file!='..')
			{
				$path2= $path.'/'.$file;
				if(is_dir($path2))
				{
					$this->get_dirs_img($path2);
				}
				else
				{
					if(preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i",$file))
					{
						$files[] = $file;
					}
				}
			}
		}
		return $files;
	}
	function create_file($name,$path,$content) //创建文件
	{
		$toppath=$path.$name;
		$Ts=fopen($toppath,"a+");
		fputs($Ts,$content."\r\n");
		fclose($Ts);
	}
	function send_mail($femail,$fpass,$fsmtp,$to,$subject = 'No subject',$body) //发送邮件
	{
		$loc_host = "test";
		$smtp_acc = $femail;
		$smtp_pass=$fpass;
		$smtp_host=$fsmtp;
		$from=$femail;
		$headers = "Content-Type: text/plain; charset=\"gb2312\"\r\nContent-Transfer-Encoding: base64";
		$lb="\r\n";
		$hdr = explode($lb,$headers);
		if($body) 
		{
			$bdy = preg_replace("/^\./","..",explode($lb,$body));
		}
		$smtp = array( array("EHLO ".$loc_host.$lb,"220,250","HELO error: "), array("AUTH LOGIN".$lb,"334","AUTH error:"), array(base64_encode($smtp_acc).$lb,"334","AUTHENTIFICATION error : "), array(base64_encode($smtp_pass).$lb,"235","AUTHENTIFICATION error : "));
		$smtp[] = array("MAIL FROM: <".$from.">".$lb,"250","MAIL FROM error: ");
		$smtp[] = array("RCPT TO: <".$to.">".$lb,"250","RCPT TO error: ");
		$smtp[] = array("DATA".$lb,"354","DATA error: ");
		$smtp[] = array("From: ".$from.$lb,"","");
		$smtp[] = array("To: ".$to.$lb,"","");
		$smtp[] = array("Subject: ".$subject.$lb,"","");
		foreach($hdr as $h) 
		{
			$smtp[] = array($h.$lb,"","");
		}
		$smtp[] = array($lb,"","");
		if($bdy) 
		{
			foreach($bdy as $b) 
			{
				$smtp[] = array(base64_encode($b.$lb).$lb,"","");
			}
		}
		$smtp[] = array(".".$lb,"250","DATA(end)error: ");
		$smtp[] = array("QUIT".$lb,"221","QUIT error: ");
		$fp = @fsockopen($smtp_host,25);
		if (!$fp) echo "Error: Cannot conect to ".$smtp_host."";
		while($result = @fgets($fp,1024))
		{
			if(substr($result,3,1) == " ") 
			{
				break;
			}
		}
		$result_str="";
		foreach($smtp as $req)
		{
			@fputs($fp,$req[0]);
			if($req[1])
			{
				while($result = @fgets($fp,1024))
				{
					if(substr($result,3,1) == " ") 
					{
						break;
					}
				}
				;
				if (!strstr($req[1],substr($result,0,3)))
				{
					$result_str.=$req[2].$result."";
				}
			}
		}
		@fclose($fp);
		return $result_str;
	}
}
?>