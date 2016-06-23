<?php
class WeixinController extends Controller{
 	function  __construct() {
           $this->css('content.css');
	}
	//推广二维码
	function erweimaset(){
	 	$this->template('erweimaset');
	}
	
	function ajax_save_wid($rt=array()){
		$wid = $rt['wid'];
		if($wid > 0){
			if($this->App->update('userconfig',array('wid'=>$wid),'type','basic')){
				echo "绑定成功";
			}else{
				echo "意外错误";
			}
		}
		exit;
	}
	function wxguanzhuurl($data=array()){
   	 	$sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(!empty($_POST)){
			if(empty($rt)){
					$this->App->insert('systemconfig',$_POST);
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}else{
					$this->App->update('systemconfig',$_POST,'type','basic');
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}
		}
		$this->set('rt',$rt);
		$this->action('system','save_basic_config');
   	    $this->template('wxguanzhuurl');
   }
   
	//平台基本设置
	function userconfig($data=array()){
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";
		$rt = $this->App->findrow($sql);
		if($rt['wid'] > 0){
			$wid = $rt['wid'];
			$rt['nickname'] = $this->App->findvar("SELECT nickname FROM `{$this->App->prefix()}user` WHERE user_id = '$wid' LIMIT 1");
		}
		if(!empty($_POST)){
			$_POST['openfxbuy'] = !isset($_POST['openfxbuy']) ? '0' : $_POST['openfxbuy'];
			$_POST['openfxauto'] = !isset($_POST['openfxauto']) ? '0' : $_POST['openfxauto'];
			$_POST['openfx_baoming'] = !isset($_POST['openfx_baoming']) ? '0' : $_POST['openfx_baoming'];
			if(empty($rt)){
					$this->App->insert('userconfig',$_POST);
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}else{
					$this->App->update('userconfig',$_POST,'type','basic');
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}
		}
		$this->set('rt',$rt);
		$this->template('userconfig');
	}
	
    function tongzhiset(){
		$this->template('tongzhiset');
	}
	
   function wxconfig($data=array()){
   		$id = isset($data['id']) ? $data['id'] : 0;
		if($id > 0){
			$this->App->delete('wxuserset','id',$id);
			$this->jump(ADMIN_URL.'weixin.php?type=wxconfig');
			exit;
		}
   	   $sql = "SELECT * FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC";
	   $rt = $this->App->find($sql);
   	   $this->set('rt',$rt);
	   $this->template('wxconfig');
   }
   
   function wxconfiginfo($data=array()){
   	   $id = isset($data['id']) ? $data['id'] : 0;
	   $rt = array();
	   if($id > 0){
	   		 if(!empty($_POST)){
			 	if($this->App->update('wxuserset',$_POST,'id',$id)){ 
					$this->action('common','showdiv',$this->getthisurl());exit;
				}
			 }
			
			 $sql = "SELECT * FROM `{$this->App->prefix()}wxuserset` WHERE id='$id'";
	   		 $rt = $this->App->findrow($sql);
	   }else{
	   		 if(!empty($_POST)){
			 	$randLength=6;
				$chars='abcdefghijklmnopqrstuvwxyz';
				$len=strlen($chars);
				$randStr='';
				for ($i=0;$i<$randLength;$i++){
					$randStr.=$chars[rand(0,$len-1)];
				}
				$_POST['token'] = $randStr.mktime();
				$pigSecret=$this->get_token(20,0,1);
				$_POST['pigsecret']=$pigSecret;
			 	if($this->App->insert('wxuserset',$_POST)){
					$this->action('common','showdiv',$this->getthisurl());exit;
				}
			 }
	   }
	   $this->set('rt',$rt);
   	   $this->template('wxconfiginfo');
   }
   
   
   function wxconfigview($data=array()){
    	$id = isset($data['id']) ? $data['id'] : 0;
		$sql = "SELECT * FROM `{$this->App->prefix()}wxuserset` WHERE id='$id'";
	   	$rt = $this->App->findrow($sql);
		$this->set('rt',$rt);
		$this->template('wxconfigview');
   }
   
   function get_token($randLength=6,$attatime=1,$includenumber=0){
		if ($includenumber){
			$chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
		}else {
			$chars='abcdefghijklmnopqrstuvwxyz';
		}
		$len=strlen($chars);
		$randStr='';
		for ($i=0;$i<$randLength;$i++){
			$randStr.=$chars[rand(0,$len-1)];
		}
		$tokenvalue=$randStr;
		if ($attatime){
			$tokenvalue=$randStr.mktime();
		}
		return $tokenvalue;
	}
	
	//关注时回复
	function wxgzreply($data=array()){
		$rt = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}wxkeyword` WHERE type='guanzhu' LIMIT 1");
		$dd = array();
		$id = isset($rt['id']) ? $rt['id'] : 0;
		if( $id > 0 ){
			if(isset($_POST)&&$_POST['ttt']=='1'){
				$dd['keyword'] = !empty($_POST['keyword']) ? trim($_POST['keyword']) : '';
				$this->App->update('wxkeyword',$dd,'id',$id);
				$this->jump('',0,'操作成功');exit;
			}
		}else{
			if(isset($_POST)&&$_POST['ttt']=='1'){
				$dd['type'] = 'guanzhu';
				$dd['keyword'] = !empty($_POST['keyword']) ? trim($_POST['keyword']) : '';
				$this->App->insert('wxkeyword',$dd);
				$this->jump('',0,'操作成功');exit;
			}
		}
		$this->set('rt',$rt);
		$this->template('wxgzreply');
	}
	//文本信息
	function wxnewlisttxt($data=array()){
		$w="";
		//排序
		$orderby = ' ORDER BY `article_id` DESC';
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(article_id) FROM `{$this->App->prefix()}wx_article`";
		$sql .=" WHERE type='txt'";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);

		$sql = "SELECT * FROM `{$this->App->prefix()}wx_article`";
		$sql .=" WHERE type='txt' {$orderby} LIMIT $start,$list";

		$this->set('newlist',$this->App->find($sql));
		$this->template('wxnewlisttxt');
	}
	
	//微信图文信息
	function wxnewlist($data=array()){
		$w="";
		//排序
		$orderby = ' ORDER BY tb1.vieworder ASC,tb1.`article_id` DESC';
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(tb1.article_id) FROM `{$this->App->prefix()}wx_article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}wx_cate` AS tb2";
		$sql .=" ON tb1.cat_id = tb2.cat_id";
		$sql .=" WHERE tb1.type='img'";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);

		$sql = "SELECT tb1.*,tb2.cat_name FROM `{$this->App->prefix()}wx_article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}wx_cate` AS tb2";
		$sql .=" ON tb1.cat_id = tb2.cat_id";
		$sql .=" WHERE tb1.type='img' {$orderby} LIMIT $start,$list";

		$this->set('newlist',$this->App->find($sql));
		$this->template('wxnewlist');
	}
	
	//图文信息
	function infos($data=array()){
		$id = isset($data['id']) ? $data['id'] : 0;
		$this->js("edit/kindeditor.js"); 
		$rt = array();
		if($id>0){
			if(!empty($_POST)){
				$_POST['uptime'] = mktime();
				$_POST['content'] = @str_replace('./../photos/',SYS_PHOTOS_URL,$_POST['content']); //替换为绝对路径的链接
				$this->App->update('wx_article',$_POST,'article_id',$id);
				$this->action('common','showdiv',$this->getthisurl());
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE article_id='{$id}'";
			$rt = $this->App->findrow($sql);
		}else{
			if(!empty($_POST)){
				$_POST['addtime'] = mktime();
				$_POST['uptime'] = mktime();
				$_POST['content'] = @str_replace('./../photos/',SYS_PHOTOS_URL,$_POST['content']); //替换为绝对路径的链接
				$this->App->insert('wx_article',$_POST);
				$this->action('common','showdiv',$this->getthisurl());
				$rt = $_POST;
			}
		}
		$this->set('rt',$rt);
		$this->set('id',$id);
		$this->set('catids',$this->get_cate_tree());
		$this->template('infos');
	}
	
	//文章排序
	function ajax_vieworder($data=array()){
		if(empty($data['id'])) return "50";
		$sdata['vieworder'] = empty($data['val']) ? 50 : $data['val'];
		$this->App->update('wx_article',$sdata,'article_id',$data['id']);
	}
	
	//文本信息
	function infostxt($data=array()){
		$id = isset($data['id']) ? $data['id'] : 0;
		$rt = array();
		if($id>0){
			if(!empty($_POST)){
				$_POST['uptime'] = mktime();
				$_POST['content'] = @str_replace('./../photos/',SYS_PHOTOS_URL,$_POST['content']); //替换为绝对路径的链接
				$this->App->update('wx_article',$_POST,'article_id',$id);
				$this->action('common','showdiv',$this->getthisurl());
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE article_id='{$id}'";
			$rt = $this->App->findrow($sql);
		}else{
			if(!empty($_POST)){
				$_POST['addtime'] = mktime();
				$_POST['uptime'] = mktime();
				$_POST['content'] = @str_replace('./../photos/',SYS_PHOTOS_URL,$_POST['content']); //替换为绝对路径的链接
				$this->App->insert('wx_article',$_POST);
				$this->action('common','showdiv',$this->getthisurl());
				$rt = $_POST;
			}
		}
		$this->set('rt',$rt);
		$this->set('id',$id);
		$this->template('infostxt');
	}
	
	
	//微信图文分类
	function catelist(){
		$this->set('catelist',$this->get_cate_tree());
		$this->template('catelist');
	}
	
	//ajax删除分类
	function ajax_del_wxdelcate($dd=array()){
		$ids = $dd['ids'];
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		$new_ids = array();
		foreach($id_arr as $id){
			$getid = $this->get_sub_cat_ids($id);
			if(!empty($getid)){
				foreach($getid as $id){
					$new_ids[] = $id;
				}
			}
		}//end foreach
		
		if(!empty($new_ids)){
			$new_id = array_unique($new_ids);
			unset($new_ids);
			
			$sql = "SELECT article_img FROM `{$this->App->prefix()}wx_cate` WHERE cat_id IN(".@implode(',',$new_id).")";
			$imgs = $this->App->findcol($sql);
			if(!empty($imgs)){
				foreach($imgs as $img){
					if(empty($img)) continue;
					Import::fileop()->delete_file(SYS_PATH.$img); //删除图片
					$q = dirname($img);
					$h = basename($img);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
				}
				unset($imgs);
			}
		
			foreach($new_id as $id){
					//非法ID不允许删除
				if(Import::basic()->int_preg($id)){
					//删除分类下的文章
					$this->App->delete('wx_article','cat_id',$id);
					//删除指定分类
					$this->App->delete('wx_cate','cat_id',$id);
				}
			}
		}
		unset($id_arr,$new_id);
	}
	
	/*
	* 分类详情页面
	*/
	function cateinfo($data=array()){ 
		$rt = array();
		$id = isset($data['id']) ? $data['id'] : 0;
		if($id > 0){
				if(!empty($_POST)){
					$this->App->update('wx_cate',$_POST,'cat_id',$id);
					$this->action('common','showdiv',$this->getthisurl());
				}
				$sql = "SELECT * FROM `{$this->App->prefix()}wx_cate` WHERE cat_id='{$id}'";
				$rt = $this->App->findrow($sql);
		}else{
			if(!empty($_POST)){
				$_POST['addtime'] = time();
				$this->App->insert('wx_cate',$_POST);
				$this->action('common','showdiv',$this->getthisurl());
				$_POST['cat_img'] = "";
				$rt = $_POST;
			}
		}
		$this->set('catelist',$this->get_cate_tree());
		
		$this->set('rt',$rt);
		$this->set('id',$id);
		$this->template('cateinfo');
	}
	
	//获取分类
	function get_cate_tree($cid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT count(cat_id) FROM `'.$this->App->prefix()."wx_cate` WHERE parent_id = '$cid' AND is_show = 1";
		if ($this->App->findvar($sql) || $cid == 0)
		{
			$sql = 'SELECT tb1.cat_name,tb1.cat_id,tb1.parent_id,tb1.is_show,tb1.addtime,tb1.cat_img,tb1.vieworder, COUNT(tb2.cat_id) AS article_count FROM `'.$this->App->prefix()."wx_cate` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}wx_article` AS tb2";
			$sql .=" ON tb1.cat_id = tb2.cat_id";
			$sql .= " WHERE tb1.parent_id = '$cid' GROUP BY tb1.cat_id ORDER BY tb1.parent_id ASC,tb1.vieworder ASC, tb1.cat_id ASC";
			$res = $this->App->find($sql); 
			foreach ($res as $row)
			{
			    $three_arr[$row['cat_id']]   = $row;
				$three_arr[$row['cat_id']]['id']   = $row['cat_id'];
			   
			    if (isset($row['cat_id']) != NULL)
				{
					 $three_arr[$row['cat_id']]['cat_id'] = $this->get_cate_tree($row['cat_id']);
				}
			}
		}
		return $three_arr;
	}
	
	function get_sub_cat_ids($cid=0){
		$rts = $this->get_cate_tree($cid);
		$cids[] = $cid;
		if(!empty($rts)){
			foreach($rts as $row){
				$cids[] = $row['id'];
				if(!empty($row['cat_id'])){
					foreach($row['cat_id'] as $rows){
						$cids[] = $rows['id'];
						if(!empty($rows['cat_id'])){
							foreach($rows['cat_id'] as $rowss){
								$cids[] = $rowss['id'];
							} // end foreach
						} // end if
					} // end foreach
				} // end if
			} // end foreach
		}// end if
		return $cids;
	}
	
	//生成自定义菜单
	function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		//curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if ($js['errcode']=='0'){
				return array('rt'=>true,'errorno'=>0);
			}else {
				echo '发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg'];
			}
		}
	}
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		//curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		//curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
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
		
	//获取appid、appsecret
	function _get_appid_appsecret(){
			$t = $this->_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				    include($fn);
			}
			else
		    {
					$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` LIMIT 1";
					$rr = $this->App->findrow($sql);
					$rt['appid'] = $rr['appid'];
					$rt['appsecret'] = $rr['appsecret'];
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
	}
	
	//获取access_token
	function _get_access_token(){
			$t = $this->_return_px();
			$cache = Import::ajincache();
			$cache->SetFunction(__FUNCTION__);
			$cache->SetMode('sitemes'.$t);
			$fn = $cache->fpath(func_get_args());
			if(file_exists($fn)&& (mktime() - filemtime($fn) < 7000) && !$cache->GetClose()){
				    include($fn);
			}
			else
		    {
					$rr = $this->_get_appid_appsecret();
					$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$rr['appid'].'&secret='.$rr['appsecret'];
					$con = $this->curlGet($url);
					$json=json_decode($con);
					$rt = $json->access_token; //获取 access_token
					
					$cache->write($fn, $rt,'rt');
		   }
		   return $rt;
	}
	
	function ajax_diyclass_send($data=array()){
			/*$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` WHERE id='1'";
			$rt = $this->App->findrow($sql);
			$rt = $this->_get_appid_appsecret();
			$appid = $rt['appid'];
			$appsecret = $rt['appsecret'];
			
			$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
			
			$json=json_decode($this->curlGet($url_get));
			
			if(!$json->errmsg){
				
			}else {
				echo '获取access_token发生错误：错误代码'.$json->errcode.',微信返回错误信息：'.$json->errmsg;exit;
			}*/

			$access_token = $this->_get_access_token();
			
			$data = '{"button":[';
			$class=$this->App->find("SELECT * FROM `{$this->App->prefix()}wxdiymen` WHERE parent_id='0' AND is_show='1' ORDER BY parent_id ASC,sort ASC LIMIT 3");
			$kcount=$this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix()}wxdiymen` WHERE parent_id='0' AND is_show='1' LIMIT 3");
			$k=1;
			foreach($class as $key=>$vo){
				//主菜单

				$data.='{"name":"'.$vo['title'].'",';
				$id = $vo['id']; 
				$c=$this->App->find("SELECT * FROM `{$this->App->prefix()}wxdiymen` WHERE parent_id='$id' AND is_show='1' LIMIT 5");
				$count=$this->App->findvar("SELECT COUNT(id) FROM `{$this->App->prefix()}wxdiymen` WHERE parent_id='$id' AND is_show='1' LIMIT 5");
				//子菜单
				//$vo['url']=str_replace(array('&amp;','&wecha_id={wechat_id}'),array('&','&diymenu=1'),$vo['url']);
				if($c!=false){
					$data.='"sub_button":[';
				}else{
					if(!$vo['url']){
						$data.='"type":"click","key":"'.$vo['keyword'].'"';
					}else {
						$data.='"type":"view","url":"'.$vo['url'].'"';
					}
				}
				$i=1;
				foreach($c as $voo){
					//$voo['url']=str_replace(array('&amp;','&wecha_id={wechat_id}'),array('&','&diymenu=1'),$voo['url']);
					if($i==$count){
						if($voo['url']){
							$data.='{"type":"view","name":"'.$voo['title'].'","url":"'.$voo['url'].'"}';
						}else{
							$data.='{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['keyword'].'"}';
						}
					}else{
						if($voo['url']){
							$data.='{"type":"view","name":"'.$voo['title'].'","url":"'.$voo['url'].'"},';
						}else{
							$data.='{"type":"click","name":"'.$voo['title'].'","key":"'.$voo['keyword'].'"},';
						}
					}
					$i++;
				}
				if($c!=false){
					$data.=']';
				}

				if($k==$kcount){
					$data.='}';
				}else{
					$data.='},';
				}
				$k++;
			}
			$data.=']}';

			file_get_contents('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$access_token);

			$url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
			$rt=$this->api_notice_increment($url,$data);
			if($rt['rt']==false){
				echo '操作失败,curl_error:'.$rt['errorno'];
			}else{
				echo '成功提交，等待生效';
			}
			exit;
	}
	
	//自定义菜单
	function diymenu($data=array()){
		$id = isset($data['id']) ? $data['id'] : 0;
		if($id>0){
			$this->App->delete('wxdiymen','id',$id);
			$this->jump(ADMIN_URL.'weixin.php?type=diymenu');exit;
		}
		
		$rt = $this->get_cate_diytree();
		$this->set('rt',$rt);
		$this->template('diymenu');
	}
	
	function diymenuinfo($data=array()){ 
		$this->css('jquery_dialog.css');
		$this->js('jquery_dialog.js');
			
		$id = isset($data['id']) ? $data['id'] : 0;
		if($id > 0){
			if(!empty($_POST)){
				$key = $_POST['keyword'];
				if(empty($key)){
					$this->jump(ADMIN_URL.'weixin.php?type=diymenuinfo&id='.$id,0,'关键字不能为空');exit;
				}
				$title = $_POST['title'];
				if(empty($title)){
					$this->jump(ADMIN_URL.'weixin.php?type=diymenuinfo&id='.$id,0,'主菜单名称');exit;
				}
				$url = $_POST['url'];
				$is_show = $_POST['is_show'];
				$sort = $_POST['sort'];
				if(!($sort>0)) $sort = 50;
				if($this->App->update('wxdiymen',$_POST,'id',$id)){
					$this->jump(ADMIN_URL.'weixin.php?type=diymenuinfo&id='.$id,0,'更新成功');exit;
				}else{
					$this->jump(ADMIN_URL.'weixin.php?type=diymenuinfo&id='.$id,0,'更新失败');exit;
				}
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}wxdiymen` WHERE id='{$id}'";
			$rt = $this->App->findrow($sql);
		}else{
			if(!empty($_POST)){
				$key = $_POST['keyword'];
				if(empty($key)){
					$this->jump(ADMIN_URL.'weixin.php?type=diymenuinfo',0,'关键字不能为空');exit;
				}
				$title = $_POST['title'];
				if(empty($title)){
					$this->jump(ADMIN_URL.'weixin.php?type=diymenuinfo',0,'主菜单名称');exit;
				}
				$url = $_POST['url'];
				$is_show = $_POST['is_show'];
				$sort = $_POST['sort'];
				if(!($sort>0)) $sort = 50;
				if($this->App->insert('wxdiymen',$_POST)){
					$this->jump(ADMIN_URL.'weixin.php?type=diymenuinfo',0,'添加成功');exit;
				}else{
					$this->jump(ADMIN_URL.'weixin.php?type=diymenuinfo',0,'添加失败');exit;
				}
				$rt = $_POST;
			}
		}
		
		$rt['nav'] = $this->get_cate_diytree();
		//print_r($rt['nav']);
		$this->set('rt',$rt);
		$this->set('id',$id);
		$this->template('diymenuinfo');
	}
	
	function get_cate_diytree($cid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT count(id) FROM `'.$this->App->prefix()."wxdiymen` WHERE parent_id = '$cid'";
		if ($this->App->findvar($sql) || $cid == 0)
		{
			$sql = 'SELECT * FROM `'.$this->App->prefix()."wxdiymen` WHERE parent_id = '$cid' ORDER BY parent_id ASC,sort ASC, id ASC";
			$res = $this->App->find($sql); 
			foreach ($res as $row)
			{
			    $three_arr[$row['id']]   = $row;
			 
			    if (isset($row['id'])&&!empty($row['id']) != NULL)
				{
					 $three_arr[$row['id']]['cat_id'] = $this->get_cate_diytree($row['id']);
				}
			}
		}
		return $three_arr;
	}
	
	//删除文章
	function ajax_delarticle($data=array()){
		$ids = $data['ids'];
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		
		$sql = "SELECT article_img FROM `{$this->App->prefix()}wx_article` WHERE article_id IN(".@implode(',',$id_arr).")";
		$imgs = $this->App->findcol($sql);
		if(!empty($imgs)){
			foreach($imgs as $img){
				if(empty($img)) continue;
				Import::fileop()->delete_file(SYS_PATH.$img); //删除图片
					$q = dirname($img);
					$h = basename($img);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
			}
			unset($imgs);
		}
		
		foreach($id_arr as $id){
		  if(Import::basic()->int_preg($id))
		  $this->App->delete('wx_article','article_id',$id);
		}
	}
}
?>