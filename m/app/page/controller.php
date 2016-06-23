<?php
class PageController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		
	}
	function get_is_subscribe(){
		$uid = $this->Session->read('User.uid');
		return $this->App->findvar("SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
	}
	
     function get_site_nav($t='top',$list=4){
				   $ts = Common::_return_px();
				   $cache = Import::ajincache();
                   $cache->SetFunction(__FUNCTION__);
                   $cache->SetMode('sitemes'.$ts);
                   $fn = $cache->fpath(array('0'=>$t));
                   if(file_exists($fn)&&!$cache->GetClose()){
                            include($fn);
                   }
                   else
                   {
							$sql = "SELECT * FROM `{$this->App->prefix()}nav_wx` WHERE is_show = '1' AND type = '$t' ORDER BY vieworder ASC, id ASC LIMIT $list";
							$rt = $this->App->find($sql);
							
							$cache->write($fn, $rt,'rt');
                   }
                 
                return $rt;
    }
		
	function defaults(){
		$this->action('common','checkjump');
				//轮播js css
		$this->css(array("flexslider.css"));
		$this->js(array("jquery.flexslider-min.js","main.js"));
		$this->js(array('jquery.json-1.3.js','goods.js?v=17'));//将js文件放到页面头
		$this->title($GLOBALS['LANG']['site_title']);
		$this->meta("title",$GLOBALS['LANG']['metatitle']);
		$this->meta("keywords",$GLOBALS['LANG']['metakeyword']);
		$this->meta("description",$GLOBALS['LANG']['metadesc']);
	 	//$rt = $this->Cache->read(3600);
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
	 	$t = Common::_return_px();
	    $cache = Import::ajincache();
	    $cache->SetFunction(__FUNCTION__);
	    $cache->SetMode('page'.$t);
	    $fn = $cache->fpath(array('0'=>$id));
	    if(file_exists($fn)&&!$cache->GetClose()){
				include($fn);
	    }
	    else
	    {
			$s = '';
			if($id > 0) $s = "tb1.id='$id' AND";
			$sql = "SELECT tb1.*,tb2.goods_name,tb2.pifa_price FROM `{$this->App->prefix()}goods_tuijian` AS tb1 LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb2.goods_id = tb1.goods_id WHERE {$s} tb2.is_on_sale = '1' ORDER BY id DESC LIMIT 1";
			$rt['tj'] = $this->App->findrow($sql);
			
			//$this->Cache->write($rt);
			$cache->write($fn, $rt,'rt');
		}
		$uid = $this->Session->read('User.uid');
		$rt['tjr']['nickname'] = '[官网]';
		$rt['tjr']['headimgurl'] = ADMIN_URL.'images/uclicon.jpg';
		$rt['uinfo'] = array();
		if($uid > 0){
			$sql = "SELECT tb1.nickname,tb1.headimgurl FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";
			$rt['tjr'] = $this->App->findrow($sql);
			if(empty($rt['tjr'])){
					$rt['tjr']['nickname'] = '[官网]';
					$rt['tjr']['headimgurl'] = ADMIN_URL.'images/uclicon.jpg';
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$rt['uinfo'] = $this->App->findrow($sql);
		}
		$this->set('rt',$rt);
		$this->set('title',$GLOBALS['LANG']['metatitle']);
		$this->set('description',$GLOBALS['LANG']['metadesc']);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/page_defaults');
	}
	
	function index(){
		$this->action('common','checkjump');

		$this->js(array('jquery.json-1.3.js','goods.js?v=17'));//将js文件放到页面头
		//轮播js css
		$this->css(array("flexslider.css"));
		$this->js(array("jquery.flexslider-min.js","main.js"));
		
		$this->title($GLOBALS['LANG']['site_title']);
		$this->meta("title",$GLOBALS['LANG']['metatitle']);
		$this->meta("keywords",$GLOBALS['LANG']['metakeyword']);
		$this->meta("description",$GLOBALS['LANG']['metadesc']);
		$t = Common::_return_px();
	    $cache = Import::ajincache();
	    $cache->SetFunction(__FUNCTION__);
	    $cache->SetMode('page'.$t);
	    $fn = $cache->fpath(array('0'=>''));
	    if(file_exists($fn)&&!$cache->GetClose()){
				include($fn);
	    }
	    else
	    {
		   //分类产品
			$sql = "SELECT cat_name,cat_url,cat_img,cat_id FROM `{$this->App->prefix()}goods_cate` WHERE is_index='1' ORDER BY sort_order ASC";
			$rt['cat'] = $this->App->find($sql);
			if(empty($rt['cat'])){
				$sql = "SELECT cat_name,cat_url,cat_img,cat_id FROM `{$this->App->prefix()}goods_cate` WHERE is_show='1' ORDER BY sort_order ASC";
				$rt['cat'] = $this->App->find($sql);
			}
			if(!empty($rt['cat']))foreach($rt['cat'] as $row){
				//$sub_cids = $this->action('catalog','get_goods_sub_cat_ids',$row['cat_id']);
				$cid = $row['cat_id'];
				$sql = "SELECT goods_id,goods_name,goods_thumb,goods_img,pifa_price,shop_price,sale_count,sort_desc FROM `{$this->App->prefix()}goods` WHERE cat_id ='$cid' AND is_on_sale='1' AND is_jifen = '0' AND is_delete = '0'  AND up_goods<1 AND (is_best ='1' OR is_new='1' OR is_hot='1') ORDER BY sort_order ASC LIMIT 6";
				$rt['goods'][$row['cat_id']] = $this->App->find($sql);
			}
			
			//积分产品
			$sql = "SELECT goods_id,goods_name,market_price,shop_price,promote_price,goods_thumb,goods_img,is_jifen,need_jifen FROM `{$this->App->prefix()}goods` WHERE is_best='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='1' AND is_delete = '0' ORDER BY sort_order ASC, goods_id DESC LIMIT 4";
			$rt['listsjf'] = $this->App->find($sql);
			
			
			//统计
			$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' LIMIT 1";
			$rt['zgcount'] = $this->App->findvar($sql);
			
			$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_new='1' AND is_on_sale='1' AND is_alone_sale='1' AND is_jifen='0' AND is_delete = '0' LIMIT 1";
			$rt['zgnewcount'] = $this->App->findvar($sql);
			
			//品牌列表
		    $sql = "SELECT distinct brand_name, brand_id,brand_name,brand_banner,brand_logo FROM `{$this->App->prefix()}brand` WHERE is_show='1' AND is_promote='1' ORDER BY sort_order ASC,brand_id LIMIT 7";
		   // $rt['blist'] =  $this->App->find($sql);
		
			//产品分类
			$sql = "SELECT cat_name,cat_url,cat_img,cat_id FROM `{$this->App->prefix()}goods_cate` WHERE is_show='1' ORDER BY sort_order ASC";
	    	$rt['indexcat'] = $this->App->find($sql);
		
			$sql = "SELECT tb1.*,tb2.ad_name FROM `{$this->App->prefix()}ad_content` AS tb1 LEFT JOIN `{$this->App->prefix()}ad_position` AS tb2 ON tb1.tid = tb2.tid WHERE tb1.is_show='1' AND tb2.ad_name LIKE '%首页轮播%' ORDER BY tb1.vieworder ASC,tb1.addtime DESC LIMIT 5";
			$rt['lunbo'] = $this->App->find($sql);
			
			//
			$rt['navtop'] = $this->get_site_nav('middle',8);
			
			$cache->write($fn, $rt,'rt');
		}
		
		$uid = $this->Session->read('User.uid');
		
		$sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE user_id='$uid' LIMIT 1";
		$rt['zordercount'] = $this->App->findvar($sql);
		
		//获取分享者信息
		$rt['shareinfo'] = array();
		if($uid > 0){ 
			$sql = "SELECT tb1.nickname,tb1.headimgurl FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";
			$rt['shareinfo'] = $this->App->findrow($sql);
			if(empty($rt['shareinfo'])){
			 	$rt['shareinfo']['nickname'] = '官网';
				$rt['shareinfo']['headimgurl'] = ADMIN_URL.'images/uclicon.jpg';
			}
		}
		
		$this->set('rt',$rt);
		$this->set('title',$GLOBALS['LANG']['metatitle']);
		$this->set('description',$GLOBALS['LANG']['metadesc']);
		
		$this->set('page',$page);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/page_index');
	}
	
	
	function coupon(){
		$type_id = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;
		$shopid = $this->Session->read('Shop.uid');
		if($type_id > 0){
			$img = $this->App->findvar("SELECT img FROM `{$this->App->prefix()}user_coupon_type` WHERE type_id = '$type_id'");
			if($this->App->delete('user_coupon_type','type_id',$type_id)){
				if(!empty($img)){
					Import::fileop()->delete_file(SYS_PATH.$img); //删除文件
					$q = dirname($img);
					$h = basename($img);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
					Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
				}
				$this->App->delete('user_coupon_list','type_id',$type_id);
			}
			$this->jump(ADMIN_URL.'sp.php?type=coupon&id='.$shopid); exit;
		}
		
		$sql = "SELECT * FROM `{$this->App->prefix()}user_coupon_type` WHERE shop_id = '$shopid'";
		$rt = $this->App->find($sql);
		$this->set('rt',$rt);
		$this->template('coupon');
	}
	
	function couponinfo(){
		$shopid = $this->Session->read('Shop.uid');
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		//检查表单
		if(!empty($_POST)){
			if(empty($_POST['type_name'])){
				$this->jump('',0,'类型名称不能为空！'); exit;
			}
			if(!($_POST['type_money']>0)){
				$this->jump('',0,'红包金额必须大于0！'); exit;
			}
			(isset($_POST['send_start_date'])&&!empty($_POST['send_start_date'])) ? $_POST['send_start_date'] = strtotime($_POST['send_start_date']) : "";
			(isset($_POST['send_end_date'])&&!empty($_POST['send_end_date'])) ? $_POST['send_end_date'] = strtotime($_POST['send_end_date']) : "";			
		}
		$rt = array();
		if($id>0){ //编辑
			if(!empty($_POST)){
				if($this->App->update('user_coupon_type',$_POST,'type_id',$id)){
					$this->jump('',0,'修改成功！'); exit;
				}else{
					$this->jump('',0,'修改失败！'); exit;
				}
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}user_coupon_type` WHERE type_id='$id'";
			$rt = $this->App->findrow($sql);
			$type = 'edit';
		}else{ //添加
			if(!empty($_POST)){
				$_POST['shop_id'] = $shopid;
				if($this->App->insert('user_coupon_type',$_POST)){
					$this->jump('',0,'添加成功！'); exit;
				}else{
				echo '<script> alert("添加失败！");</script>';
				$rt = $_POST;
				}
			}
		}
		$this->set('rt',$rt);
		
		
		$this->template('couponinfo');
	}
	
	function couponsend(){
		$this->js(array('jquery.json-1.3.js'));
		$type_id = $_GET['type_id'];
		$shopid = $this->Session->read('Shop.uid');
		
		if(isset($_POST) && !empty($_POST['bonus_sum'])){
			$bonus_sum = $_POST['bonus_sum'];
			$num = $this->App->findvar("SELECT MAX(bonus_sn) FROM `{$this->App->prefix()}user_coupon_list`");
			$num = $num ? $num : 100;
			for ($i = 0; $i < $bonus_sum; $i++)
			{
				$bonus_sn = $num + $i + 1;
				$dd = array('type_id'=>$type_id,'bonus_sn'=>$bonus_sn);
				$this->App->insert('user_coupon_list',$dd);
			}
			$this->jump(ADMIN_URL.'sp.php?type=couponview&shopid='.$shopid.'&type_id='.$type_id);exit;
		}

		$send_type = $this->App->findrow("SELECT type_money,type_name,type_id FROM `{$this->App->prefix()}user_coupon_type` WHERE type_id='$type_id'");
		$this->set('send_type',$send_type);

		$this->template('coupon_send');
	}
	
	function couponview(){
		$id = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;
		if(isset($_GET['op'])&&$_GET['op']=='del' && intval($_GET['delid'])>0){ //删除
			$shopid = $this->Session->read('Shop.uid');
			if($this->App->delete('user_coupon_list','bonus_id',intval($_GET['delid']))){
				$this->jump('sp.php?type=couponview&shopid='.$shopid.'&type_id='.$id); exit;
			}
		}
		$list = 15;
		$page = (isset($_GET['page'])&&intval($_GET['page'])> 0) ? intval($_GET['page']) : 1;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(bonus_id) FROM `{$this->App->prefix()}user_coupon_list` WHERE bonus_type_id='$id'";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
        $this->set("pagelink",$pagelink);
			
		$sql = "SELECT tb1.*,tb2.type_name,tb3.user_name FROM `{$this->App->prefix()}user_coupon_list` AS tb1 LEFT JOIN `{$this->App->prefix()}user_coupon_type` AS tb2 ON tb1.type_id=tb2.type_id LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb1.user_id=tb3.user_id WHERE tb1.type_id='$id' ORDER BY tb1.bonus_id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
        $this->set('rt',$rt);
		
		$this->template('couponview');
	}
	
	function photos(){
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
				$page = 1;
		}
		
		$shopid = $this->Session->read('Shop.uid');
		
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(img_id) FROM `{$this->App->prefix()}user_photos` WHERE shop_id='$shopid'";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
			
		$sql = "SELECT * FROM `{$this->App->prefix()}user_photos` WHERE shop_id='$shopid' LIMIT $start,$list";
		$this->set('rt',$this->App->find($sql));
		
		$this->template('photos');
	}
	
	function ajax_bathdel_photos($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		
		$sql = "SELECT img_url  FROM `{$this->App->prefix()}user_photos` WHERE img_id IN(".@implode(',',$id_arr).")";
		$imgs = $this->App->findcol($sql); 
		if(!empty($imgs)){
			foreach($imgs as $vv){
				if(empty($vv)) continue;
				Import::fileop()->delete_file(SYS_PATH.$vv); //删除文件
				$q = dirname($vv);
				$h = basename($vv);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_s'.DS.$h);
				Import::fileop()->delete_file(SYS_PATH.$q.DS.'thumb_b'.DS.$h);
					
			}
			unset($imgs);
		}
		
		foreach($id_arr as $id){
		  if(Import::basic()->int_preg($id))
		  $this->App->delete('user_photos','img_id',$id);
		}
		//$this->action('system','add_admin_log','删除店铺店貌图片：'.@implode(',',$id_arr));
	}
	
	function ajax_bathdel_cate($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		
		foreach($id_arr as $id){
			$getid = $this->get_goods_sub_cat_ids($id); //子分类
			if(!empty($getid)){
				foreach($getid as $id){
				   //删除数据库信息
						//非法ID不允许删除
						if(Import::basic()->int_preg($id)){
								//删除指定分类
								$this->App->delete('user_shopcate','cat_id',$id);
								//删除菜单分类菜谱
								$gids = $this->App->findcol("SELECT goods_id FROM `{$this->App->prefix()}user_shopgoods` WHERE cat_id ='$id'");
								if(!empty($gids)){
									$this->ajax_delgoods($gids);
								}
						}
				}
			}
		}//end foreach
		
		//$this->action('system','add_admin_log','删除店铺菜单分类：'.@implode(',',$id_arr));
	}
	
	function ajax_bathdel_shopcate($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		
		foreach($id_arr as $id){
			$getid = $this->get_shopcate_sub_cat_ids($id); //子分类
			if(!empty($getid)){
				foreach($getid as $id){
				   //删除数据库信息
						//非法ID不允许删除
						if(Import::basic()->int_preg($id)){
								//删除指定分类
								$this->App->delete('user_cate','cat_id',$id);
								$this->App->delete('user_catesub','cat_id',$id);
						}
				}
			}
		}//end foreach
		
		//$this->action('system','add_admin_log','删除店铺菜单分类：'.@implode(',',$id_arr));
	}
	
	//ajax删除商品
	function ajax_delgoods($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		if(!is_array($ids))
			$id_arr = @explode('+',$ids);
		else
			$id_arr = $ids;
		
		
		$sql = "SELECT goods_thumb, goods_img, original_img FROM `{$this->App->prefix()}user_shopgoods` WHERE goods_id IN(".@implode(',',$id_arr).")";
		$imgs = $this->App->find($sql);
		if(!empty($imgs)){
			foreach($imgs as $row){
				if(!empty($row['goods_thumb']))
					Import::fileop()->delete_file(SYS_PATH.$row['goods_thumb']); //
				if(!empty($row['goods_img']))
					Import::fileop()->delete_file(SYS_PATH.$row['goods_img']); //
				if(!empty($row['original_img']))
					Import::fileop()->delete_file(SYS_PATH.$row['original_img']); //
			}
			unset($imgs);
		}
		
		
		
		foreach($id_arr as $id){
		  if(Import::basic()->int_preg($id)){
			  if($this->App->delete('user_shopgoods','goods_id',$id)){ //删除商品
					 // $this->App->delete('comment','id_value',$id); //删除商品评论
					  //$this->App->delete('goods_collect','goods_id',$id); //删除商品收藏
			   }
		  }
		}
		//$this->action('system','add_admin_log','删除店铺菜谱：'.@implode(',',$id_arr));
		return true;
	}
	
	function ajax_bathdel_goods($ids=0){
		$this->ajax_delgoods($ids);
		
	}
	
	function photosinfo(){
		$this->layout('kong2');
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$uid = $this->Session->read('Shop.uid');
		if(isset($_POST) && !empty($_POST)){
			if($id > 0){ //编辑
				$this->App->update('user_photos',$_POST,'img_id',$id);

				$this->jump(SITE_URL.'shop/sp.php?type=photosinfo&id='.$id,0,'修改成功！'); exit;
			}else{ //添加
				$_POST['shop_id'] = $uid;
				$_POST['addtime'] = mktime();
				$this->App->insert('user_photos',$_POST);
				$this->jump(SITE_URL.'shop/sp.php?type=photosinfo',0,'添加成功！'); exit;
			}
		}
		
		if($id > 0){
			$sql = "SELECT * FROM `{$this->App->prefix()}user_photos` WHERE img_id='$id' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$this->set('rt',$rt);	
		}
		$this->template('photosinfo');
	}
	
	function goodsinfo(){
		$this->layout('kong2');
		$gid = isset($_GET['id']) ? intval($_GET['id']) : 0;
		//$sid = isset($_GET['shopid']) ? intval($_GET['shopid']) : 0;
		$sid = $this->Session->read('Shop.uid');
		if($gid > 0){
			$sql = "SELECT * FROM `{$this->App->prefix()}user_shopgoods` WHERE goods_id='$gid' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$this->set('rt',$rt);
		}
		
		if(isset($_POST) && !empty($_POST)){
			$_POST['is_on_sale'] = isset($_POST['is_on_sale'])&&intval($_POST['is_on_sale'])>0 ? intval($_POST['is_on_sale']) : '0';
			$_POST['is_best'] = isset($_POST['is_best'])&&intval($_POST['is_best'])>0 ? intval($_POST['is_best']) : '0';
			$_POST['is_new'] = isset($_POST['is_new'])&&intval($_POST['is_new'])>0 ? intval($_POST['is_new']) : '0';
			$_POST['is_hot'] = isset($_POST['is_hot'])&&intval($_POST['is_hot'])>0 ? intval($_POST['is_hot']) : '0';
			$_POST['is_promote'] = isset($_POST['is_promote'])&&intval($_POST['is_promote'])>0 ? intval($_POST['is_promote']) : '0';
				
			if($gid > 0){ //编辑
				if($rt['original_img']!=$_POST['original_img']){
						//修改了上传文件 那么重新上传
						$source_path = SYS_PATH.DS.str_replace('/',DS,$_POST['original_img']);
						$pa = dirname($_POST['original_img']);
						$thumb = basename($_POST['original_img']);
						
						$tw_s = (intval($GLOBALS['LANG']['th_width_s']) > 0) ? intval($GLOBALS['LANG']['th_width_s']) : 200;
						$th_s = (intval($GLOBALS['LANG']['th_height_s']) > 0) ? intval($GLOBALS['LANG']['th_height_s']) : 200;
						$tw_b = (intval($GLOBALS['LANG']['th_width_b']) > 0) ? intval($GLOBALS['LANG']['th_width_b']) : 450;
						$th_b = (intval($GLOBALS['LANG']['th_height_b']) > 0) ? intval($GLOBALS['LANG']['th_height_b']) : 450;
						if(isset($_POST['goods_thumb'])&&!empty($_POST['goods_thumb'])){
						   //留空
						}else{
							Import::img()->thumb($source_path,dirname($source_path).DS.'thumb_s'.DS.$thumb,$tw_s,$th_s); //小缩略图
							$_POST['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
						}
						 
						Import::img()->thumb($source_path,dirname($source_path).DS.'thumb_b'.DS.$thumb,$tw_b,$th_b); //大缩略图
						$_POST['goods_img'] = $pa.'/thumb_b/'.$thumb;
				 }
				 $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
				  									 
				 $this->App->update('user_shopgoods',$_POST,'goods_id',$gid);

				 $this->jump(SITE_URL.'shop/sp.php?type=photosinfo',0,'修改成功！'); exit;
				
			}else{ //添加
				 $_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
				 $_POST['add_time'] = mktime();
				 $_POST['uid'] = $sid;
				 //商品图片
				 if(!empty($_POST['original_img'])){
					$pa = dirname($_POST['original_img']);
					$thumb = basename($_POST['original_img']);
					//商品小图
					if(isset($_POST['goods_thumb'])&&!empty($_POST['goods_thumb'])){
						//留空即可
					}else{
						$_POST['goods_thumb'] = $pa.'/thumb_s/'.$thumb;
					}
					//商品中图
					$_POST['goods_img'] = $pa.'/thumb_b/'.$thumb;
				 }
								 
				
				$this->App->insert('user_shopgoods',$_POST);

				$this->jump(SITE_URL.'shop/sp.php?type=photosinfo',0,'添加成功！'); exit;
			}
		}
		
		$this->set('catelist',$this->get_goods_cate_tree(0,$sid));
		$this->template('goodsinfo');
	}
	
	function goodslist(){
		$id = $this->Session->read('Shop.uid');
		//查询当前店铺的所有菜单
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
				$page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		
		$sql = "SELECT tb1.*,tb2.cat_name FROM `{$this->App->prefix()}user_shopgoods` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}user_shopcate` AS tb2 ON tb1.cat_id = tb2.cat_id";
		$sql .=" WHERE tb1.uid = '$id' LIMIT $start,$list";// echo $sql;
		$rt = $this->App->find($sql);
		$this->set('rt',$rt);	
		$this->template('goodslist');
	}
	
	function ajax_activeop_photos($data=array()){
		if(empty($data['img_id'])) die("非法操作，ID为空！");
		$sdata['is_show']= $data['active'];
		//$this->action('system','add_admin_log','激活店铺店貌图片:ID为=>');
		$this->App->update('user_photos',$sdata,'img_id',$data['img_id']);
		unset($data,$sdata);
	}
	
	function ajax_activeop_goods($data=array()){
		if(empty($data['img_id'])) die("非法操作，ID为空！");
		$type = $data['type']; 
		switch($type){
			case 'is_on_sale':
				$sdata['is_on_sale']= $data['active'];
				//$this->action('system','add_admin_log','修改上架状态:ID为=>'.$data['img_id']);
				break;
			case 'is_hot':
				$sdata['is_hot']= $data['active'];
				//$this->action('system','add_admin_log','修改商品热销状态:ID为=>'.$data['img_id']);
				break;
			case 'is_new':
				$sdata['is_new']= $data['active'];
				//$this->action('system','add_admin_log','修改商品新品状态:ID为=>'.$data['img_id']);
				break;
			case 'is_best':
				$sdata['is_best']= $data['active'];
				//$this->action('system','add_admin_log','修改商品精品状态:ID为=>'.$data['img_id']);
				break;
			case 'is_alone_sale':
				$sdata['is_alone_sale']= $data['active'];
				//$this->action('system','add_admin_log','修改商品礼品状态:ID为=>'.$data['img_id']);
				break;
		}
		
		$this->App->update('user_shopgoods',$sdata,'goods_id',$data['img_id']);
		unset($data,$sdata);
	}
	
	function shopcate(){
		$id = $this->Session->read('Shop.uid');
		$this->set('catelist',$this->get_goods_cate_tree(0,$id));
		$this->template('shopcate');
	}
	
	function shopcateinfo(){
		$this->layout('kong2');
		$rt = array();
		$cid = isset($_GET['id']) ? intval($_GET['id']) : 0; //分类ID
		$id = $this->Session->read('Shop.uid');
		if($cid > 0){ //编辑页面
			 if(isset($_POST)&&!empty($_POST)){
				 if(empty($_POST['cat_name'])){
					 echo'<script>alert("名称不能为空！");</script>';
				 }else{
						$_POST['keywords'] = !empty($_POST['keywords']) ? str_replace(array('，','。','.'),',',$_POST['keywords']) : "";
						$this->App->update('user_shopcate',$_POST,'cat_id',$cid);
						
						$this->jump(ADMIN_URL.'sp.php?type=shopcateinfo&id='.$shopid,0,'编辑成功！'); exit;
				   // }
				 }
				 $rt = $_POST;
			  }
			$sql = "SELECT * FROM `{$this->App->prefix()}user_shopcate` WHERE cat_id='{$cid}' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$this->set('type','edit');

		}else{ //添加页面
			 if(isset($_POST)&&!empty($_POST)){
				 if(empty($_POST['cat_name'])){
					 echo'<script>alert("名称不能为空！");</script>';
				 }else{
				 		$_POST['shopid'] = $id;
						 $_POST['keywords'] = !empty($_POST['keywords']) ? str_replace(array('，','。','.'),',',$_POST['keywords']) : "";
						 $this->App->insert('user_shopcate',$_POST);
						 
						 $this->jump(ADMIN_URL.'sp.php?type=shopcateinfo',0,'添加成功！'); exit;
					// }
				 }
				 $rt = $_POST;
			 }
			 $this->set('type','add');
		}

		$this->set('rt',$rt);

		$this->set('catelist',$this->get_goods_cate_tree(0,$id));
			
		$this->template('shopcateinfo');
	}
	
	//获商品子自分类cat_id
	function get_goods_sub_cat_ids($cid=0){
		//if(!($cid>=0)) return false;
		$rts = $this->get_goods_cate_tree($cid);
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
								if(!empty($rowss['cat_id'])){
									foreach($rowss['cat_id'] as $rowsss){
										$cids[] = $rowsss['id'];
									} // end foreach
								} // end if
							} // end foreach
						} // end if
					} // end foreach
				} // end if
			} // end foreach
		}// end if
		return $cids;
	}
	
	function get_shopcate_sub_cat_ids($cid=0){
		$rts = $this->get_shopcate_tree($cid);
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
								if(!empty($rowss['cat_id'])){
									foreach($rowss['cat_id'] as $rowsss){
										$cids[] = $rowsss['id'];
									} // end foreach
								} // end if
							} // end foreach
						} // end if
					} // end foreach
				} // end if
			} // end foreach
		}// end if
		return $cids;
	}
	
	//获取店里的共享分类
	function get_shopcate_tree($cid = 0)
	{
		$three_arr = array();
		$sql = 'SELECT count(cat_id) FROM `'.$this->App->prefix()."user_cate` WHERE parent_id = '$cid' AND is_show = 1";
		if ($this->App->findvar($sql) || $cid == 0)
		{
			$sql = "SELECT * FROM `{$this->App->prefix()}user_cate` WHERE parent_id = '$cid' ORDER BY parent_id ASC,sort_order ASC, cat_id ASC";
			$res = $this->App->find($sql); 
			foreach ($res as $row)
			{
			   $three_arr[$row['cat_id']]['id']   = $row['cat_id'];
			   $three_arr[$row['cat_id']]['parent_id']   = $row['parent_id'];
			   $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
			   $three_arr[$row['cat_id']]['is_show']   = $row['is_show'];
			   $three_arr[$row['cat_id']]['sort_order'] = $row['sort_order'];
			   
			    if (isset($row['cat_id']) != NULL)
				{
					 $three_arr[$row['cat_id']]['cat_id'] = $this->get_shopcate_tree($row['cat_id']);
				}
			}
		}
		return $three_arr;
	}
	
	
	//获取每个店里的分类
	function get_goods_cate_tree($cid = 0,$shopid=0)
	{
		$three_arr = array();
		$sql = 'SELECT count(cat_id) FROM `'.$this->App->prefix()."user_shopcate` WHERE parent_id = '$cid' AND shopid='$shopid' AND is_show = 1";
		if ($this->App->findvar($sql) || $cid == 0)
		{
			$sql = 'SELECT tb1.cat_name,tb1.cat_id,tb1.parent_id,tb1.is_show, tb1.keywords,tb1.sort_order, COUNT(tb2.cat_id) AS goods_count FROM `'.$this->App->prefix()."user_shopcate` AS tb1";
			$sql .=" LEFT JOIN `{$this->App->prefix()}user_shopgoods` AS tb2";
			$sql .=" ON tb1.cat_id = tb2.cat_id";
			$sql .= " WHERE tb1.parent_id = '$cid' AND shopid='$shopid' GROUP BY tb1.cat_id ORDER BY tb1.parent_id ASC,tb1.sort_order ASC, tb1.cat_id ASC";
			$res = $this->App->find($sql); 
			foreach ($res as $row)
			{
			   $three_arr[$row['cat_id']]['id']   = $row['cat_id'];
			   $three_arr[$row['cat_id']]['parent_id']   = $row['parent_id'];
			   $three_arr[$row['cat_id']]['name'] = $row['cat_name'];
			   $three_arr[$row['cat_id']]['is_show']   = $row['is_show'];
			   $three_arr[$row['cat_id']]['sort_order'] = $row['sort_order'];
			   $three_arr[$row['cat_id']]['goods_count'] = $row['goods_count'];
			   $three_arr[$row['cat_id']]['keywords'] = $row['keywords'];
			   
			    if (isset($row['cat_id']) != NULL)
				{
					 $three_arr[$row['cat_id']]['cat_id'] = $this->get_goods_cate_tree($row['cat_id'],$shopid);
				}
			}
		}
		return $three_arr;
	}
	
	function optongjifen(){
		$shopid = $this->Session->read('Shop.uid');
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		
		$list = 10 ; //每页显示多少个
		$start = ($page-1)*$list;
		$tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_point_change` WHERE shopid='$shopid'");
		$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		
		$sql = "SELECT tb1.*,tb2.user_name,tb2.nickname FROM `{$this->App->prefix()}user_point_change` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id WHERE tb1.shopid='$shopid' ORDER BY tb1.time DESC LIMIT $start,$list";
		$rt['pointlist'] = $this->App->find($sql); 
		
		$rt['page'] = $page;	
		$this->set('rt',$rt);
		$this->template('optongjifen');
	}
	
	function optongmoney(){
		$shopid = $this->Session->read('Shop.uid');
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		
		$list = 10 ; //每页显示多少个
		$start = ($page-1)*$list;
		$tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_money_change` WHERE shopid='$shopid'");
		$rt['pages'] = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		
		$sql = "SELECT tb1.*,tb2.user_name,tb2.nickname FROM `{$this->App->prefix()}user_money_change` AS tb1 LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.uid = tb2.user_id WHERE tb1.shopid='$shopid' ORDER BY tb1.time DESC LIMIT $start,$list";
		$rt['pointlist'] = $this->App->find($sql); 
		
		$rt['page'] = $page;	
		$this->set('rt',$rt);
		$this->template('optongmoney');
	}
	
	function userlist(){
		//$this->layout('kong2');
		$this->template('userlist');
	}
	
	function userxiaofei(){
		$uid = isset($_GET['id']) ? $_GET['id'] : 0;
		$shopid = $this->Session->read('Shop.uid');
		if(isset($_POST) && !empty($_POST)){
			$key = isset($_POST['keyword']) ? $_POST['keyword'] : '';
			if(!empty($key)){
				$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_rank = '1' AND (user_id = '$key' OR nickname LIKE '%$key%'  OR user_name LIKE '%$key%'  OR mobile_phone LIKE '%$key%'  OR user_sn LIKE '%$key%')";
				$uids = $this->App->findvar($sql);
				if($uid != $uids){
					$this->jump(SITE_URL.'shop/sp.php?type=userxiaofei&id='.$uids);exit;
				}
			}
			$money = isset($_POST['money']) ? $_POST['money'] : 0;
			$giftpoint = isset($_POST['giftpoint']) ? $_POST['giftpoint'] : 0;
			$changedesc = isset($_POST['changedesc'])&&!empty($_POST['changedesc']) ? $_POST['changedesc'] : '';
			$tt = 0;
			if($money > 0 && $uid > 0){
				$money = -$money;
				$this->App->insert('user_money_change',array('shopid'=>$shopid,'uid'=>$uid,'money'=>$money,'changedesc'=>"商家操作：".$changedesc.' - 消费','time'=>mktime()));
				$tt = 1;
			}
			if($giftpoint > 0 && $tt ==1){
				$this->App->insert('user_point_change',array('shopid'=>$shopid,'uid'=>$uid,'points'=>$giftpoint,'changedesc'=>"商家操作：".$changedesc.' 消费送积分','time'=>mktime()));
			}
			if($tt==1){
				$this->jump(SITE_URL.'shop/sp.php?type=userxiaofei&id='.$uid,0,'成功消费！');exit;
			}
			
		}
		$rt = array();
		if($uid > 0){
			$sql = "SELECT nickname,user_name,user_rank,mobile_phone FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'";
			$rt = $this->App->findrow($sql);
			$sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid = '$uid' GROUP BY uid";
			$rt['zmoney'] = $this->App->findvar($sql);
			$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid = '$uid' GROUP BY uid";
			$rt['zpoints'] = $this->App->findvar($sql);
		}
		
		$this->set('rt',$rt);

		$this->template('userxiaofei');
	}
	
	
	//供应商列表
	function suppliers(){
		 
		 //排序
		$orderby = "";
		if(isset($_GET['desc'])){
				  $orderby = ' ORDER BY u.'.$_GET['desc'].' DESC';
		}else if(isset($_GET['asc'])){
				  $orderby = ' ORDER BY u.'.$_GET['asc'].' ASC';
		}else {
				  $orderby = ' ORDER BY u.`user_id` DESC';
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
				  $page = 1;
		}
		//条件
		$w = "WHERE l.lid='10'";
		if(isset($_GET['keyword'])&&$_GET['keyword']){
                  $w .= " AND (u.user_name LIKE '%".trim($_GET['keyword'])."%' OR u.email LIKE '%".trim($_GET['keyword'])."%' OR u.birthday LIKE '%".trim($_GET['keyword'])."%' OR u.nickname LIKE '%".trim($_GET['keyword'])."%')";
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(u.user_id) FROM `{$this->App->prefix()}user` AS u LEFT JOIN `{$this->App->prefix()}user_level` AS l ON u.user_rank = l.lid $w";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
			
		$sql = "SELECT u.*,l.level_name,l.discount FROM `{$this->App->prefix()}user` AS u LEFT JOIN `{$this->App->prefix()}user_level` AS l ON u.user_rank = l.lid $w $orderby LIMIT $start,$list";
		$this->set('userlist',$this->App->find($sql));
		$this->template('suppliers');
	}
	
	
	//判断是否已经登陆
	function is_login(){
		$uid = $this->Session->read('Shop.uid');
		if( $uid >0 ) {
			return true;
		}else{
		 	return false;
		}
	}
	
	function login(){
		$this->css('login.css');
		$this->layout('kong');
		$this->template('login');
	}
	
	function ajax_user_login($data=array()){
		if(empty($data)) die("请填写完整信息");
		$user = trim(stripcslashes(strip_tags(nl2br($data['username'])))); //过滤
		if(empty($user)) die("请输入用户名");
		$pass = md5(trim($data['password']));
		if(empty($pass)) die("请输入密码");
		
		$sql = "SELECT password,user_id,last_login,active,user_rank FROM `{$this->App->prefix()}user` WHERE user_rank='10' AND user_name='$user' LIMIT 1";
		$rt = $this->App->findrow($sql);
			if(empty($rt)){ die("请确保您的用户是否存在！");
		}else{
			if($rt['password']==$pass){
				//登录成功,记录登录信息
				$ip = Import::basic()->getip();
				$datas['last_ip'] = empty($ip) ? '0.0.0.0' : $ip;
				$datas['last_login'] = mktime();
				$datas['visit_count'] = '`visit_count`+1';
				$this->Session->write('Shop.prevtime',$rt['last_login']); //记录上一次的登录时间
				
				$this->App->update('user',$datas,'user_id',$rt['user_id']); //更新
				$this->Session->write('Shop.username',$user);
				$this->Session->write('Shop.uid',$rt['user_id']);
				$this->Session->write('Shop.active',$rt['active']);
				$this->Session->write('Shop.rank',$rt['user_rank']);
				$this->Session->write('Shop.lasttime',$datas['last_login']);
				$this->Session->write('Shop.lastip',$datas['last_ip']);
				
				unset($data);
				
			}else{
				//密码是错误的
				die("密码错误");
			}
		}
		
	}
	
	//退出登录
	function logout(){ 
		session_destroy();
		$url = SITE_URL.'shop/login.php';
		$this->jump($url); exit;
	}
	
	
	//用户详情信息
	function user_info(){
		$uid = $this->Session->read('Shop.uid');
		$this->js(array("edit/kindeditor.js"));
		$rt['userinfo'] = array();
		$rt['province'] = $this->get_regions(1);  //获取省列表
		
		if(isset($_POST)&&!empty($_POST)){
			
			$consignee_ = array('consignee'=>'0'); 
			$province_ = array('province'=>'0'); 
			$city_ = array('city'=>'0'); 
			$district_ = array('district'=>'0'); 
			$address_ = array('address'=>'0'); 
			$zipcode_ = array('zipcode'=>'0'); 
			$_POST['s_is_qianyue'] = isset($_POST['s_is_qianyue'])&&intval($_POST['s_is_qianyue'])>0 ? intval($_POST['s_is_qianyue']) : '0';
			$dd = array();
			if(isset($_POST['consignee'])){
					$dd['consignee'] = $_POST['consignee'];
					$_POST = array_diff_key($_POST,$consignee_);
			}
			if(isset($_POST['province'])){
					$dd['province'] = $_POST['province'];
					//$_POST = array_diff_key($_POST,$province_);
			}
			if(isset($_POST['city'])){
					$dd['city'] = $_POST['city'];
					//$_POST = array_diff_key($_POST,$city_);
			}
			if(isset($_POST['district'])){ //USER表记录 “区”，方便查询
					$dd['district'] = $_POST['district'];
					//$_POST = array_diff_key($_POST,$district_);
			}
			if(isset($_POST['address'])){
					$dd['address'] = $_POST['address'];
					$_POST = array_diff_key($_POST,$address_);
			}
			if(isset($_POST['zipcode'])){
					$dd['zipcode'] = $_POST['zipcode'];
					$_POST = array_diff_key($_POST,$zipcode_);
			}
			//额外分类处理
			$sd = array('sub_cat_id'=>'0');
			$subcateid = array();
			if(isset($_POST['sub_cat_id'])){
					$subcateid = $_POST['sub_cat_id'];
					$_POST = array_diff_key($_POST,$sd);
			}
			$_POST['s_ld'] = $_POST['jingdu'].'|'.$_POST['weidu'];
			unset($_POST['jingdu'],$_POST['weidu']);
			//print_r($_POST); exit;
			$dd['user_id'] = $uid;
			$dd['country'] = 1;
			//$dd['email'] = 'xxxx@qq.com';
			//$dd['sex'] = $_POST['sex'];
			$dd['is_own'] = 1;
			$sql = "SELECT address_id FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
			$rsid = $this->App->findvar($sql);

			//更新地址表
			if(empty($rsid)){ //添加
				$this->App->insert('user_address',$dd);
			}else{ //更新
				//$this->App->update('user_address',$dd,'address_id',$rsid);
			}
			unset($dd);
		}
		
		
		if($uid>0){ //编辑操作
			  if(isset($_POST)&&!empty($_POST)){
                     if(empty($_POST['nickname'])){
                        echo'<script>alert("店铺名称不能为空！");</script>';
                     }else{
                        $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_name='$_POST[user_name]' LIMIT 1";
                        $rs = $this->App->findvar($sql);
                        if(!empty($rs)&&$rs!=$uid){
                              	
                        }else{
                                $this->App->update('user',$_POST,'user_id',$uid);
								 //子分类
								if(!empty($subcateid)){
									   foreach($subcateid as $ids){
									   	   if($ids > 0){
											   $dd = array();
											   $dd['shop_id'] = $uid;
											   $dd['cat_id'] = $ids;
											   $this->App->insert('user_catesub',$dd);
										   }
									   }
								}
								$this->jump("",0,'修改成功!');
						}
                     } // end if
                } // end post
				
				//用户信息
                $sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='{$uid}' LIMIT 1";
                $rt['userinfo'] = $this->App->findrow($sql);
				//
				$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
				$rt['userress'] = $this->App->findrow($sql);
				
				$rt['city'] = $this->get_regions(2,$rt['userress']['province']);  //城市
				$rt['district'] = $this->get_regions(3,$rt['userress']['city']);  //区
				
				$sql = "SELECT SUM(money) AS zdata FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
				$sql .= " UNION SELECT SUM(points) AS zdata FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
				$uu = $this->App->findcol($sql);
				if(count($uu)=='2'){
					$rt['userinfo']['user_money'] = $uu[0];
					$rt['userinfo']['pay_points'] = $uu[1];
					unset($uu);
				}
		
				 //其他子分类
				$sql = " SELECT tb1.*,tb2.cat_name FROM `{$this->App->prefix()}user_catesub` AS tb1";
				$sql .=" LEFT JOIN `{$this->App->prefix()}user_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
				$sql .=" WHERE tb1.shop_id='$uid'";
				$this->set('subcatelist',$this->App->find($sql));
                $this->set('type','edit');
		}else{ //添加操作
                    if(isset($_POST)&&!empty($_POST)){
                         if(empty($_POST['nickname'])){
                             echo'<script>alert("店铺名称不能为空！");</script>';
                         }else{
                             $sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE user_name='$_POST[user_name]' LIMIT 1";
                             $rs = $this->App->findvar($sql);
                             if(!empty($rs)){
                                  
                             }else{
							 	 $_POST['user_rank'] = 10;
                                 $_POST['password'] = trim(md5($_POST['password']));
                                 $_POST['reg_time'] = mktime();
                                 $_POST['active'] = 1;
                                 if($this->App->insert('user',$_POST)){
								 	$uid = $this->App->iid();
								 	 //子分类
									if(!empty($subcateid)){
										   foreach($subcateid as $ids){
											   if($ids > 0){
												   $dd = array();
												   $dd['shop_id'] = $uid;
												   $dd['cat_id'] = $ids;
												   $this->App->insert('user_catesub',$dd);
											   }
										   }
									}
                                 	
								 }
                                 $this->jump("",0,'添加成功!');
                             }
                         }
                    }
                 $this->set('type','add');
		}
		
		$this->set('rt',$rt);
		
		$this->set('catelist',$this->get_shopcate_tree());
		
		$this->template('user_info');
	}
	
	
   
	function shopbigcate(){
		$this->set('catelist',$this->get_shopcate_tree());
		$this->template('shopbigcate');
	}
	
	function shopbigcateinfo(){
		$rt = array();
		$cid = isset($_GET['id']) ? intval($_GET['id']) : 0; //分类ID
		if($cid > 0){ //编辑页面
			 if(isset($_POST)&&!empty($_POST)){
				 if(empty($_POST['cat_name'])){
					 echo'<script>alert("名称不能为空！");</script>';
				 }else{
						$_POST['keywords'] = !empty($_POST['keywords']) ? str_replace(array('，','。','.'),',',$_POST['keywords']) : "";
						$this->App->update('user_cate',$_POST,'cat_id',$cid);
						//$this->action('system','add_admin_log','修改店铺分类:'.$_POST['cat_name']);
						//$this->action('common','showdiv',$this->getthisurl());
				   // }
				 }
				 $rt = $_POST;
			  }
			$sql = "SELECT * FROM `{$this->App->prefix()}user_cate` WHERE cat_id='{$cid}' LIMIT 1";
			$rt = $this->App->findrow($sql);
			$this->set('type','edit');

		}else{ //添加页面
			 if(isset($_POST)&&!empty($_POST)){
				 if(empty($_POST['cat_name'])){
					 echo'<script>alert("名称不能为空！");</script>';
				 }else{
						 $_POST['keywords'] = !empty($_POST['keywords']) ? str_replace(array('，','。','.'),',',$_POST['keywords']) : "";
						 $this->App->insert('user_cate',$_POST);
						 //$this->action('system','add_admin_log','添加店铺分类:'.$_POST['cat_name']);
						 //$this->action('common','showdiv',$this->getthisurl());
					// }
				 }
				 $rt = $_POST;
			 }
			 $this->set('type','add');
		}

		$this->set('rt',$rt);
		$this->set('catelist',$this->get_shopcate_tree());
		$this->template('shopbigcateinfo');
	}
	
	function editpass(){
		$uid = $this->Session->read('Shop.uid');
		$sql = "SELECT user_name FROM `{$this->App->prefix()}user` WHERE user_id='{$uid}' LIMIT 1";
        $rt['user_name'] = $this->App->findvar($sql);
		
		if(isset($_POST)&&!empty($_POST['password'])){
			$pas = md5(trim($_POST['password']));
			if($this->App->update('user',array('password'=>$pas),'user_id',$uid)){
				$this->jump("",0,'修改成功！!');
			}
		}
			  
			  
		$this->set('rt',$rt);	
		$this->template('editpass');
	}
	
	//会员的收货地址
	function user_consignee_address($uid=0){
		if(empty($uid)){ $this->jump('user.php?type=list'); exit;}
		if(isset($_POST)&&!empty($_POST)){
			if(empty($_POST['province'])){
				$this->jump('',0,'选择省份！'); exit;
			}else if(empty($_POST['city'])){
				$this->jump('',0,'选择城市！');exit;
			}else if(empty($_POST['consignee'])){
				$this->jump('',0,'收货人不能为空！');exit;
			}else if(empty($_POST['email'])){
				$this->jump('',0,'电子邮箱不能为空！');exit;
			}else if(empty($_POST['address'])){
				$this->jump('',0,'收货地址不能为空！');exit;
			}else if(empty($_POST['tel'])){
				$this->jump('',0,'电话号码不能为空！');exit;
			}
			
			if(!isset($_POST['address_id'])&&empty($_POST['address_id'])){ //添加
					$_POST['user_id'] = $uid;
					if($this->App->insert('user_address',$_POST)){
							$this->jump('',0,'添加成功！');exit;
					}else{
						$this->jump('',0,'添加失败！');exit;
					}
				
				//$this->set('post',$_POST);
			}else{ //修改
				$address_id = $_POST['address_id'];
				$_POST = array_diff_key($_POST,array('address_id'=>'0'));
				if($this->App->update('user_address',$_POST,'address_id',$address_id )){
						$this->jump('',0,'更新成功！');exit;
				}
				else{
					$this->jump('',0,'更新失败！');exit;
				}
			}
		}
		
		$rt['province'] = $this->get_regions(1);  //获取省列表
		//当前用户的收货地址
		$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='0'";
		$rt['userress'] = $this->App->find($sql);
		if(!empty($rt['userress'])){
			foreach($rt['userress'] as $k=>$row){
				//$k++;
				$rt['city'][$row['address_id']] = $this->get_regions(2,$row['province']);  //城市
				$rt['district'][$row['address_id']] = $this->get_regions(3,$row['city']);  //区
			}
		}
		
		$this->set('rt',$rt);
		$this->template('user_consignee_address');
	}
	
	//获取地区
	function get_regions($type,$parent_id=0){
		$p = "";
		if(!empty($parent_id)) $p = "AND parent_id='$parent_id'";
		
		$sql= "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' {$p} ORDER BY region_id ASC";
		return  $this->App->find($sql);
	}
	
	//会员积分设置
	function user_setjifen(){
		$this->template('user_setjifen');
	}
	
  //删除店铺的子分类
	function ajax_del_subcate($data=array()){
		if(empty($data['cid'])|| empty($data['shopid'])) die("传送的ID为空！");
		$cid = $data['cid'];
		$shopid = $data['shopid'];
		$sql = "DELETE FROM `{$this->App->prefix()}user_catesub` WHERE cat_id='$cid' AND shop_id='$shopid'";
		if($this->App->query($sql)){
			echo "";
		}else{
			die("删除中发生意外错误！");
		}
	}
	
  //ajax获取条件的用户
  function ajax_getuser($data=array()){
		$err = 0;
		$json = Import::json();
		$result = array('error' => $err, 'message' => ''); 
		if(empty($data)){
			$result['error'] = 2;
			$result['message'] = '传送的数据为空！';
			die($json->encode($result));
		}
		$wobj = $json->decode($data); //反json ,返回值为对象
		$page = $wobj->page;
		$returnw = $wobj->returnw;
		if(!empty($returnw)){
			unset($wobj,$data);
			$wobj = $json->decode(base64_decode($returnw)); //反json ,返回值为对象
			$data = base64_decode($returnw);
		}
		$keyword = $wobj->keys;
		$province = $wobj->province;
		$city = $wobj->city;
		$district = $wobj->district;
		$user_rank = $wobj->user_rank;
		$sex = $wobj->sex;
		$start_birthday = $wobj->start_birthday;
		$end_birthday = $wobj->end_birthday;
		$start_reg_date = $wobj->start_reg_date;
		$end_reg_date = $wobj->end_reg_date;
		$reg_date = $wobj->reg_date;
		$type = $wobj->type;
		$types = array('salerank','poitsrank','logincount');
		$type = in_array($type,$types) ? $type : "";
		
		$comd = array();
		if(intval($province)>0){
			$comd[] = "ua.province='$province'";
		}
		if(intval($city)>0){
			$comd[] = "ua.city='$city'";
		}
		if(intval($district)>0){
			$comd[] = "ua.district='$district'";
		}
		if(intval($user_rank)>0){
			$comd[] = "u.user_rank='$user_rank'";
		}
		if(intval($sex)>0){
			$sex = $sex-1;
			$comd[] = "(u.sex='$sex' OR ua.sex='$sex')";
		}
		if($end_birthday > $start_birthday){
			$comd[] = "u.birthday BETWEEN '$start_birthday' AND '$end_birthday'";
		}
		if($end_reg_date > $start_reg_date){
			$end_reg_date = strtotime($end_reg_date);
			$start_reg_date = strtotime($start_reg_date);
			$comd[] = "u.reg_time BETWEEN '$start_reg_date' AND '$end_reg_date'";
		}
		$orderby = " ORDER BY u.user_id DESC";
		switch($type){
			case 'salerank':
				$orderby = " ORDER BY salerank DESC, u.user_id ASC";
				break;
			case 'poitsrank':
				$orderby = " ORDER BY pointrank DESC, u.user_id ASC";
				break;
			case 'logincount':
				$orderby = " ORDER BY visit_count DESC, u.user_id ASC";
				break;
		}
		if(!empty($keyword)){
			$comd[] = "(u.user_name LIKE '%$keyword%' OR u.email LIKE '%$keyword%' OR u.nickname LIKE '%$keyword%' OR ua.consignee LIKE '%$keyword%' OR ua.email LIKE '%$keyword%')";
		}
		if(!($page>0)) $page = 1;
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT distinct u.user_id FROM `{$this->App->prefix()}user` AS u";
		$sql .=" LEFT JOIN `{$this->App->prefix()}user_address` AS ua ON u.user_id=ua.user_id AND ua.is_own='1'";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_info` AS goi ON u.user_id=goi.user_id AND goi.pay_status='2'";
		$sql .=" LEFT JOIN `{$this->App->prefix()}user_point_change` AS upc ON u.user_id=upc.uid";
		$sql .= !empty($comd) ? " WHERE ".implode(' AND ',$comd)." GROUP BY u.user_id" : " GROUP BY u.user_id";
		$tts = $this->App->findcol($sql);
		$tt = count($tts);
		$getuserpage = Import::basic()->ajax_page($tt,$list,$page,'ajax_getuser',array(base64_encode($data)));
		$this->set('getuserpage',$getuserpage);
		$sql = "SELECT distinct u.user_id,u.user_name,u.birthday,u.reg_time,u.visit_count,ua.sex,ua.email,SUM(goi.goods_amount+goi.shipping_fee) AS salerank,SUM(upc.points) AS pointrank FROM `{$this->App->prefix()}user` AS u";
		$sql .=" LEFT JOIN `{$this->App->prefix()}user_address` AS ua ON u.user_id=ua.user_id AND ua.is_own='1'";
		$sql .=" LEFT JOIN `{$this->App->prefix()}goods_order_info` AS goi ON u.user_id=goi.user_id AND goi.pay_status='2'";
		$sql .=" LEFT JOIN `{$this->App->prefix()}user_point_change` AS upc ON u.user_id=upc.uid";
		$sql .= !empty($comd) ? " WHERE ".implode(' AND ',$comd)." GROUP BY u.user_id" : " GROUP BY u.user_id";
		$sql .="$orderby LIMIT $start,$list";
		$rt = $this->App->find($sql);
		$this->set('rt_user',$rt);
		echo $result = $this->fetch('ajax_need_send_user',true);
		unset($rt); exit;
  }
  
 
  //获取地区
  function ajax_get_ress($data=array()){
   		$type = $data['type'];
		$parent_id = $data['parent_id'];
		if(empty($type)||empty($parent_id)){
			if($type==1){
				$str = '<option value="0">请选择...</option>';
			}else if($type==2){
				$str = '<option value="0">请选择...</option>';
			}else if($type==3){
				$str = '<option value="0">请选择...</option>';
			}
			die($str);
		}
		$sql= "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' AND parent_id='$parent_id' ORDER BY region_id ASC";
		$rt = $this->App->find($sql);
		if(!empty($rt)){
			if($type==1){
				$str = '<option value="0">请选择...</option>';
			}else if($type==2){
				$str = '<option value="0">请选择...</option>';
			}else if($type==3){
				$str = '<option value="0">请选择...</option>';
			}
				
			foreach($rt as $row){
			$str .='<option value="'.$row['region_id'].'">'.$row['region_name'].'</option>'."\n";
			}
			die($str);
		}
		
   }
   
   //ajax删除用户收货地址
   function ajax_delress($id=0,$uid=0){
		if(empty($uid)) die("用户id不存在！！");
		if(empty($id)) die("非法删除！");
		
		if($this->App->delete('user_address','address_id',$id)){
		}else{
			die("删除失败!");
		}
	}
	
	//ajax删除用户
	function ajax_bathdel($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		
		$sql = "SELECT avatar  FROM `{$this->App->prefix()}user` WHERE user_id IN(".@implode(',',$id_arr).")";
		$imgs = $this->App->findcol($sql); 
		if(!empty($imgs)){
			foreach($imgs as $img){
				if(empty($img)) continue;
				Import::fileop()->delete_file(SYS_PATH.$img); //
			}
			unset($imgs);
		}
		
		foreach($id_arr as $id){
		  if(Import::basic()->int_preg($id))
		  $this->App->delete('user','user_id',$id);
		  //删除收货地址
		  $this->App->delete('user_address','user_id',$id);
		  //删除积分改变
		   $this->App->delete('user_point_change','uid',$id);
		   //删余额改变
		   $this->App->delete('user_money_change','uid',$id);
		   //删除评论表
		   $this->App->delete('comment','user_id',$id);
		  // $this->App->delete('comment','parent_id',$id);
		}
		//$this->action('system','add_admin_log','删除会员：'.@implode(',',$id_arr));
	}
	//排量激活会员
	function ajax_activeop($data=array()){
		if(empty($data['uid'])) die("非法操作，ID为空！");
		$sdata['active']= $data['active'];
		//$this->action('system','add_admin_log','批量激活会员:ID为=>'.$data['uid']);
		$this->App->update('user',$sdata,'user_id',$data['uid']);
		unset($data,$sdata);
	}
	
	//检查表单
	function ajax_checkform($val="",$type=""){
		switch($type){
			case 'email':
				//Import::basic()->email_preg();
				break;
		}
	}
	
	//给会员增加积分或者增加钱
	function ajax_change_user_points_money($data=array()){
		if(empty($data)){ echo "";exit;}
		
		$type = $data['type'];
		$val = $data['val'];
		$uid = $data['uid'];
		if(empty($uid) || empty($type) || empty($val)){
		 echo "不允许操作！";exit;
		}
		$uu = "";
		if($type=='money'){
			$this->App->insert('user_money_change',array('money'=>$val,'changedesc'=>'管理改变资金','time'=>mktime(),'uid'=>$uid));
			$sql = "SELECT SUM(money) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'";
			$uu = $this->App->findvar($sql);
		}else if($type=='points'){
			$this->App->insert('user_point_change',array('points'=>$val,'changedesc'=>'管理改变积分','time'=>mktime(),'uid'=>$uid));
			$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
			$uu = $this->App->findvar($sql);
		}
		echo $uu;
		
	}
	
	function ajax_user_pointchange(){
			$page = isset($_POST['page'])&&intval($_POST['page'])>0 ? intval($_POST['page']) : 1;
			if(empty($page)){
				   $page = 1;
			}
			$uid = $_POST['uid'];
			$list = 5 ; //每页显示多少个
			$start = ($page-1)*$list;
			$tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'");
			$rt['userpointpage'] = Import::basic()->ajax_page($tt,$list,$page,'get_userpoint_page_list',array($uid));
			$sql = "SELECT * FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";
			$rt['userpointlist'] = $this->App->find($sql); 
			$rt['page'] = $page;
			$this->set('rt',$rt);
			echo  $this->fetch('ajax_user_point',true);
			exit;
	}
	
	function ajax_user_mymoney(){
			$page = isset($_POST['page'])&&intval($_POST['page'])>0 ? intval($_POST['page']) : 1;
			if(empty($page)){
				   $page = 1;
			}
			$uid = $_POST['uid'];
			$list = 5 ; //每页显示多少个
			$start = ($page-1)*$list;
			$tt = $this->App->findvar("SELECT COUNT(cid) FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid'");
			$rt['usermoneypage'] = Import::basic()->ajax_page($tt,$list,$page,'get_usermoney_page_list',array($uid));
			$sql = "SELECT * FROM `{$this->App->prefix()}user_money_change` WHERE uid='$uid' ORDER BY time DESC LIMIT $start,$list";
			$rt['usermoneylist'] = $this->App->find($sql); 
			$this->set('rt',$rt);
			echo  $this->fetch('ajax_user_money',true);
			exit;
	}
	
	
	
}
?>