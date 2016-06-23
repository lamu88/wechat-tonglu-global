<?php
class TaggoodsController extends Controller{
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
	
	function index($data= array()){
		$this->action('common','checkjump');
		$page = isset($data['page']) ? $data['page'] : 1;
		$cid = isset($data['cid']) ? $data['cid'] : 0;
		if(!($cid > 0)){
			$this->jump(ADMIN_URL);exit;
		}
		
		$sql = "SELECT * FROM `{$this->App->prefix()}top_cate` WHERE tcid='$cid' LIMIT 1";
		$rt['cateinfo'] = $this->App->findrow($sql); 
		if(empty($rt['cateinfo'])){
			$this->jump(ADMIN_URL);exit;
		}	
		$rt['ad'] = array();
		$k = 0;
		if(!empty($rt['cateinfo']['cat_img2'])){
			$rt['ad'][$k]['img'] = SITE_URL.$rt['cateinfo']['cat_img2'];
			$rt['ad'][$k]['url'] = $rt['cateinfo']['cat_url'];
			$rt['ad'][$k]['name'] = $rt['cateinfo']['cat_name'];
			++$k;
		}
		if(!empty($rt['cateinfo']['cat_img'])){
			$rt['ad'][$k]['img'] = SITE_URL.$rt['cateinfo']['cat_img'];
			$rt['ad'][$k]['url'] = $rt['cateinfo']['cat_url'];
			$rt['ad'][$k]['name'] = $rt['cateinfo']['cat_name'];
		}
		//是否有子分类
		$sql = "SELECT tcid FROM `{$this->App->prefix()}top_cate` WHERE parent_id='$cid'";
		$tcids = $this->App->findcol($sql); 
		if(empty($tcids)){
			$tcids[0] = $cid;
		}
		
		//轮播js css
		$this->css(array("flexslider.css"));
		$this->js(array("jquery.flexslider-min.js","main.js"));

		//分页
		if(empty($page)){
			   $page = 1;
		}
		$list = 10;
		$start = ($page-1)*$list;
				
		//产品
		$sql = "SELECT COUNT(cg.gid) FROM `{$this->App->prefix()}goods` AS g LEFT JOIN `{$this->App->prefix()}top_cate_goods` AS cg ON g.goods_id = cg.goods_id WHERE cg.tcid='$cid'";	
		//$tt = $this->App->findvar($sql);
		//$rt['categoodspage'] = Import::basic()->getpage($tt,$list,$page,'?page=',true);
		$rt['cat'] = $this->App->find("SELECT * FROM `{$this->App->prefix()}top_cate` WHERE tcid IN (".implode(',',$tcids).")");
		
		foreach($tcids as $tcid){		
			$sql = "SELECT g.goods_name,g.goods_img,g.is_jifen,g.is_virtual,g.pifa_price,g.shop_price,cg.* FROM `{$this->App->prefix()}goods` AS g LEFT JOIN `{$this->App->prefix()}top_cate_goods` AS cg ON g.goods_id = cg.goods_id WHERE cg.tcid='$tcid' ORDER BY g.sort_order ASC,cg.goods_id DESC";	
			$rt['categoodslist'][$tcid] = $this->App->find($sql);
		}
		
		
		if(!defined(NAVNAME)) define('NAVNAME',$rt['cateinfo']['cat_name']);
		
		$this->set('rt',$rt);
		
		$this->title($rt['cateinfo']['cat_name']);
		
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/taggoods_index');
	}
	
}

