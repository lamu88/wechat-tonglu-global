<?php
class SiteController extends Controller{
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
	function index(){
		$this->layout('site');
		$this->title($GLOBALS['LANG']['site_title']);
		$this->meta("title",$GLOBALS['LANG']['metatitle']);
		$this->meta("keywords",$GLOBALS['LANG']['metakeyword']);
		$this->meta("description",$GLOBALS['LANG']['metadesc']);
		
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			$rt = $this->action('banner','banner','微官网轮播',5);
			$this->Cache->write($rt);
		} 
		
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/site_index');
	}
	
	function about(){
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE keyword='路易劳莎' LIMIT 1";
			$rt['art'] = $this->App->findrow($sql);
			
			$this->Cache->write($rt);
		} 
		
		$this->set('rt',$rt);
		$title = $rt['art']['article_title'];
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$this->meta("keywords",$rt['art']['meta_keys']);
		$this->meta("description",$rt['art']['meta_desc']);
		
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/site_about');
	}
	
	function contact(){
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE keyword='联系我们' LIMIT 1";
			$rt['art'] = $this->App->findrow($sql);
			
			$this->Cache->write($rt);
		} 
		
		$this->set('rt',$rt);
		$title = $rt['art']['article_title'];
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$this->meta("keywords",$rt['art']['meta_keys']);
		$this->meta("description",$rt['art']['meta_desc']);
		
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/site_contact');
	}
	
	
	function news(){
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE cat_id='3' ORDER BY article_id DESC";
			$rt['art'] = $this->App->find($sql);
			
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_cate` WHERE cat_id='3' LIMIT 1";
			$rt['cat'] = $this->App->findrow($sql);
			$this->Cache->write($rt);
		} 
		
		$this->set('rt',$rt);
		$title = $rt['cat']['cat_name'];
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$this->meta("keywords",$rt['cat']['meta_keys']);
		$this->meta("description",$rt['cat']['meta_desc']);
		
		$this->set('bgimg',ADMIN_URL.'tpl/2/images/newbg.jpg');
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/site_new');
	}
	
	function newinfo(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if(!($id>0)){
			$this->jump(ADMIN_URL.'site.php?act=news');exit;
		}
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE article_id='$id' LIMIT 1";
			$rt['art'] = $this->App->findrow($sql);
			$this->Cache->write($rt);
		} 
		
		$this->set('rt',$rt);
		$title = $rt['art']['article_title'];
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$this->meta("keywords",$rt['art']['meta_keys']);
		$this->meta("description",$rt['art']['meta_desc']);
		
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/site_newinfo');
	}
	
	function shishang(){
		$rt = $this->Cache->read(3600);
	 	if(is_null($rt)) {
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_article` WHERE cat_id='4' ORDER BY article_id DESC";
			$rt['art'] = $this->App->find($sql);
			
			$sql = "SELECT * FROM `{$this->App->prefix()}wx_cate` WHERE cat_id='4' LIMIT 1";
			$rt['cat'] = $this->App->findrow($sql);
			$this->Cache->write($rt);
		} 
		
		$this->set('rt',$rt);
		$title = $rt['cat']['cat_name'];
		$this->title($title.' - '.$GLOBALS['LANG']['site_name']);
		$this->meta("title",$title);
		$this->meta("keywords",$rt['cat']['meta_keys']);
		$this->meta("description",$rt['cat']['meta_desc']);
		$this->set('bgimg',ADMIN_URL.'tpl/2/images/newbg.jpg');
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/site_new');
	}
	
	//授权查询
	function shouquan(){
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/site_shouquan');
	}
	
	//防伪查询
	function fangwei(){
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/site_fangwei');
	}
	
	function ajax_check_shouquan($data=array()){
		$types = $data['types'];
		$keys = $data['keys'];
		if(empty($keys)) die("请输入关键字搜索");
		$keys = trim($keys);
		if($types=='1'){
			$sql = "SELECT article_id,article_img FROM `{$this->App->prefix()}article` WHERE weixin='$keys' LIMIT 1";
			$rl = $this->App->findrow($sql);
			$aid = isset($rl['article_id']) ? $rl['article_id'] : 0;
			if($aid > 0){
				$img = isset($rl['article_img']) ? $rl['article_img'] : '';
				$ss = "恭喜您，您查询的微信号【".$keys."】是".$GLOBALS['LANG']['site_name']."授权经销商，请放心购买！";
				if(!empty($img)) $ss .='<br/><img src="'.SITE_URL.$img.'" style="max-width:100%" />';
				die($ss);
			}else{
				die("对不起，您查询的经销商微信号【".$keys."】未授权，请谨慎，防止上当受骗！");
			}
		}elseif($types=='2'){
			$sql = "SELECT article_id,article_img FROM `{$this->App->prefix()}article` WHERE wangwang='$keys' LIMIT 1";
			$rl = $this->App->findrow($sql);
			$aid = isset($rl['article_id']) ? $rl['article_id'] : 0;
			if($aid > 0){
				$img = isset($rl['article_img']) ? $rl['article_img'] : '';
				$ss = "恭喜您，您查询的旺旺号【".$keys."】是".$GLOBALS['LANG']['site_name']."授权经销商，请放心购买！";
				if(!empty($img)) $ss .='<br/><img src="'.SITE_URL.$img.'" style="max-width:100%" />';
				die($ss);
			}else{
				die("对不起，您查询的经销商旺旺号【".$keys."】未授权，请谨慎，防止上当受骗！");
			}
		}else{
			$sql = "SELECT article_id,article_img FROM `{$this->App->prefix()}article` WHERE author='$keys' LIMIT 1";
			$rl = $this->App->findrow($sql);
			$aid = isset($rl['article_id']) ? $rl['article_id'] : 0;
			if($aid > 0){
				$img = isset($rl['article_img']) ? $rl['article_img'] : '';
				$ss = "恭喜您，您查询的手机号【".$keys."】是".$GLOBALS['LANG']['site_name']."授权经销商，请放心购买！";
				if(!empty($img)) $ss .='<br/><img src="'.SITE_URL.$img.'" style="max-width:100%" />';
				die($ss);
			}else{
				die("对不起，您查询的经销商手机号【".$keys."】未授权，请谨慎，防止上当受骗！");
			}
		}
		exit;
	}
}

