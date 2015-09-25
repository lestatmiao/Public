<?php
	//网站信息模型
	class About extends PDOSeries{
		public $table = 'web';
		public function getOne(){
			$sql = "select * from {$this -> getTable()} where w_is_default = 1";
			//执行
			//接收返回的数组
			return $this -> s_arr($sql);
		}
	}