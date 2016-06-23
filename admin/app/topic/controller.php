<?php
class TopicController extends Controller{
 	function  __construct() {
           $this->css('content.css');
	}
	
    function index(){
		$id = (isset($_GET['id'])&&intval($_GET['id'])>0) ? intval($_GET['id']) : 0;
		if($id>0){  //删除
			$sql = "SELECT * FROM `{$this->App->prefix()}topic` WHERE topic_id='$id'";
			$rows = $this->App->findrow($sql);
			if(empty($rows)){
				$this->jump(ADMIN_URL.'topic.php?type=list'); exit;
			}
			$topic_img = SYS_PATH.$rows['topic_img'];
			$topic_flash  = SYS_PATH.$rows['topic_flash '];
			if(file_exists($topic_img) && is_file($topic_img)) Import::fileop()->delete_file($topic_img);
			if(file_exists($topic_flash) && is_file($topic_img)) Import::fileop()->delete_file($topic_flash);
			//删除数据库内容
			$this->App->delete('topic','topic_id',$id); 
			$this->jump(ADMIN_URL.'topic.php?type=list');
			exit;
		}
		 //分页
		$page= isset($_GET['page']) ? $_GET['page'] : '';
		if(empty($page)){
			   $page = 1;
		}
		$start = ($page-1)*$list;
		$list = 10;
		$sql = "SELECT COUNT(topic_id) FROM `{$this->App->prefix()}topic`";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page,'?page=',true);
		$this->set("pagelink",$pagelink);
			
		$sql = "SELECT * FROM `{$this->App->prefix()}topic` ORDER BY topic_id DESC LIMIT $start,$list";
		$rt = $this->App->find($sql);
		
		$this->set('rt',$rt);
	    $this->template('topiclist');
	}
	
	function topicinfo($id=0){
		    $this->js(array("edit/kindeditor.js",'time/WdatePicker.js','jquery.json-1.3.js','color/jscolor.js'));
			$json = Import::json();
			if($id>0){ //编辑
				if(!empty($_POST['topic_data'])){
					$_POST['topic_data'] = $json->decode($_POST['topic_data']); //
					$_POST['topic_data']       = serialize($_POST['topic_data']); //序列化
					$data['topic_name'] = $_POST['topic_name'];
					$data['meta_keys'] = $_POST['meta_keys'];
					$data['meta_desc'] = $_POST['meta_desc'];
					$data['topic_bgcolor'] = $_POST['topic_bgcolor'];
					if(!empty($_POST['topic_bgimg'])) $data['topic_bgimg'] = $_POST['topic_bgimg'];
					$data['start_time'] = isset($_POST['start_time'])&&!empty($_POST['start_time']) ? strtotime($_POST['start_time']) : '0';
					$data['end_time'] = isset($_POST['end_time'])&&!empty($_POST['end_time']) ? strtotime($_POST['end_time']) : '0';
					$data['topic_desc'] = $_POST['topic_desc'];
					$data['goods_ids'] = $_POST['topic_data'];
					$data['topic_type'] = $_POST['topic_type'];
					$data['topic_img'] = $_POST['topic_img'];
					$data['topic_flash'] = $_POST['topic_flash'];
					$data['topic_imgurl'] = $_POST['topic_imgurl'];  //图片或者flash外部URL
					$data['topic_imgcode'] = $_POST['topic_imgcode'];
					$data['top_url'] = $_POST['top_url'];  //顶部广告URL
					
					if($this->App->update('topic',$data,'topic_id',$id)){
						unset($data,$_POST);
						$this->jump('',0,'更新成功成功！'); exit;
					}else{
						unset($data,$_POST);
						$this->jump('',0,'更新失败！'); exit;
					}
				}
				$sql = "SELECT * FROM `{$this->App->prefix()}topic` WHERE topic_id='$id' LIMIT 1";
				$rt = $this->App->findrow($sql);
				$rt['goods_ids'] = addcslashes($rt['goods_ids'], "'");
				
				$rt['goods_ids'] = $json->encode(@unserialize($rt['goods_ids']));
				$rt['goods_ids'] = addcslashes($rt['goods_ids'], "'");
				
			}else{ //添加
				if(!empty($_POST['topic_data'])){
					$_POST['topic_data'] = $json->decode($_POST['topic_data']); //
					$_POST['topic_data']       = serialize($_POST['topic_data']); //序列化
					$data['topic_name'] = $_POST['topic_name'];
					$data['meta_keys'] = $_POST['meta_keys'];
					$data['meta_desc'] = $_POST['meta_desc'];
					$data['topic_bgcolor'] = $_POST['topic_bgcolor'];
					if(!empty($_POST['topic_bgimg'])) $data['topic_bgimg'] = $_POST['topic_bgimg'];
					$data['start_time'] = isset($_POST['start_time'])&&!empty($_POST['start_time']) ? strtotime($_POST['start_time']) : '0';
					$data['end_time'] = isset($_POST['end_time'])&&!empty($_POST['end_time']) ? strtotime($_POST['end_time']) : '0';
					$data['topic_desc'] = $_POST['topic_desc'];
					$data['goods_ids'] = $_POST['topic_data'];
					$data['topic_type'] = $_POST['topic_type'];
					$data['topic_img'] = $_POST['topic_img'];
					$data['topic_flash'] = $_POST['topic_flash'];
					$data['topic_imgurl'] = $_POST['topic_imgurl'];  //图片或者flash外部URL
					$data['topic_imgcode'] = $_POST['topic_imgcode'];
					$data['top_url'] = $_POST['top_url'];  //顶部广告URL
					
					if($this->App->insert('topic',$data)){
						unset($data,$_POST);
						$this->jump('',0,'添加成功！'); exit;
					}else{
						unset($data,$_POST);
						$this->jump('',0,'添加失败！'); exit;
					}
				}
				
			}
			/*
			    require(ROOT_PATH . 'includes/cls_json.php');

				$json          = new JSON;
				$topic['data'] = addcslashes($topic['data'], "'");
				$topic['data'] = $json->encode(@unserialize($topic['data']));
				$topic['data'] = addcslashes($topic['data'], "'");
			*/
			
			$this->set('rt',$rt);
			//分类列表
			$this->set('catelist',$this->action('common','get_goods_cate_tree'));
			//品牌列表
			$this->set('brandlist',$this->action('common','get_brand_cate_tree'));
			$this->template('topicinfo');
	}
	
	
	function ajax_searchGoods($data=""){
		$err = 0;
		$result = array('error' => $err, 'message' => '');
		$json = Import::json();
	
		if (empty($data))
		{
			$result['error'] = 2;
			$result['message'] = '传送的数据为空！';
			die($json->encode($result));
		}
		$art = $json->decode($data); //反json
		$cat_id = $art->cat_id;
		$brand_id = $art->brand_id;
		$keyword = $art->keyword; 
		$comd = array();
		if(intval($cat_id)>0){
			$cids = $this->action('common','get_goods_sub_cat_ids',$cat_id);
            $comd[] = 'cat_id IN('.implode(",",$cids).')';
		}
		if(intval($brand_id)>0){
			$comd[] = "brand='$brand_id'";
		}
		if(!empty($keyword)){
			$comd[] = "goods_name LIKE '%$keyword%'";
		}
		if(empty($comd)){
			$result['error'] = 2;
			$result['message'] = '';
			die($json->encode($result));
		}
		
		$sql = "SELECT goods_id,goods_name FROM `{$this->App->prefix()}goods` WHERE ".implode(' AND ',$comd)." ORDER BY goods_id DESC LIMIT 20";
		$rt = $this->App->find($sql);
		
		$result['error'] = 1;
		$result['message'] = $rt;
		die($json->encode($result));
	
	}
}
?>