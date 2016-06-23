<?php

class SeoController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/
		$this->css('content.css');
	}
	
	//分类 新闻资讯
	function seo_category($data=array(),$type='new'){
	    extract($data);
		switch($type){
			case 'new':
			    $na = "新闻咨询";
				break;
			case 'customer':
				$na = "客户列表";
				break;
			case 'case':
				$na = "模版案例";
				break;
			case 'web':
				$na = "网站建设";
				break;
		}
		if(!empty($cat_id)){
			if(count($cat_id)==count($names)&&count($cat_title)==count($meta_keys)&&count($meta_desc)==count($cat_id)){
				foreach($cat_id as $k=>$cid){
					if($cid<=0) continue;
					if(isset($sdata)) unset($sdata);
					$sdata = array('cat_name'=>$names[$k],'cat_title'=>$cat_title[$k],'meta_keys'=>$meta_keys[$k],'meta_desc'=>$meta_desc[$k]);
					$this->App->update('article_cate',$sdata,'cat_id',$cid);
				}
				$this->action('system','add_admin_log','批量修改'.$na.'分类SEO优化:');
				$this->action('common','showdiv',$this->getthisurl());
			}
		}
		unset($data);
		
		$catelist = $this->action('common','get_cate_tree',0,$type);
		
		$this->set('catelist',$catelist);
		$this->set('catename',$na);
		$this->template('seo_category');
	}
	
	function seo_category_content($data=array(),$type='new'){
		extract($data);
		switch($type){
			case 'new':
			    $na = "新闻咨询";
				break;
			case 'customer':
				$na = "客户列表";
				break;
			case 'case':
				$na = "模版案例";
				break;
			case 'web':
				$na = "网站建设";
				break;
		}
		if(!empty($article_id)){
			if(count($article_id)==count($article_title)&&count($meta_keys)==count($meta_desc)&&count($meta_desc)==count($article_id)){
				foreach($article_id as $k=>$cid){
					if($cid<=0) continue;
					if(isset($sdata)) unset($sdata);
					$sdata = array('article_title'=>$article_title[$k],'meta_keys'=>$meta_keys[$k],'meta_desc'=>$meta_desc[$k]);
					$this->App->update('article',$sdata,'article_id',$cid);
				}
				$this->action('system','add_admin_log','批量修改'.$na.'内容SEO优化:');
				$this->action('common','showdiv',$this->getthisurl());
			}
		}
		unset($data);
		
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
		$sql .=" WHERE tb2.type='$type'";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
		
		$sql = "SELECT tb1.article_title,tb1.article_id,tb1.meta_keys,tb1.meta_desc,tb2.cat_name FROM `{$this->App->prefix()}article` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}article_cate` AS tb2";
		$sql .=" ON tb1.cat_id = tb2.cat_id";
		$sql .=" WHERE tb2.type='$type' ORDER BY tb1.article_id DESC LIMIT $start,$list";
		$this->set('articlelist',$this->App->find($sql));
		$this->template('seo_category_content');
	}

}
?>