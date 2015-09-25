<?php
	//登陆验证模型
	class Privilege extends PDOSeries{
		protected $table = 'user';
		/*
		 * 验证用户名密码是否匹配
		 * @param1 $user 用户名
		 * @param2 $pwd	密码
		 * return bool 匹配返回true，反之false
		 * */
		public function checkUserPwd($user,$pwd){
			$sql = "select * from {$this -> getTable()} where u_user='{$user}' and u_pwd = '{$pwd}'";
			//执行
			//返回布尔转换后的结果
			return (boolean)$this -> s_arr($sql);
		}
		public function setIpTime($id){
			$time = time();
			$ip = $_SERVER['REMOTE_ADDR'];
			$sql = "update {$this -> getTable()} set u_last_log_time='{$time}',u_last_log_ip='{$ip}' where u_user='{$id}'";
			return (boolean)$this -> udi($sql);
		}
	}