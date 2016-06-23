<?php
class HbjiluController extends Controller{
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
	
	function index(){
		echo "<div style='margin-top:10%; margin-left:10%; width:30%; text-align:center'>
			<h1>
				用户红包核实
			</h1>
			<div>
				<form name='form' action='hbjilu.php?type=heshi' method='post'>
					用户ID：<input type='text' name='uid' value='' />
					<input type='submit' name='submit' value='查 询' />
				</form>
			</div>
		</div>
		";		
	}
	
	function heshi($data=array()){
		if(isset($data['uid']))	$uid=$data['uid'];
		if(isset($_POST['uid'])) $uid=$_POST['uid'];
		//用户信息
		$sql = "SELECT * from `{$this->App->prefix()}user` WHERE `user_id`=$uid";
		$user = $this->App->findrow($sql);
		//用户资金记录
		$sql = "SELECT * from `{$this->App->prefix()}user_money_change` WHERE `uid`=$uid OR `buyuid`=$uid ORDER BY `time` DESC";
		$money = $this->App->find($sql);
		//验证码记录
		$sql = "SELECT * from `{$this->App->prefix()}goods_sn` WHERE `uid`=$uid OR `pid`=$uid AND (`money`>0 OR `lmoney`>0) ORDER BY `order_id` DESC";
		$sn = $this->App->find($sql);
		
		$this->set("user",$user);
		$this->set("money",$money);
		$this->set("sn",$sn);
		$this->template('heshi');
	}
	
	function fix(){
		$sql = "SELECT * from gz_goods_sn WHERE is_use =1 AND `money`=`lmoney` and `money`>0 and cengji>0 ORDER BY order_id desc";
		$lists = $this->App->find($sql);
		
		foreach($lists as $key=>$list){
			$mymoney = $this->App->findvar("SELECT mymoney from gz_user WHERE user_id=".$list['pid']);
			if($mymoney==0){
				//echo $list['pid'].'-'.$mymoney.'<br />';
				$sql = "UPDATE gz_goods_sn SET money=0 WHERE `pid`=".$list['pid'];
				$this->App->query($sql);
			}elseif($mymoney==$list['money']){
				$sql="UPDATE gz_goods_sn SET lmoney=0 WHERE `pid`=".$list['pid'];
				$this->App->query($sql);
			}elseif($mymoney<$list['money']){
				$sql="UPDATE gz_goods_sn SET money=".$mymoney.",lmoney=lmoney-$mymoney WHERE `pid`=".$list['pid'];
				$this->App->query($sql);
			}
		}
			echo 'success';
		
	}
	
}

