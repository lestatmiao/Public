<?php
	//电影详情页模型
	class Details extends PDOSeries{
		public $table = 'infos';
		/*
		 * 该方法的存在意义主要用于两种情况:1,处理用户的不合理请求；2,用于在当数据库中的一条记录被删除的时候避免用户的详情页面出现错误，可以跳转至404页面
		 * @param1 string $column 该参数用于获取当前所请求查询的字段名
		 * @param2 string $table 改参数用于获取当前所请求查询的表名
		 * @return array/false 成功返回二维数组，失败返回false
		 * */
		//定义一个单独获取所有id的方法，用于对用户可能发生的错误的id请求进行判断
		public function getId($column,$table = 'infos'){
			$sql = "select {$column} from {$this -> getTable($table)}";
			//返回执行结果
			return $this -> s_arrs($sql);
		}
		//通过id获取对应的影片信息
		public function getInfos($id,$table = 'infos'){
			$sql = "select * from {$this -> getTable($table)} where i_id='{$id}' and i_is_delete=0";
			return $this -> s_arr($sql);
		}
		//通过制定id获取该id对应的所有评论
		public function getRows($id,$table = 'infos'){
			$sql = "select * from {$this -> getTable($table)} where i_id='{$id}' and r_is_allowed = 1 order by r_date asc";
			//var_dump($this -> s_arrs($sql));exit;
			return $this -> s_arrs($sql);
		}
		/*
		 * 设置电影评分方法
		 * @param1 string 需要被设置的电影id
		 * @param2 int 分数
		 * @return boolean 更新则返回受影响行数，否则返回0
		 * */
		public function setScore($id,$score){
			$sql = "update {$this -> getTable()} set i_score = {$score} where i_id = '{$id}'";
			//执行
			//var_dump((boolean)$this -> udi($sql));exit;
			return (boolean)$this -> udi($sql);
		}
	}