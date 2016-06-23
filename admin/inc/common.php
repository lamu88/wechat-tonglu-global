<?php
if(!function_exists('chart_color')){
	/**
	 * 取得图表颜色
	 *
	 * @access  public
	 * @param   integer $n  颜色顺序
	 * @return  void
	 */
	function chart_color($n)
	{
		/* 随机显示颜色代码 */
		$arr = array('33FF66', 'FF6600', '3399FF', '009966', 'CC3399', 'FFCC33', '6699CC', 'CC3366', '33FF66', 'FF6600', '3399FF');
	
		if ($n > 8)
		{
			$n = $n % 8;
		}
	
		return $arr[$n];
	}
}
?>