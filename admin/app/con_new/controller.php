<?php

class Con_newController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数
		*/
		$this->css('content.css');
	}
	
	/*
	* 分类列表
	*/
	function catelist(){
		//$catelist = $this->get_cate_tree();
		$this->set('catelist',$this->action('common','get_cate_tree',0,'new'));
		$this->template('con_new_cate_list');
	}
	
	/*
	* 分类详情页面
	*/
	function cateinfo($type='cateadd',$id=0){ 
		$rt = array();
		if($type=='cateedit'){
			if($id==0){
				$this->jump('con_new.php?type=catelist',0,'非法操作，ID为空！'); exit;
			}else{
				if(!empty($_POST)){
					$_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
					$_POST['uptime'] = time();
					$this->App->update('article_cate',$_POST,'cat_id',$id);
					//$this->action('system','add_admin_log','修改新闻咨询分类:'.$_POST['cat_name']);
					$this->action('common','showdiv',$this->getthisurl());
				}
				$sql = "SELECT * FROM `{$this->App->prefix()}article_cate` WHERE cat_id='{$id}'";
				$rt = $this->App->findrow($sql);
			}
		}else{
			if(!empty($_POST)){
				$_POST['addtime'] = time();
				$_POST['uptime'] = time();
				$_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
				$this->App->insert('article_cate',$_POST);
				//$this->action('system','add_admin_log','添加新闻咨询分类:'.$_POST['cat_name']);
				$this->action('common','showdiv',$this->getthisurl());
				$_POST['cat_img'] = "";
				$rt = $_POST;
			}
		}
		//$catelist = $this->get_cate_tree(0,'new');
		$this->set('catelist',$this->action('common','get_cate_tree',0,'new'));
		
		$this->set('rt',$rt);
		$this->set('type',$type);
		$this->template('con_new_cate_info');
	}
	
	/*
	* 内容列表
	*/
	function newlist($type='new'){
		$w="";
		if(!empty($type)){
			$w = " WHERE tb2.type='$type' ";
		}
		//排序
        $orderby = "";
        if(isset($_GET['desc'])){
			  $orderby = ' ORDER BY '.$_GET['desc'].' DESC';
		}else if(isset($_GET['asc'])){
			  $orderby = ' ORDER BY '.$_GET['asc'].' ASC';
		}else {
		  	  $orderby = ' ORDER BY tb1.`article_id` DESC';
		}
		//分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			  $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
		$sql = "SELECT COUNT(tb1.article_id) FROM `{$this->App->prefix()}article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2";
		$sql .=" ON tb1.cat_id = tb2.cat_id";
		$sql .="{$w}";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);

		$sql = "SELECT tb1.*,tb2.cat_name FROM `{$this->App->prefix()}article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2";
		$sql .=" ON tb1.cat_id = tb2.cat_id";
		$sql .="{$w} {$orderby} LIMIT $start,$list";

		$this->set('newlist',$this->App->find($sql));
		$this->template('con_new_list');
	}
	
	/*
	* 内容详情页面
	*/
	function newinfo($type='newadd',$id=0){
		$this->js("edit/kindeditor.js"); 
		$rt = array();

		if($type=='newedit'){
			if($id==0){
				$this->jump('con_new.php?type=newlist',0,'非法操作，ID为空！'); exit;
			}else{
				if(!empty($_POST)){
					/*if(!empty($_POST['content'])){
						if(!(Import::basic()->sql_check($_POST['content']))){
							die("非法插入，不允许出现select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file
					|outfile字段");
						}
					}*/
					$_POST['s_ld'] = $_POST['jingdu'].'|'.$_POST['weidu'];
					unset($_POST['jingdu'],$_POST['weidu']);
					$_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
					$_POST['uptime'] = time();
					$_POST['content'] = @str_replace('./../photos/',SYS_PHOTOS_URL,$_POST['content']); //替换为绝对路径的链接
					$this->App->update('article',$_POST,'article_id',$id);
					//$this->action('system','add_admin_log','修改新闻文章:'.$_POST['article_title']);
					$this->action('common','showdiv',$this->getthisurl());
				}
				$sql = "SELECT * FROM `{$this->App->prefix()}article` WHERE article_id='{$id}'";
				$rt = $this->App->findrow($sql);
			}
			$rt['citys'] = $this->action('user','get_regions',2,$rt['province']);  //城市
			$rt['districts'] = $this->action('user','get_regions',3,$rt['city']);  //区

		}else{
			if(!empty($_POST)){
				$_POST['s_ld'] = $_POST['jingdu'].'|'.$_POST['weidu'];
				unset($_POST['jingdu'],$_POST['weidu']);
				$_POST['addtime'] = time();
				$_POST['uptime'] = time();
				$_POST['meta_keys'] = !empty($_POST['meta_keys']) ? str_replace(array('，','。','.'),',',$_POST['meta_keys']) : "";
				$_POST['content'] = @str_replace('./../photos/',SYS_PHOTOS_URL,$_POST['content']); //替换为绝对路径的链接
				$this->App->insert('article',$_POST);
				//$this->action('system','add_admin_log','添加新闻文章:'.$_POST['article_title']);
				$this->action('common','showdiv',$this->getthisurl());
				$rt = $_POST;
			}
		}
		$rt['provinces'] = $this->action('user','get_regions',1);  //获取省列表
		$this->set('rt',$rt);
		$this->set('catids',$this->action('common','get_cate_tree',0,'new'));
		$this->set('type',$type);
		$this->template('con_new_info');
	}
	

	function ajax_check_cat_name($data=array()){
		$cat_id = $data['cat_id'];
		$cat_name = $data['cat_name'];
		$type = $data['type'];
		if(!empty($type)){
			$type = " AND type='$type'";
		}
		if(empty($cat_name)){
			die("分类名称不能为空！");
		}
		//print_r($data);
		$sql = "SELECT cat_id,cat_name FROM `{$this->App->prefix()}article_cate` WHERE cat_name='{$cat_name}' $type LIMIT 1";
		$rt = $this->App->find($sql);
		//print_r($rt);
		$cname = isset($rt[0]['cat_name']) ? $rt[0]['cat_name'] : "";
		$cid = isset($rt[0]['cat_id']) ? $rt[0]['cat_id'] : "";
		if(!empty($cname) && $cid!=$cat_id){
			die("该分类名称已经存在！");
		}
	}
	//ajax删除分类
	function ajax_delcate($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		$new_ids = array();
		foreach($id_arr as $id){
			$getid = $this->action('common','get_sub_cat_ids',$id);
			if(!empty($getid)){
				foreach($getid as $id){
					$new_ids[] = $id;
				}
			}
		}//end foreach
		
		if(!empty($new_ids)){
			$new_id = array_unique($new_ids);
			unset($new_ids);
			
			$sql = "SELECT article_img FROM `{$this->App->prefix()}article_cate` WHERE cat_id IN(".@implode(',',$new_id).")";
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
					$this->App->delete('article','cat_id',$id);
					//删除指定分类
					$this->App->delete('article_cate','cat_id',$id);
				}
			}
		}
		unset($id_arr,$new_id);
		$this->action('system','add_admin_log','删除分类:ID为=>'.@implode(',',$id_arr));
	}
	
	function ajax_delarticle($ids=0){
		if(empty($ids)) die("非法删除，删除ID为空！");
		$id_arr = @explode('+',$ids);
		
		$sql = "SELECT article_img FROM `{$this->App->prefix()}article` WHERE article_id IN(".@implode(',',$id_arr).")";
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
		  $this->App->delete('article','article_id',$id);
		}
		$this->action('system','add_admin_log','删除新闻咨询文章：'.@implode(',',$id_arr));
	}
	//分类列表激活
	function ajax_active($data=array()){
		if(empty($data['cid'])) die("非法操作，ID为空！");
		
		if($data['type']=='show_in_nav'){
			//添加到导航栏表
			$sql = "SELECT cid FROM `{$this->App->prefix()}nav` WHERE cid ='{$data[cid]}' AND ctype ='c' LIMIT 1";
			$checkvar = $this->App->findvar($sql);
			$sdata['show_in_nav']= $data['active'];
			
			if(empty($checkvar)){
				$cdata['ctype'] = 'c';
				$cdata['cid'] = $data['cid'];
				$cdata['name'] = $this->App->findvar("SELECT cat_name FROM `{$this->App->prefix()}article_cate` WHERE cat_id ='{$data[cid]}' LIMIT 1");
				$cdata['is_show'] = 1;
				$cdata['is_opennew'] = 0;
				$cdata['url'] = 'category.php?cid='.$data['cid'];
				$this->App->insert('nav',$cdata);
				unset($cdata);
			 }else{ 
			 	//$this->App->delete('nav','cid',$checkvar);
				$sql = "DELETE FROM `{$this->App->prefix()}nav` WHERE cid='$checkvar' and ctype='c'";
			 	$this->App->query($sql);
			 }
			 
			$this->action('system','add_admin_log','修改是否显示在导航栏:ID为=>'.$data['cid']);
		}else if($data['type']=='is_show'){
		
			$sdata['is_show']= $data['active'];
			$this->action('system','add_admin_log','修改状态:ID为=>'.$data['cid']);
		}else{
			die('没有指派类型！');
		}
		$this->App->update('article_cate',$sdata,'cat_id',$data['cid']);
		unset($data,$sdata);
	}
	
	//文章列表激活
	function ajax_alt_activeop($data=array()){
		if(empty($data['cid'])) die("非法操作，ID为空！");
		if($data['type']=='is_show'){
			$sdata['is_show']= $data['active'];
			$this->action('system','add_admin_log','修改新闻文章审核状态:ID为=>'.$data['cid']);
		}else if($data['type']=='is_top'){
			$sdata['is_top']= $data['active'];
			$this->action('system','add_admin_log','修改新闻文章置顶状态:ID为=>'.$data['cid']);
		}
		$this->App->update('article',$sdata,'article_id',$data['cid']);
		unset($data);
	}
	
	function ajax_vieworder($data=array()){
		if(empty($data['id'])) return "50";
		$sdata['vieworder'] = empty($data['val']) ? 50 : $data['val'];
		$this->App->update('article_cate',$sdata,'cat_id',$data['id']);
	}
}
?>