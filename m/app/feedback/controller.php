<?php
class FeedbackController extends Controller{
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
		$this->js(array('jquery.json-1.3.js'));//将js文件放到页面头
	}
    
	
	function ajax_feedback($data=array()){
		$err = 0;
		$result = array('error' => $err, 'message' => '');
		$json = Import::json();

		if (empty($data))
		{
				$result['error'] = 2;
				$result['message'] = '传送的数据为空！';
				die($json->encode($result));
		}
		$mesobj = $json->decode($data); //反json ,返回值为对象
		
		$uid = $this->Session->read('User.uid');
		if(!($uid > 0)){
			$result['error'] = 2;
			$result['message'] = '先登陆后留言！';
			die($json->encode($result));
		}
		
		$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid' LIMIT 1";
		$rank = $this->App->findvar($sql);
		if($rank=='1'){
			$result['error'] = 2;
			$result['message'] = '请先购买一件产品后再发表！';
			die($json->encode($result));
		}
		
		//以下字段对应评论的表单页面 一定要一致
		$datas['comment_title'] = '合伙人留言';
		$datas['content'] = $mesobj->content;
		$datas['pics'] = $mesobj->pics;
		$datas['user_id'] = !empty($uid) ? $uid : 0;
		$datas['status'] = 2;
		
		if (strlen($datas['content'])<12)
		{
				$result['error'] = 2;
				$result['message'] = '留言内容不能太少！';
				die($json->encode($result));
		}
		
		//检查需要超过24小时候才能再次提问
		//if(!empty($goods_id)){
			$t = mktime()+24*3600;
			$sql = "SELECT addtime FROM `{$this->App->prefix()}message` WHERE user_id='$uid' ORDER BY addtime DESC LIMIT 1";
			$dt = $this->App->findvar($sql);
			if(!empty($dt)){
				if(($dt+3600*24)>mktime()){
					$result['error'] = 1;
					$result['message'] = '今天您已经发表过了，请您<font color=red>'.intval((($dt+3600*24)-mktime())/3600).'</font>小时之后再次发表吧！';
					die($json->encode($result));
				}
			}
		//}
		/*$datas['content'] = $mesobj->content;goods_id
		$datas['user_name'] = $mesobj->user_name;
		$datas['sex'] = $mesobj->sex;
		$datas['mobile'] = $mesobj->mobile;
		$datas['telephone'] = $mesobj->telephone;
		$datas['email'] = $mesobj->email;
		$datas['companyname'] = $mesobj->companyname;
		$datas['address'] = $mesobj->address;
		$datas['companyurl'] = $mesobj->companyurl;
		*/
		$datas['addtime'] = mktime();
		$ip = Import::basic()->getip();
		$datas['ip_address'] = $ip ? $ip : '0.0.0.0';
		$datas['ip_from'] = Import::ip()->ipCity($ip);

		if($this->App->insert('message',$datas)){
			$rl = $this->action('user','add_user_jifen','comment'); //留言返积分
			$result['error'] = 0;
			$result['message2'] ='发表成功，我们会很快回答您的问题！<br />恭喜您，本次提问所得积分：'.$rl['points'].'分！';
		}else{
			$result['error'] = 1;
			$result['message']='发表失败，系统正在维护中！';
		}
		unset($datas,$data);
		
		//查询评论
		if(!$page) $page = 1;
		$list = 2;
        $start = ($page-1)*$list;
		$tt = $this->__get_message_count($goods_id);
		$rt['message_count'] =$tt;
		$rt['messagelist'] = $this->__get_message($goods_id,$start,$list);
		$rt['messagepage'] = Import::basic()->ajax_page($tt,$list,$page,'get_message_page',array($goods_id));
		$rt['goodsinfo']['goods_id'] = $goods_id;
		$this->set('rt',$rt);
		$result['message'] = $this->fetch('ajax_message',true);
		
		die($json->encode($result));
	}
	
	function ajax_getmessagelist($data=array()){
		if(empty($data['goods_id'])||!(intval($data['goods_id'])>0)) die("获取数据失败，传送的商品id为空！");
		if(empty($data['page'])||!(intval($data['page'])>0)) $page=1;
		//查询评论
		$list = 2;
		$page =intval($data['page']);
		$goods_id =intval($data['goods_id']);
        $start = ($page-1)*$list;
		$tt = $this->__get_message_count($goods_id);
		$rt['message_count'] =$tt;
		$rt['messagelist'] = $this->__get_message($goods_id,$start,$list);
		$rt['messagepage'] = Import::basic()->ajax_page($tt,$list,$page,'get_message_page',array($goods_id));
		$rt['goodsinfo']['goods_id'] = $goods_id;
		$this->set('rt',$rt);
		echo  $this->fetch('ajax_message',true);
		exit;
	}
	
	function __get_message_count($gid=0){
		$g = "";
        if($gid>0) $g = "AND goods_id = '$gid'";
		$sql = "SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message`";
        $sql .=" WHERE status='2' $g";
		return $this->App->findvar($sql);
	}

	
	function __get_message($gid=0,$start=0,$list=8){
		 $g = "";
		 if($gid>0) $g = "WHERE AND goods_id = '$gid'";
		 $sql = "SELECT m.*,u.avatar,u.nickname FROM `{$this->App->prefix()}message` AS m LEFT JOIN `{$this->App->prefix()}user` AS u ON m.user_id=u.user_id";
		 $sql .=" $g ORDER BY m.addtime DESC LIMIT $start,$list";
		 return $this->App->find($sql);
	}
	
}

