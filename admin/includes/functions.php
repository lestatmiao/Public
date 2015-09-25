<?php

	//后台所有的公共函数文件


	/*
	 * 自动加载
	 * @param1 string $class
	*/
	function __autoload($class){
		//到admin/includes加载类文件
		$file = INCDIR . "{$class}.class.php";
		//判断
		if(is_file($file)){
			//加载
			include_once $file;
		}
	}

	/*
	 * 跳转提示函数
	 * @param1 string $url,要跳转的目标文件
	 * @param2 string $msg,要提示的信息
	 * @param3 int $time = 3,默认等待时间
	*/
	function admin_redirect($info,$url,$time = 3){
		//跳转
		//header("Refresh:{$time};url={$url}");
		//提示
		//echo $msg;
		//终止脚本执行
		//exit;
		//加载跳转模板即可
		include_once TEMPDIR . 'redirect.html';
		//终止脚本执行
		exit;
	}