<?php
class EmailController extends Controller{
 	function  __construct() {
           $this->css('content.css');
	}
	
   //邮箱服务器配置
	function email_config(){
		$fn = SYS_PATH.'data/email_config.php';
		if(!empty($_POST)){
			if(empty($_POST['smtp_host'])){
			 $this->jump('',0,"邮件服务器地址不能为空！"); exit;
			}
			
			if(empty($_POST['smtp_port'])){
			 $this->jump('',0,"服务器端口不能为空！"); exit;
			}
			
			if(empty($_POST['smtp_user'])){
			 $this->jump('',0,"邮件发送帐号不能为空！"); exit;
			}
			
			if(empty($_POST['smtp_pass'])){
			 $this->jump('',0,"帐号密码不能为空！"); exit;
			}
			
			if(empty($_POST['smtp_mail'])){
			 $this->jump('',0,"邮件发送地址不能为空！"); exit;
			}
			$str = "<?php\n";
			$str .= '$'."GLOBALS['LANG']['smtp_mail'] = '".trim($_POST['smtp_mail'])."';\n";
			$str .= '$'."GLOBALS['LANG']['smtp_host'] = '".trim($_POST['smtp_host'])."';\n";
			$str .= '$'."GLOBALS['LANG']['smtp_port'] = '".trim($_POST['smtp_port'])."';\n";
			$str .= '$'."GLOBALS['LANG']['smtp_user'] = '".trim($_POST['smtp_user'])."';\n";
			$str .= '$'."GLOBALS['LANG']['smtp_pass'] = '".trim($_POST['smtp_pass'])."';\n";
			$str .= '$'."GLOBALS['LANG']['mail_service'] = '".$_POST['mail_service']."';\n";
			$str .= '$'."GLOBALS['LANG']['smtp_ssl'] = '".$_POST['smtp_ssl']."';\n";
			$str .= "?>\n";
			if(@file_put_contents($fn,$str)){
					$this->action('system','add_admin_log','邮箱服务器设置！');
					$this->action('common','showdiv',$this->getthisurl());
			}else{ die("写入文件失败！是否是权限问题呢？！");}
		}
		
		$this->template('email_config');
	}
	
	//发送模板
	function set_send_tpl(){
		$this->template('set_send_tpl');
	}
	//发送开启
	function set_send_open(){
		if(!empty($_POST)){
			$this->App->update('systemconfig',array('email_open_config'=>serialize($_POST)),'type','basic'); //序列化后保存 
			echo '<script> alert("保存成功！"); </script>';
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(!empty($rt['email_open_config'])){
			$this->set('rt',unserialize($rt['email_open_config']));
		}
		$this->save_basic_config(); //写入文件
		$this->template('set_send_open');
		
	}
	
	function save_basic_config(){
		$sql = "SELECT * FROM `{$this->App->prefix()}systemconfig` LIMIT 1";
		$basic_config = $this->App->findrow($sql);
		$cache = Import::ajincache();
		$fn = SYS_PATH.'data/basic_config.php';
		$cache->write($fn, $basic_config,'basic_config');
	}
	
	//发送测试
	function send_test($datas=array()){
		$basic=Import::basic(); 
		$data['name'] = '收件人姓名';
		$data['email'] = isset($datas['useremail']) ? trim($datas['useremail']): "";
		$data['subject'] = '测试标题';
		$data['content'] = '你好，这是一封测试邮件，看到此内容时，表示测试成功！';
		$data['type'] = 1;
		$data['notification'] = false;
		

		if($basic->ecshop_sendemail($data)){
			echo "已测试成功！请稍后查看邮件！";
		}else{
			$rt = Import::error()->get_all();
			print_r($rt);
			echo "发送失败！";
		}
		exit;
	}
	
	
	//订单确认发送EMAIL
	function send_confirm_order($rt=array()){
   				$datanew['name'] = '亲爱的'.$rt['user_name'];
				$datanew['email'] = $rt['email'];
				$datanew['subject'] = '订单确认信息！';
				$this->set('rt',$rt);
				$datanew['content'] = $this->fetch('email_tpl/confirm_order',true);
				$datanew['type'] = 1;
				$datanew['notification'] = false;
				Import::basic()->ecshop_sendemail($datanew);
				unset($datanew);
   }
   
   	//订单取消发送EMAIL
	function send_cancel_order($rt=array()){
   				$datanew['name'] = '亲爱的'.$rt['user_name'];
				$datanew['email'] = $rt['email'];
				$datanew['subject'] = '订单已被取消信息！';
				$this->set('rt',$rt);
				$datanew['content'] = $this->fetch('email_tpl/cancel_order',true);
				$datanew['type'] = 1;
				$datanew['notification'] = false;
				Import::basic()->ecshop_sendemail($datanew);
				unset($datanew);
   }
   
    //订单失效发送EMAIL
	function send_invalid_order($rt=array()){
   				$datanew['name'] = '亲爱的'.$rt['user_name'];
				$datanew['email'] = $rt['email'];
				$datanew['subject'] = '订单已被设为失效状态！不允许再购买！';
				$this->set('rt',$rt);
				$datanew['content'] = $this->fetch('email_tpl/invalid_order',true);
				$datanew['type'] = 1;
				$datanew['notification'] = false;
				Import::basic()->ecshop_sendemail($datanew);
				unset($datanew);
   }
}
?>