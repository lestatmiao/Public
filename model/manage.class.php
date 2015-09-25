<?php
	//个人中心模型
	class Manage extends PDOSeries{
		public $table = 'user';
		public function getUserInfo($id){
			//获取当前登陆用户的信息
			if(is_array($id)){
				$id = $id['a_user'];
			}
			$sql = "select * from {$this -> getTable()} where u_user='{$id}'";
			//执行
			return $this -> s_arr($sql);
		}
		/*
		 * 该方法用来保存用户上传的图像文件的路径，使用update来保存，因为只能有一个头像，因此直接替换
		 * @param1 string $file_name 原图完整路径
		 * @param2 string $thumb_name 缩略图完整路径
		 * @param3 string $id 用户名
		 * @return boolean 操作结果 成功返回true，反之false
		 * */
		public function updateInfo($file_name,$thumb_name,$id){
			$sql = "update {$this -> getTable()} set u_image='{$file_name}',u_thumb='{$thumb_name}' where u_user='{$id}'";
			//执行并返回结果
			return (boolean)$this -> udi($sql);
		}
		/*
		 * 获取所有用户名
		 * @return array 成功返回一个数组，失败返回false
		 * */
		public function getAllUsers(){
			$sql = "select u_user from {$this -> getTable()}";
			return $this -> s_arrs($sql);
		}
	}