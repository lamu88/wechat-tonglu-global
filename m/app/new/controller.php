<?php
class NewController extends Controller{
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
    }
	
    function index($cat_id=0,$page=1){ 
        $rt = $this->Cache->read(3600);
		$type = 'new';
        if(is_null($rt)) {
				if(empty($cat_id)){
					$c = $this->App->findvar("SELECT cat_id FROM `{$this->App->prefix()}article_cate` WHERE type='$type' AND parent_id='0' ORDER BY cat_id ASC");
				 }else{
				 	$c = $cat_id;
				 }
				//获取当前分类信息
				$sql = "SELECT * FROM `{$this->App->prefix()}article_cate` WHERE type='$type' AND cat_id='$c' LIMIT 1";
				$rt['catemes'] = $this->App->findrow($sql);
				if(empty($rt['catemes'])){ $this->jump(SITE_URL); exit;}
				
				$cityid = 0;
				$comd[] = "tb1.is_show='1'";
				$comd[] = "tb3.type='new'";
				if($cityid > 0){
					$comd[] = "tb1.district = '$cityid'";
				}
				
				//下级分类ID
				if($cat_id > 0){
					$sourceid = array($cat_id);
					$get_cid = $this->action('article','get_sub_cat_ids',$cat_id,'new');
					if(!empty($get_cid)){
						$subcid=array_merge($get_cid,$sourceid);
					}else{
						$subcid = $sourceid;
					}
					unset($sourceid,$get_cid);
					$comd[] = "(tb1.cat_id IN(".@implode(',',$subcid).") OR tb2.cat_id IN(".@implode(',',$subcid)."))";
				}
				
				
				$w = "WHERE ".implode(' AND ',$comd);
				
				$page = (isset($_GET['page'])&&intval($_GET['page'])>0) ? intval($_GET['page']) : 1;
				//分页
				if(empty($page)){
					   $page = 1;
				}
				$list = 10;
				$start = ($page-1)*$list;
				$sql = "SELECT COUNT(distinct tb1.article_id) FROM `{$this->App->prefix()}article` AS tb1 LEFT JOIN `{$this->App->prefix()}article_cate` AS tb3 ON tb3.cat_id = tb1.cat_id LEFT JOIN `{$this->App->prefix()}article_cate_sub` AS tb2 ON tb2.article_id = tb1.article_id $w";
				$tt = $this->App->findvar($sql);
				$rt['categorypage'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
			
				$sql = "SELECT distinct tb1.article_id,tb1.article_title,tb1.article_img,tb1.addtime,tb3.cat_name FROM `{$this->App->prefix()}article` AS tb1 LEFT JOIN `{$this->App->prefix()}article_cate` AS tb3 ON tb3.cat_id = tb1.cat_id LEFT JOIN `{$this->App->prefix()}article_cate_sub` AS tb2 ON tb2.article_id = tb1.article_id $w ORDER BY tb1.is_top DESC,tb1.vieworder ASC, tb1.article_id DESC LIMIT $start,$list";
				$rt['catecon'] = $this->App->find($sql);
				
				
				//分类列表		
				$rt['menu'] = $this->action('article','get_cate_tree',0,'new');
				
				
            	$this->Cache->write($rt);
         }

			 //页面头信息
			$title = (!empty($rt['catemes']['cat_title']) ? $rt['catemes']['cat_title'] : $rt['catemes']['cat_name']);
			$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
			$this->meta("title",$title);
			$this->meta("keywords",$rt['catemes']['meta_keys']);
			$this->meta("description",$rt['catemes']['meta_desc']);
			$this->set('rt',$rt);
			$this->set('page',$page);
			$this->template('con_'.$type);	
    }
	
	function news(){
		$this->action('common','checkjump');
		$cat_id = isset($_GET['cid']) ? $_GET['cid'] : 0;
		$page = (isset($_GET['page'])&&intval($_GET['page'])>0) ? intval($_GET['page']) : 1;
		//分页
		if(empty($page)){
			   $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
				
		if(empty($cat_id)){
			$c = $this->App->findvar("SELECT cat_id FROM `{$this->App->prefix()}wx_cate` WHERE parent_id='0' ORDER BY cat_id ASC");
		 }else{
			$c = $cat_id;
		 }
		//获取当前分类信息
		$sql = "SELECT * FROM `{$this->App->prefix()}wx_cate` WHERE cat_id='$c' LIMIT 1";
		$rt['catemes'] = $this->App->findrow($sql);
		if(empty($rt['catemes'])){ $this->jump(SITE_URL); exit;}
		$this->title($rt['catemes']['cat_name']);
		
		$sql = "SELECT COUNT(article_id) FROM `{$this->App->prefix()}wx_article` WHERE cat_id='$c' AND is_show='1'";
		$tt = $this->App->findvar($sql);
		$pages = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		$this->set('pages',$pages);
		
		$sql = "SELECT article_title, addtime , article_id FROM `{$this->App->prefix()}wx_article` WHERE cat_id='$c' AND is_show='1' ORDER BY vieworder ASC,article_id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		
		if(!defined(NAVNAME)) define('NAVNAME', $rt['catemes']['cat_name']);
		$this->set('rt',$rt);
		
		$this->set('cid',$c);
		unset($pages,$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_news');
	}
	
	function article($id=0){
		$this->action('common','checkjump');
		
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if(!($id > 0)){
			if(!($id>0)){ $this->jump(ADMIN_URL.'new.php'); exit;}
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE article_id='$id' AND is_show='1' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(empty($rt)){
			if(!($id>0)){ $this->jump(ADMIN_URL.'new.php'); exit;}
		}
		
		$this->title($rt['article_title']);
		if(!defined(NAVNAME)) define('NAVNAME', $rt['article_title']);
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v2_gonggaoinfo');
	}//end 
	
}

