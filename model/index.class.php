<?php
	//首页模型文件
	class Index extends PDOSeries{
		public $table = 'infos';
		/*
		 * 该方法用来查询所有电影图片名称
		 * @return array 返回一个二维数组 或 false
		 * */
		public function getImage($sort = ''){
			//var_dump($sort);exit;
			if($sort == ''){
				$sql = "select i_date,i_nation,i_score,i_image,i_name,i_id from {$this -> getTable()} where i_is_delete = 0";
			}else{
				$sql = "select i_date,i_nation,i_score,i_image,i_name,i_id from {$this -> getTable()} where i_is_delete = 0 order by {$sort} desc";
			}
			//var_dump($this -> s_arrs($sql));exit;
			//将结果(一个二维数组返回调用处)
			return $this -> s_arrs($sql);
		}
		public function getNews($table = 'infos'){
			$sql = "select * from {$this -> getTable($table)}";
			//var_dump($this -> s_arrs($sql));exit;
			//将结果(一个二维数组返回调用处)
			return $this -> s_arrs($sql);
		}
	}