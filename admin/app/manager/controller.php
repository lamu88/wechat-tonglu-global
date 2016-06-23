<?php


class ManagerController extends Controller
{
	function __construct()
	{
		$this->css('content.css');
	}

	function managerlist()
	{
		$sql = "SELECT tb1.*, tb2.groupname FROM `{$this->App->prefix()}admin` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}admin_group` AS tb2 ";
		$sql .= " ON tb1.groupid = tb2.gid";
		$this->set('adminlist', $this->App->find($sql));
		$this->template('managerlist');
	}

	function manageredit($type = 'add', $id = 0)
	{
		$sql = "SELECT groupname,gid FROM `{$this->App->prefix()}admin_group` WHERE active ='1'";
		$groupar = $this->App->find($sql);
		$this->set('groupar', $groupar);
		unset($groupar);
		$rts = array();
		if ($type == 'edit') {
			if (empty($id)) {
				$id = $this->getuserinfo('adminid');
			}
			if (empty($id) || !(Import::basic()->int_preg($id))) {
				$this->jump('manager.php?type=list');
				exit;
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}admin` WHERE adminid='{$id}' LIMIT 1";
			$rts = $this->App->findrow($sql);
		}
		$this->set('type', $type);
		$this->set('rts', $rts);
		$this->template('manager_info');
	}

	function managergroup($tt = "", $id = 0)
	{
		if (empty($tt)) {
			$sql = "SELECT * FROM `{$this->App->prefix()}admin_group`";
			$this->set('grouplist', $this->App->find($sql));
			$this->template('managergrouplist');
		} else {
			$rts = array();
			if ($tt == 'edit') {
				if (empty($id) || !(Import::basic()->int_preg($id))) {
					$this->jump('manager.php?type=group');
					exit;
				}
				$sql = "SELECT *FROM `{$this->App->prefix()}admin_group` WHERE gid='$id' LIMIT 1";
				$rts = $this->App->findrow($sql);
				if (empty($rts)) {
					$this->jump('manager.php?type=group');
					exit;
				}
			}
			require_once(SYS_PATH_ADMIN . "inc/admingroup.php");
			$sql = "SELECT `option_group` FROM `{$this->App->prefix()}admin_group` WHERE gid='$id' LIMIT 1";
			$option_group = $this->App->findvar($sql);
			$option_group_arr = array();
			if (!empty($option_group)) {
				$option_group_arr = explode('+', $option_group);
			}
			$this->set('option_group_arr', $option_group_arr);
			$this->set('groupname_arr', $groupname_arr);
			$this->set('groupname_arr2_sub', $groupname_arr2_sub);
			$this->set('rts', $rts);
			$this->set('type', $tt);
			unset($option_group_arr, $groupname_arr, $rts);
			$this->template('managergroup_info');
		}
	}

	function managerlog($adminname = '')
	{
		$w = "";
		$orderby = "";
		if (isset($_GET['desc'])) {
			$orderby = ' ORDER BY `' . $_GET['desc'] . '` DESC';
		} else if (isset($_GET['asc'])) {
			$orderby = ' ORDER BY `' . $_GET['asc'] . '` ASC';
		} else {
			$orderby = ' ORDER BY `gid` DESC';
		}
		if (!empty($adminname)) {
			$w = "WHERE optioner='$adminname'";
		}
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		if (empty($page)) {
			$page = 1;
		}
		$list = 10;
		$start = ($page - 1) * $list;
		$sql = "SELECT COUNT(gid) FROM `{$this->App->prefix()}adminlog` {$w}";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
		$this->set("pagelink", $pagelink);
		$sql = "SELECT * FROM `{$this->App->prefix()}adminlog` {$w} {$orderby} LIMIT $start,$list";
		$rts = $this->App->find($sql);
		$this->set('rts', $rts);
		$this->set('page', $page);
		$this->template('managerlog');
	}

	function ajax_deladmin($id = 0)
	{
		if (empty($id) || !(Import::basic()->int_preg($id))) {
			echo "非法删除！删除ID为空或者不合法！";
			return false;
		}
		$sql = "SELECT groupid FROM `{$this->App->prefix()}admin` WHERE adminid = '{$id}' LIMIT 1";
		$groupid = $this->App->findvar($sql);
		if ($groupid == 1) {
			echo "你没有权限删除最高管理员！";
			exit;
		}
		if ($this->App->delete('admin', 'adminid', $id)) {
			$this->action('system', 'add_admin_log', '删除管理员：ID为' . $id);
		} else {
			echo "删除中发生意外错误！";
		}
	}

	function ajax_delgroup($id = 0)
	{
		if (empty($id) || !(Import::basic()->int_preg($id))) {
			echo "非法删除！删除ID为空或者非法！";
			return false;
		}
		if ($this->App->delete('admin_group', 'gid', $id)) {
			$this->action('system', 'add_admin_log', '删除权限组：ID为' . $id);
		} else {
			echo "删除中发生意外错误！";
		}
	}

	function ajax_addmanmger($data = array(), $aid = 0)
	{
		if (empty($data)) {
			echo "数据为空！";
			return false;
		}
		$uname = $data['adminname'];
		if (!(Import::basic()->username_preg($uname))) {
			echo "你指定的管理名字不合法！";
			return false;
		}
		$sql = "SELECT adminid FROM `{$this->App->prefix()}admin` WHERE adminname = '$uname' LIMIT 1";
		$adminid = $this->App->findvar($sql);
		if (!empty($aid)) {
			if (Import::basic()->int_preg($aid)) {
				if (empty($adminid) || $adminid == $aid) {
					$data_ = array_diff_assoc($data, array('addtime' => time()));
					$this->App->update('admin', $data_, 'adminid', $aid);
					$this->action('system', 'add_admin_log', '修改管理员：' . $data['adminname']);
					unset($data);
				} else {
					echo "重复管理员名称，无法操作！";
				}
			} else {
				echo "非法的ID！";
			}
			exit;
		}
		if (!empty($adminid)) {
			echo "重复管理员名称，无法操作！";
		} else {
			if ($this->App->insert('admin', $data)) {
				$this->action('system', 'add_admin_log', '添加管理员：' . $data['adminname']);
			} else {
				echo "操作中发生意外错误！";
			}
		}
	}

	function ajax_addgroup($data = array(), $gid = 0)
	{
		if (empty($data)) {
			echo "数据为空！";
			return false;
		}
		$gname = $data['groupname'];
		$sql = "SELECT groupname FROM `{$this->App->prefix()}admin_group` WHERE groupname = '$gname' LIMIT 1";
		$gname = $this->App->findvar($sql);
		if (!empty($gid) && (Import::basic()->int_preg($gid))) {
			if (empty($gname) || $gname == $data['groupname']) {
				$data_ = array_diff_assoc($data, array('addtime' => time()));
				if ($this->App->update('admin_group', $data_, 'gid', $gid)) {
					$this->action('system', 'add_admin_log', '修改权限组：' . ($gname ? $gname : '审核状态'));
				} else {
					echo "数据从未改变，无需修改！";
				}
				unset($data);
				exit;
			} else {
				echo "重复管理组名称，无法修改！";
				exit;
			}
		}
		if (!empty($gname)) {
			echo "重复管理组名称，无法添加！";
		} else {
			if ($this->App->insert('admin_group', $data)) {
				$this->action('system', 'add_admin_log', '添加权限组：' . $gname);
			} else {
				echo "入库时发生意外错误！";
			}
		}
	}

	function ajax_check_lib()
	{
		$thisurl = Import::basic()->thisurl();
		exit;
	}

	function ajax_dellog($ids)
	{
		if (empty($ids)) {
			echo "删除ID为空！";
			exit;
		}
		$groupid = $this->Session->read('groupid');
		if ($groupid != '1') {
			echo "你不是最高管理员，无法删除！";
			exit;
		}
		$arr = explode('+', $ids);
		foreach ($arr as $id) {
			$this->App->delete('adminlog', 'gid', $id);
		}
		$this->action('system', 'add_admin_log', '管理员日记删除：ID为' . implode(',', $arr));
	}

	function index($type = '')
	{
		$this->layout('login-default');
		$this->title('微信分销系统高级版');
		$this->css('login.css');
		$this->template('login');
	}

	function login($data = array())
	{
		if (!isset($data['adminname']) || empty($data['adminname']) || !isset($data['password']) || empty($data['password'])) die("用户名或密码不能为空！");
		$username = $data['adminname'];
		$pass = md5($data['password']);
		$vifcode = $data['vifcode'];
		if (strtolower($vifcode) != strtolower($this->Session->read('vifcode'))) {
			die("验证码错误！");
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}admin` WHERE `adminname`='{$username}' AND `password`='{$pass}' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if (!empty($rt)) {
			$this->App->update('admin', array('lasttime' => time()), 'adminid', $rt['adminid']);
			$this->Session->write('adminname', $rt['adminname']);
			$this->Session->write('adminid', $rt['adminid']);
			$this->Session->write('groupid', $rt['groupid']);
			$this->Session->write('lasttime', $rt['lasttime']);
			$this->Session->write('lastip', $rt['lastip']);
			$this->Session->write('email', $rt['email']);
			$groupid = $rt['groupid'];
			if ($groupid == '1') {
				$sql = "SELECT  option_group FROM `{$this->App->prefix()}admin_group` WHERE active ='1' AND gid='$groupid'";
				$option_group = $this->App->findvar($sql);
				if (!empty($option_group)) {
					$Permissions = @explode("+", $option_group);
					if (count($Permissions) < 10) {
						require_once(SYS_PATH_ADMIN . "inc/menulist.php");
						if (!empty($menu)) {
							$groupname_arr = array();
							foreach ($menu as $row) {
								$groupname_arr[] = $row['big_key'];
								foreach ($row['sub_mod'] as $rows) {
									$groupname_arr[] = $rows['en_name'];
								}
							}
							if (!empty($groupname_arr)) {
								$this->App->update('admin_group', array('option_group' => implode('+', $groupname_arr)), 'gid', '1');
							}
						}
					}
				}
			}
			$this->action('system', 'add_admin_log', '登录成功，登录管理员：' . $rt['adminname']);
		} else {
			die("用户名与密码不匹配，请重新输入！");
			$this->action('system', 'add_admin_log', '登录失败，登录管理员：' . $rt['adminname']);
		}
	}

	function admin_Permissions()
	{
		$groupid = $this->Session->read('groupid');
		$sql = "SELECT  option_group FROM `{$this->App->prefix()}admin_group` WHERE active ='1' and gid='$groupid'";
		return $this->App->findvar($sql);
	}

	function is_login()
	{
		if (!isset($_SESSION['adminid']) || empty($_SESSION['adminid']) || !isset($_SESSION['adminname']) || empty($_SESSION['adminname'])) {
			return false;
		} else {
			return true;
		}
	}

	function logout()
	{
		$this->action('system', 'add_admin_log', '退出登录-' . date('Y-m-d H:i:s', mktime()) . '-' . ($this->Session->read('adminname')));
		session_destroy();
	}

	function getuserinfo($type = "")
	{
		switch ($type) {
			case 'adminname':
				return isset($_SESSION['adminname']) ? $_SESSION['adminname'] : "";
				break;
			case "adminid":
				return isset($_SESSION['adminid']) ? $_SESSION['adminid'] : "0";
				break;
			case "groupid":
				return isset($_SESSION['groupid']) ? $_SESSION['groupid'] : "0";
				break;
			case "lasttime":
				return isset($_SESSION['lasttime']) ? $_SESSION['lasttime'] : "";
				break;
			case "lastip":
				return isset($_SESSION['lastip']) ? $_SESSION['lastip'] : "0.0.0.0";
			case "email":
				return isset($_SESSION['email']) ? $_SESSION['email'] : "";
				break;
			default:
				return array("adminid" => $_SESSION['adminid'], 'groupid' => $_SESSION['groupid'], 'lasttime' => $_SESSION['lasttime'], 'lastip' => $_SESSION['lastip'], 'adminname' => $_SESSION['adminname'], 'email' => $_SESSION['email']);
				break;
		}
	}

	function message_list($status = 0)
	{
		$w = "";
		if ($status == 1) {
			$w = " WHERE tb1.status='1'";
			$ws = " WHERE status='1'";
		} elseif ($status == 2) {
			$w = " WHERE tb1.status='2'";
			$ws = " WHERE status='1'";
		}
		$orderby = "";
		if (isset($_GET['desc'])) {
			$orderby = ' ORDER BY tb1.`' . $_GET['desc'] . '` DESC';
		} else if (isset($_GET['asc'])) {
			$orderby = ' ORDER BY tb1.`' . $_GET['asc'] . '` ASC';
		} else {
			$orderby = ' ORDER BY tb1.`mes_id` DESC';
		}
		$page = isset($_GET['page']) ? $_GET['page'] : '';
		if (empty($page)) {
			$page = 1;
		}
		$list = 10;
		$start = ($page - 1) * $list;
		$sql = "SELECT COUNT(mes_id) FROM `{$this->App->prefix()}message` {$ws}";
		$tt = $this->App->findvar($sql);
		$pagelink = Import::basic()->getpage($tt, $list, $page, '?page=', true);
		$this->set("pagelink", $pagelink);
		$sql = "SELECT tb1.*, tb2.user_name AS dbuser_name,tb2.nickname,tb3.goods_name,tb3.goods_id FROM `{$this->App->prefix()}message` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id";
		$sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS tb3 ON tb1.goods_id=tb3.goods_id";
		$sql .= " $w $orderby LIMIT $start,$list";
		$this->set('meslist', $this->App->find($sql));
		$this->template('mes_list');
	}

	function message_info($id = "0")
	{
		if ($id == 0) {
			$this->jump('manager.php?type=meslist');
			exit;
		}
		$sql = "SELECT tb1.*, tb2.user_name AS dbuser_name,tb2.nickname,tb3.goods_name,tb3.goods_id FROM `{$this->App->prefix()}message` AS tb1";
		$sql .= " LEFT JOIN `{$this->App->prefix()}user` AS tb2 ON tb1.user_id=tb2.user_id";
		$sql .= " LEFT JOIN `{$this->App->prefix()}goods` AS tb3 ON tb1.goods_id=tb3.goods_id";
		$sql .= " WHERE tb1.mes_id = '{$id}' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if (empty($rt)) {
			$this->jump('manager.php?type=meslist');
			exit;
		}
		$this->set('rt', $rt);
		$this->template('mes_info');
	}

	function ajax_delmes($ids)
	{
		if (empty($ids)) echo "删除ID为空！";
		$arr = explode('+', $ids);
		foreach ($arr as $id) {
			$this->App->delete('message', 'mes_id', $id);
		}
		$this->action('system', 'add_admin_log', '留言删除：ID为' . implode(',', $arr));
	}

	function ajax_savemes($data = array())
	{
		if (empty($data['mes_id'])) die("非法操作，无识别ID！");
		$sdata['admin_remark'] = $data['admin_remark'];
		$sdata['status'] = 2;
		$sdata['rp_content'] = $data['rp_content'];
		$sdata['rp_adminid'] = $this->getuserinfo('adminid');
		$this->App->update('message', $sdata, 'mes_id', $data['mes_id']);
		$this->action('system', 'add_admin_log', '留言审核：' . $data['title'] . '=>' . $data['mes_id']);
		unset($data, $sdata);
	}
}