<?php
	//注册信息验证模型
	class Register extends PDOSeries{
		protected $table = 'user';
		//判断一个字段是否已经存在
		public function checkRepeat($value,$name){
			//var_dump($value,$name,$this -> getTable());exit;
			$sql = "select * from {$this -> getTable()} where $name = '{$value}'";
			//执行
			//var_dump($this -> s_arr($sql));
			if($this -> s_arr($sql)){
				//如果重复，则返回true
				return true;
			}else{
				//如果不重复，则返回false
				return false;
			}
		}
		//新用户信息入库方法,需要一个数组作为参数
		public function addUser($arr){
			$sql = "insert into {$this -> getTable()} values(null,'{$arr['user']}','{$arr['pwd']}','{$arr['email']}','{$arr['tele']}',null,null,null,null)";
			if($this -> udi($sql)){
				//插入数据成功
				return true;
			}else{
				//插入数据失败
				return false;
			}
		}
	}