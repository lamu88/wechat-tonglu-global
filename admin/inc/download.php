<?php 
/*
$gurl 要下载的图片地址
$rfurl 来路。如果目标图像做了防盗链设置，可以绕过。
$filename 下载图片保存的文件名，相对路径，不要用realpath
$gcookie 调整cookie 伪造的cookie
$JumpCount 跳转计数
$maxtime 最大次数
调用方法：DownImageKeep(“http://www.baidu.com/img/baidu_jgylogo2.gif”,”http://baidu.com”,”a.gif”,”",0,10);
*/
function DownImageKeep($gurl, $rfurl, $filename, $gcookie="", $JumpCount=0, $maxtime=30)  
{  
    $urlinfos = GetHostInfo($gurl);  
    $ghost = trim($urlinfos['host']);  
    if($ghost=='')  
    {  
        return FALSE;  
    }  
    $gquery = $urlinfos['query'];  
    if($gcookie=="" && !empty($rfurl))  
    {  
        $gcookie = RefurlCookie($rfurl);  
    }  
    $sessionQuery = "GET $gquery HTTP/1.1\r\n";  
    $sessionQuery .= "Host: $ghost\r\n";  
    $sessionQuery .= "Referer: $rfurl\r\n";  
    $sessionQuery .= "Accept: */*\r\n";  
    $sessionQuery .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)\r\n";  
    if($gcookie!="" && !preg_match("/[\r\n]/", $gcookie))  
    {  
        $sessionQuery .= $gcookie."\r\n";  
    }  
    $sessionQuery .= "Connection: Keep-Alive\r\n\r\n";  
    $errno = "";  
    $errstr = "";  
    $m_fp = fsockopen($ghost, 80, $errno, $errstr,10);  
    fwrite($m_fp,$sessionQuery);  
    $lnum = 0;  
  
    //获取详细应答头  
    $m_httphead = Array();  
    $httpstas = explode(" ",fgets($m_fp,256));  
    $m_httphead["http-edition"] = trim($httpstas[0]);  
    $m_httphead["http-state"] = trim($httpstas[1]);  
    while(!feof($m_fp))  
    {  
        $line = trim(fgets($m_fp,256));  
        if($line == "" || $lnum>100)  
        {  
            break;  
        }  
        $hkey = "";  
        $hvalue = "";  
        $v = 0;  
        for($i=0; $i<strlen($line); $i++)  
        {  
            if($v==1)  
            {  
                $hvalue .= $line[$i];  
            }  
            if($line[$i]==":")  
            {  
                $v = 1;  
            }  
            if($v==0)  
            {  
                $hkey .= $line[$i];  
            }  
        }  
        $hkey = trim($hkey);  
        if($hkey!="")  
        {  
            $m_httphead[strtolower($hkey)] = trim($hvalue);  
        }  
    }  
  
    //分析返回记录  
    if(preg_match("/^3/", $m_httphead["http-state"]))  
    {  
        if(isset($m_httphead["location"]) && $JumpCount<3)  
        {  
            $JumpCount++;  
            DownImageKeep($gurl,$rfurl,$filename,$gcookie,$JumpCount);  
        }  
        else  
        {  
            return FALSE;  
        }  
    }  
    if(!preg_match("/^2/", $m_httphead["http-state"]))  
    {  
        return FALSE;  
    }  
    if(!isset($m_httphead))  
    {  
        return FALSE;  
    }  
    $contentLength = $m_httphead['content-length'];  
  
    //保存文件  
    $fp = fopen($filename,"w") or die("写入文件：{$filename} 失败！");  
    $i=0;  
    $okdata = "";  
    $starttime = time();  
    while(!feof($m_fp))  
    {  
        $okdata .= fgetc($m_fp);  
        $i++;  
  
        //超时结束  
        if(time()-$starttime>$maxtime)  
        {  
            break;  
        }  
  
        //到达指定大小结束  
        if($i >= $contentLength)  
        {  
            break;  
        }  
    }  
    if($okdata!="")  
    {  
        fwrite($fp,$okdata);  
    }  
    fclose($fp);  
    if($okdata=="")  
    {  
        @unlink($filename);  
        fclose($m_fp);  
        return FALSE;  
    }  
    fclose($m_fp);  
    return TRUE;  
}  
  
/**  
 *  获得某页面返回的Cookie信息  
 *  
 * @access    public  
 * @param     string  $gurl  调整地址  
 * @return    string  
 */  
function RefurlCookie($gurl)  
{  
    global $gcookie,$lastRfurl;  
    $gurl = trim($gurl);  
    if(!empty($gcookie) && $lastRfurl==$gurl)  
    {  
        return $gcookie;  
    }  
    else  
    {  
        $lastRfurl=$gurl;  
    }  
    if(trim($gurl)=='')  
    {  
        return '';  
    }  
    $urlinfos = GetHostInfo($gurl);  
    $ghost = $urlinfos['host'];  
    $gquery = $urlinfos['query'];  
    $sessionQuery = "GET $gquery HTTP/1.1\r\n";  
    $sessionQuery .= "Host: $ghost\r\n";  
    $sessionQuery .= "Accept: */*\r\n";  
    $sessionQuery .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.00; Windows 98)\r\n";  
    $sessionQuery .= "Connection: Close\r\n\r\n";  
    $errno = "";  
    $errstr = "";  
    $m_fp = fsockopen($ghost, 80, $errno, $errstr,10) or die($ghost.'<br />');  
    fwrite($m_fp,$sessionQuery);  
    $lnum = 0;  
  
    //获取详细应答头  
    $gcookie = "";  
    while(!feof($m_fp))  
    {  
        $line = trim(fgets($m_fp,256));  
        if($line == "" || $lnum>100)  
        {  
            break;  
        }  
        else  
        {  
            if(preg_match("/^cookie/i", $line))  
            {  
                $gcookie = $line;  
                break;  
            }  
        }  
    }  
    fclose($m_fp);  
    return $gcookie;  
}  
  
/**  
 *  获得网址的host和query部份  
 *  
 * @access    public  
 * @param     string  $gurl  调整地址  
 * @return    string  
 */  
function GetHostInfo($gurl)  
{  
    $gurl = preg_replace("/^http:\/\//i", "", trim($gurl));  
    $garr['host'] = preg_replace("/\/(.*)$/i", "", $gurl);  
    $garr['query'] = "/".preg_replace("/^([^\/]*)\//i", "", $gurl);  
    return $garr;  
}  ?>