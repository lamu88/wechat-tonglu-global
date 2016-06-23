<?php
 /*
 * 这是一个后台数据统计处理类
 */
class StatsController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		$this->css('content.css');
	}
	
	function statsindex(){
	  $this->App->test();
	  $arr = parse_url('http://www.baidu.com/ddd/ddd/ddd.php?dd=dfsa');
	  print_r($arr);
	}
	//销量排行
	function sale_rank(){
		$this->template('sale_rank');
	}
	
	//销量统计
	function sale_total(){
		$this->template('sale_total');
	}
	
	//订单走势
	function order_trend(){
		require_once(SYS_PATH_ADMIN.'inc/common.php');
		$this->js('time/WdatePicker.js');
		
		/* 时间参数 */
		if (isset($_POST['start_date']) && !empty($_POST['end_date']))
		{
			$start_date = strtotime($_POST['start_date']);
			$end_date = strtotime($_POST['end_date']);
			if ($start_date == $end_date)
			{
				$end_date   =   $start_date + 86400;
			}
		}
		else
		{
			$today      = mktime();   //本地时间
			$start_date = $today - 86400 * 30;
			$end_date   = $today + 86400;               //至明天零时
		}
		$rt['start_date'] = date('Y-m-d',$start_date);
		$rt['end_date'] = date('Y-m-d',$end_date);
		
		$ordername = array('0'=>"未确认",'1'=>'已取消','2'=>'已确认','3'=>'退货','4'=>'无效');
		$area_xml .= "<graph caption='订单统计' shownames='1' showvalues='1' decimalPrecision='2' outCnvBaseFontSize='13' baseFontSize='13' pieYScale='45'  pieBorderAlpha='40' pieFillAlpha='70' pieSliceDepth='15' pieRadius='100' bgAngle='460'>";

		$sql = "SELECT COUNT(order_id) AS order_count,order_status FROM `{$this->App->prefix()}goods_order_info` WHERE add_time BETWEEN  '$start_date' AND '$end_date' GROUP BY order_status ORDER BY order_status ASC";
		$this->App->fieldkey('order_status');
		$rl = $this->App->find($sql);
		if(!empty($rl)){
			for($i=0;$i<5;$i++){
				if(!isset($rl[$i]['order_count'])){ $rl[$i]['order_count'] = 0; $rl[$i]['order_status'] = $i;} 
				$area_xml .= "<set name='".$ordername[$i]."' value='".$rl[$i]['order_count']."' color='".chart_color($i)."' />";
			}
		}
		
        $area_xml .= '</graph>';
		
		$rt['order_data'] = $area_xml;
		
		$sql = "SELECT SUM(order_amount + shipping_fee) FROM `{$this->App->prefix()}goods_order_info` WHERE order_status='2' AND pay_status='1'";
		$rt['successprice'] =  $this->App->findvar($sql);
		$this->set('rt',$rt);
		$this->template('order_trend');
	}
	
	//【订单走势、销售走势】
	function sale_trend(){
		require_once(SYS_PATH_ADMIN.'inc/common.php');
		
		// 取得查询类型和查询时间段 
		if (empty($_POST['query_by_year']) && empty($_POST['query_by_month']))
		{
				// 默认当年的月走势 
				$query_type = 'month';
				$start_time = strtotime(date('Y').'-1');
				$end_time   = mktime();
			
		}
		else
		{
			if (isset($_POST['query_by_year']))
			{
				// 年走势 
				$query_type = 'year';
				$start_time = strtotime($_POST['year_beginYear'].'-01-01');
				$end_time   = strtotime($_POST['year_endYear'].'-'.date('m-d',mktime()));
			}
			else
			{
				// 月走势
				$query_type = 'month';
				$start_time = intval(strtotime($_POST['month_beginYear']."-".$_POST['month_beginMonth']));
				$end_time = intval(strtotime($_POST['month_endYear']."-".$_POST['month_endMonth']));
			}
		}

		// 分组统计订单数和销售额：已发货时间为准
		$format = ($query_type == 'year') ? '%Y' : '%Y-%m';
		$sql = "SELECT DATE_FORMAT(FROM_UNIXTIME(add_time), '$format') AS period, COUNT(*) AS order_count, SUM(goods_amount + shipping_fee) AS order_amount FROM `{$this->App->prefix()}goods_order_info` WHERE add_time BETWEEN '$start_time' AND '$end_time' AND order_status='2' AND pay_status='1' GROUP BY period";
		$data_list = $this->App->find($sql);

		// 赋值统计数据 
		$xml = "<chart caption='' xAxisName='%s' showValues='0' decimals='0' formatNumberScale='0'>%s</chart>";
		$set = "<set label='%s' value='%s' />";
		$i = 0;
		$data_count  = '';
		$data_amount = '';
		if(!empty($data_list)){
			foreach ($data_list as $data)
			{
				$data_count  .= sprintf($set, $data['period'], $data['order_count'], chart_color($i));
				$data_amount .= sprintf($set, $data['period'], $data['order_amount'], chart_color($i));
				$i++;
			}
		}
		
		$rt['order_data'] = sprintf($xml, '', $data_count); // 订单数统计数据
		$rt['sale_data'] = sprintf($xml, '', $data_amount);    // 销售额统计数据
		$this->set('rt',$rt);
		$this->template('sale_trend');
	}
	
	//利润走势
	function profit_trend(){
		$this->template('profit_trend');
	}
	
	//销售排行
/*	function sale_rank(){
		$this->template('sale_rank');
	}*/
	
	//访问购买率
	function visit_sale(){
		$this->template('visit_sale');
	}
}
?>