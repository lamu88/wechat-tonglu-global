<?php
require_once(SYS_PATH.'inc'.DS.'lang.php');

//Common::check_isMobile();

//if(!isset($GLOBALS['LANG'])){
	$GLOBALS['LANG'] = Common::get_site_mes();
//}

$GLOBALS['LANG']['custome_phone'] = !empty($GLOBALS['LANG']['custome_phone']) ? @explode(',',$GLOBALS['LANG']['custome_phone']) : "";
$GLOBALS['LANG']['custome_qq'] = !empty($GLOBALS['LANG']['custome_qq']) ? @explode(',',$GLOBALS['LANG']['custome_qq']) : "";
//$GLOBALS['LANG']['tongjicode'] = !empty($GLOBALS['LANG']['tongjicode']) ? Import::basic()->str2html($GLOBALS['LANG']['tongjicode']) : ""; //转换为html代码

//网站是否开放
if(!$GLOBALS['LANG']['is_open'] || empty($GLOBALS['LANG']['is_open'])){
	die($GLOBALS['LANG']['close_desc']);
}

$lang = $GLOBALS['LANG'];

//记录前一个页面
if(!empty($_SERVER['PHP_SELF'])){
	$index = basename($_SERVER['PHP_SELF']);
	if($index!='user.php' && $index!='captcha.php' &&!strpos($_SERVER['PHP_SELF'],'ajaxfile') &&!strpos($_SERVER['PHP_SELF'],'admin/')){
		 $_this->Session->write('REFERER',(!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : Import::basic()->thisurl()));
	}
}

//自动登录
//Common::is_auto_login($_this->action('user','is_login'));
$thisurl = Basic::siteurl();
if(strpos($thisurl,'paywx') == false && strpos($thisurl,'WxPay') == false){ $_this->action('common','user_auto_login'); }//用户自动登录
$lang['shareinfo'] = $_this->action('common','get_share_user_info'); //如果当前用户没有关注，那么返回分享用户信息
$lang['dailiinfo'] = $_this->action('common','get_daili_info');
$lang['userinfo'] = $_this->action('common','get_user_info');
$rr = $_this->action('common','_get_jsapi_ticket');
$lang['jsapi_ticket'] = $rr['jsapi_ticket'];
$lang['appid'] = $rr['appid'];

//网站导航菜单
$lang['menu'] = $_this->action('catalog','get_goods_cate_tree');
//$lang['navlist_top']= Common::get_site_nav('top');
$lang['navlist_middle'] = Common::get_site_nav('middle');
//$lang['navlist_footer'] = Common::get_site_nav('bottom');
//$lang['lis_website'] = Common::get_lis_website();
//$lang['goods_history'] = Common::history_view(); //商品浏览历史
//$lang['help_article'] = Common::help_article();
//$lang['search_keys'] = Common::get_search_keywords();
$lang['lang'] = $_LANG;
if(!empty($lang['dailiinfo'])){
	if(isset($lang['dailiinfo']['sitename'])&&!empty($lang['dailiinfo']['sitename'])){
		 $GLOBALS['LANG']['site_title'] = $GLOBALS['LANG']['site_title'].$lang['dailiinfo']['sitename'];
	}
	if(isset($lang['dailiinfo']['logo'])&&!empty($lang['dailiinfo']['logo'])){
		$lang['site_logo'] = $lang['dailiinfo']['logo'];
	}
	if(isset($lang['dailiinfo']['sitetitle'])&&!empty($lang['dailiinfo']['sitetitle'])){
		$lang['metatitle'] = $GLOBALS['LANG']['site_title'];
	}
	if(isset($lang['dailiinfo']['metadesc'])&&!empty($lang['dailiinfo']['metadesc'])){
		$lang['metadesc'] = $lang['dailiinfo']['metadesc'];
	}
}
$str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVW";
$code = '';
for ($i=0; $i<16; $i++){
		$code.= $str[mt_rand(0, strlen($str)-1)];
}
$lang['nonceStr'] = $code;
$_this->set('lang',$lang);

//获取网站基本信息,好像这里已经没有用到，数据都是从文件读出来的
class Common{
		//相对于网站根目录的链接
        function class_url(){
                   return Basic::siteurl();
        }
		function _return_px(){
			   $t = '';
			   $x = $_SERVER["HTTP_HOST"];
			   $x1 = explode('.',$x);
			   if(count($x1)==2){
				 $t = $x1[0];
			   }elseif(count($x1)>2){
				 $t =$x1[0].$x1[1];
			   }
			   return $t;
		}
		
		function UserAgent(){
			$user_agent = ( !isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];
			return $user_agent;
		}

		function check_isMobile(){
			/*$site = Basic::thisurl();
			$istrue = Import::basic()->isMobile();
			if($istrue==true && !strpos($site,'ajaxfile') && !strpos($site,'user.php')){
				$this->jump(SITE_URL.'m/');
				exit;
			}*/
			if ((preg_match("/(iphone|ipod|android)/i", strtolower(Common::UserAgent()))) AND strstr(strtolower(Common::UserAgent()), 'webkit')){
				//手机
			}else if(trim(Common::UserAgent()) == '' OR preg_match("/(nokia|sony|ericsson|mot|htc|samsung|sgh|lg|philips|lenovo|ucweb|opera mobi|windows mobile|blackberry)/i", strtolower(Common::UserAgent()))){
				//手机
			}else{//PC
				die("只能在手机端打开！");
			}
		}
		
        function get_site_mes(){
				
				   $t = Common::_return_px();
				   $cache = Import::ajincache();
                   $cache->SetFunction(__FUNCTION__);
                   $cache->SetMode('sitemes'.$t);
                   $fn = $cache->fpath(func_get_args());
                   if(file_exists($fn)&&!$cache->GetClose()){
                                include($fn);
                   }
                   else
                   {
                                $sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` LIMIT 1";
                                $rt = $this->App->findrow($sql);
								
                                $cache->write($fn, $rt,'rt');
                   }
                return $rt;
        }

        //公司旗下网站
        function get_lis_website(){
			 	   $t = Common::_return_px();
				   $cache = Import::ajincache();
                   $cache->SetFunction(__FUNCTION__);
                   $cache->SetMode('page'.$t);
                   $fn = $cache->fpath(func_get_args());
                   if(file_exists($fn)&&!$cache->GetClose()){
                                include($fn);
                   }
                   else
                   {
                                $rt = $this->App->find("SELECT * FROM `{$this->App->prefix()}lts_site`");
                                $cache->write($fn, $rt,'rt');
                   }
                   return $rt;

        }

        //获取网站的导航菜单
        function get_site_nav($t='middle'){
				   $ts = Common::_return_px();
				   $cache = Import::ajincache();
                   $cache->SetFunction(__FUNCTION__);
                   $cache->SetMode('sitemes'.$ts);
                   $fn = $cache->fpath(func_get_args());
                   if(file_exists($fn)&&!$cache->GetClose()){
                                include($fn);
                   }
                   else
                   {
                                $rts = array();
                                $sql = "SELECT * FROM `{$this->App->prefix()}nav` WHERE is_show = '1' AND type = '$t' ORDER BY vieworder ASC, id ASC";
                                $rt = $this->App->find($sql);
								$tr = explode('.',basename($_SERVER['PHP_SELF']));
                                if(!empty($rt)){
                                        $site = Common::class_url();
                                        foreach($rt as $row){
												$dtr[0] = "";
												if( !empty($row['url']) && strpos($row['url'],'.') ) $dtr = explode('.',basename($row['url']));
												if(isset($tr[0]) && $tr[0]== $dtr[0]) $row['active'] = 1; else $row['active'] = 0;
                                                $row['url'] = $row['url'];
												$rts[] = $row;
                                        }
                                        unset($rt);
                                }
                                $cache->write($fn, $rts,'rts');
                   }
                 
                return $rts;
        }

        /*浏览历史记录*/
        function history_view(){
                if (!empty($_COOKIE['HISTORYVIEW']))
                {
                        $where = db_create_in($_COOKIE['HISTORYVIEW'], 'goods_id');
                        $sql   = "SELECT goods_id, goods_name, goods_thumb, shop_price, market_price, goods_sn,goods_bianhao FROM `{$this->App->prefix()}goods`" .
                                 " WHERE $where AND is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0";
                        $rs = $this->App->find($sql);
                        $goods = array();
                        if(!empty($rs)){
								$site = Common::class_url();
                                foreach($rs as $k=>$row)
                                {
                                        $goods[$k]['goods_id'] = $row['goods_id'];
										$goods[$k]['goods_bianhao'] = $row['goods_bianhao'];
										$goods[$k]['goods_sn'] = $row['goods_sn'];
                                        $goods[$k]['goods_name'] = $row['goods_name'];
                                        $goods[$k]['goods_thumb'] = SITE_URL.$row['goods_thumb'];
                                        $goods[$k]['market_price'] = $row['market_price'];
                                        $goods[$k]['shop_price'] = $row['shop_price'];
                                         $goods[$k]['url'] = get_url($row['goods_name'],$row['goods_id'],$site.'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
                                }
                                unset($rs);
                        }
                }
                return $goods;
        }
		
		//系统文章
		function help_article(){
			 $cache = Import::ajincache();
			 $cache->SetFunction(__FUNCTION__);
		     $cache->SetMode('sitemes');
		     $fn = $cache->fpath(func_get_args());
			 $type = "about";
		     if(file_exists($fn)&&!$cache->GetClose()){
						include($fn);
		     }
		     else
		     {
					$sql = "SELECT tb1.*,tb2.cat_name,tb2.type FROM `{$this->App->prefix()}article` AS tb1";
					$sql .=" LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2 ON tb1.cat_id=tb2.cat_id";
					$sql .=" WHERE tb2.type='$type' AND tb1.is_show='1' ORDER BY  tb2.vieworder ASC,tb1.vieworder ASC,tb1.article_id DESC";
					$rt = $this->App->find($sql);
					if(!empty($rt)){
						foreach($rt as $k=>$row){
							$rts[$row['cat_id']]['cat_name'] = $row['cat_name'];
							$rts[$row['cat_id']]['url'] = get_url($row['cat_name'],$row['cat_id'],Common::class_url().$row['type'].'.php?cid='.$row['cat_id'],'category',array($row['type'],'index',$row['cat_id']));
							$rts[$row['cat_id']]['article'][$k] = $row;
							$rts[$row['cat_id']]['article'][$k]['url'] = get_url($row['article_title'],$row['article_id'],Common::class_url().$row['type'].'.php?id='.$row['article_id'],'article',array($row['type'],'article',$row['article_id']));
						}
						unset($rt);
					}
				   $cache->write($fn, $rts,'rts');
             }
			return $rts;
		}
		
		//返回搜索关键字
		function get_search_keywords(){
			if(is_file(SYS_PATH.'data/search_keyword.php'))
				require_once(SYS_PATH.'data/search_keyword.php');
			return isset($search_keys)? $search_keys : array();
		}
		
		//自动登录
		function is_auto_login($tt=false){
			if(!$tt){
				if(isset($_COOKIE['USER']['AUTOLOGIN']) && intval($_COOKIE['USER']['AUTOLOGIN']) ==1){
					$user = isset($_COOKIE['USER']['USERNAME']) ? $_COOKIE['USER']['USERNAME'] : "";
					$pass = isset($_COOKIE['USER']['PASS']) ? $_COOKIE['USER']['PASS'] : "";
					if(!empty($user) && !empty($pass)){
						$data['username'] = $user;
						$data['password'] = $pass;
						$data['issave'] = 1;
						$data['isauto'] = 1;
						//登录
						$this->action('user','auto_login',$data);
					}
				}else{
					//已经关注
					
				}
			}else{
				//已经等了
			}
		}
}


//网站的公共链接交换器
function get_url($name="",$id=0,$url="",$type="",$urlobj=array()){  //$name:@链接名称 $id:链接ID $url:指定的URL $type： @类型，用户标记为分类 文章或者菜单导航等等的
	if($GLOBALS['LANG']['is_static']=='11'){
		if($type=='article'){
			return SITE_URL.'alte/'.Import::basic()->Pinyin($name).'_'.$id.'.html';
		}elseif($type=='category'){ //文章分类
			if(empty($colorid)){
				return SITE_URL.'cate/'.Import::basic()->Pinyin($name).$id.'.html';
			}else{
				return SITE_URL.'cate/'.Import::basic()->Pinyin($name).$id.'-'.$colorid.'.html'; //模板分类的颜色分类
			}
		}elseif($type=='goodscate'){ //商品分类
		
		}elseif($type=='goods'){ //商品详情页面
		
		}elseif( $type=='nav'){ //导航菜单
			return SITE_URL.'cate/'.Import::basic()->Pinyin($name).'.html';
		}else{
			die('没定义静态路径');
		}
	}elseif($GLOBALS['LANG']['is_false_static']=='1'){ //简单伪静态
			if($type=='article'){ //文章
				return Common::class_url().'article-'.$id.'.html';
			}elseif($type=='category'){ //文章分类
				return Common::class_url().'category-'.$id.'.html';				
			}elseif($type=='goodscate'){ //商品分类
				if(!empty($colorid)&&is_array($colorid)){
					$h = Common::class_url().'goodscate';
					foreach($colorid as $k=>$v){
						if(empty($v)) continue;
						$h .= '-'.$k.'-'.$v;
					}
					$h .= '.html';
					return $h;
				}else{
					return Common::class_url().'catalog-'.$id.'.html';
				}
			}elseif($type=='goods'){ //商品详情页面
				return Common::class_url().'product-'.$id.'.html';
			}elseif($type=='nav'){
				$ss= Import::basic()->Pinyin($name);
				if($ss=='chanpinzhanshi'){
					$ss = Common::class_url().'catalog.html';
				}elseif($ss=='lianxiwomen'){
					$ss = Common::class_url().'feedback.html';
				}else{
					$ss = strpos($url,'?') ? $url : str_replace('.php','.html',basename($url));
				}
				return $ss;
			}
			
	}elseif($GLOBALS['LANG']['is_best_static']=='1'){ //复杂伪静态【路径形式】
			if($type=='nav'){
				$n = basename($url);
				if(stristr($n,'.php')){
					$nr = explode('.',$n);
					if(count($nr)==2){
						$ids = explode('=',$nr[1]);
						$id = $ids[1];
						return SITE_URL.$nr[0].'/'.(stristr($nr[1],'?id')?'article/'.$id.'/' : (stristr($nr[1],'?cid') ? $id.'/' : ""));
					}else{ return $url;}
				}else{
					return $url;
				}
			}
			if(empty($urlobj)){
				return $url;
			}
			/*global $th;
			$ss =  $th->url($urlobj);
			return $ss;*/
			return $url;
		}else{
			if($type=='nav'){
				return (stristr(basename($url),'.php')?$url:Common::class_url().basename($url).'.php');	
			}
			return $url;
		}
}

/**
 * 创建像这样的查询: "IN('a','b')";
 *
 * @access   public
 * @param    mix      $item_list      列表数组或字符串
 * @param    string   $field_name     字段名称
 *
 * @return   void
 */
function db_create_in($item_list, $field_name = '')
{
    if (empty($item_list))
    {
        return $field_name . " IN ('') ";
    }
    else
    {
        if (!is_array($item_list))
        {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list AS $item)
        {
            if ($item !== '')
            {
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp))
        {
            return $field_name . " IN ('') ";
        }
        else
        {
            return $field_name . ' IN (' . $item_list_tmp . ') ';
        }
    }
}

function is_weixin(){
	if(empty($_SERVER['HTTP_USER_AGENT'])) return true;
	$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
    if(strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false ){
    	return false;//非微信浏览器
    }else{
		return true;
    }
}

function format_price($price=0){
	if(empty($price)) return '0.00';
	return number_format($price, 2, '.', '');
}
?>