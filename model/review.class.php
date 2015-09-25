<?php
	//电影评论页面
	class Review extends PDOSeries{
		public $table = 'review';
		//此处由于模板文件需要显示来自数据库中两个不同数据表的内容，所以该方法需要一个默认值
		public function getInfos($id,$table = 'infos'){
			$sql = "select * from {$this -> getTable($table)} where i_id='{$id}' and i_is_delete=0";
			return $this -> s_arr($sql);
		}
		//该方法用来将评论信息入库
		public function addContent($data){
			$sql = "insert into {$this -> getTable()}() values(null,'{$data['title']}','{$data['content']}','{$data['score']}','{$data['user']}','{$data['time']}','{$data['id']}','{$data['allowed']}')";
			//执行
			//返回布尔值确定执行结果
			return (boolean)$this -> udi($sql);
		}
		/*
		 * 该方法用来获取用户当前所评价过的未被后台删除的电影,因此需要较为多的判断
		 * 通过评论表与电影信息表的连接查询并筛选评论表id=信息表id & 该信息尚未被删除  & 该评论为当前用户发表
		 * @param1 $user 当前需要获取信息的用户名
		 * @return array 返回结果为一个二维数组
		 * 
		 * */
		public function getUserReview($user){
			if(is_array($user)){
				$user = $user['a_user'];
			}
			//sql语句
			$sql = "select * from {$this -> getTable()} inner join m_infos where {$this -> getTable()}.i_id = m_infos.i_id and m_infos.i_is_delete = 0 and {$this -> getTable()}.r_user = '{$user}'";
			return $this -> s_arrs($sql);
			//var_dump($this -> s_arrs($sql));exit;
		}
		//删除评论的方法，需要一个评论的id作为参数
		public function delReview($id){
			$sql = "delete from {$this -> getTable()} where r_id = '{$id}'";
			//执行
			return (boolean)$this -> udi($sql);
		}
		/*
		 * 获取指定电影的平均分的方法
		 * @param1 string $id 当前电影对应的id
		 * @return int 返回求出的评分平均值
		 * */
		public function getAverage($id){
			$sql = "select r_score from {$this -> getTable()} where i_id='{$id}'";
			$sum = 0;
			//执行
			$arr_res = $this -> s_arrs($sql);
			foreach($arr_res as $v){
				$sum += $v['r_score'];
			}
			$length = count($arr_res);
			//防止分母为0报错
			if($length == 0){
				$length = 1;
			}
			$average = $sum / $length;
			//var_dump(number_format($average,1));exit;
			return $average = number_format($average,1);
		}
	}