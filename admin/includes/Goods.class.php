<?php
    //对商品信息进行操作的类
    class Goods extends PDOSeries{
        protected $table = 'infos';
        private $DB_object;
        public function __construct(){
            $this -> DB_object = PDOSeries :: getInstance();
        }
        public function getAllGoods($page = 1,$table = 'infos'){
            //从配置文件获取每页显示数据量
            $length = $GLOBALS['config']['admin_goods_pagecount'];
            //计算起始位置
            $offset = ($page - 1) * $length;
            //sql
            if($table != 'infos'){
            	//var_dump($offset,$length);exit;
            	$sql = "select * from {$this -> getTable($table)} order by i_id limit {$offset},{$length}";
            }else{
            	//echo '123';
            	$sql = "select * from {$this -> getTable($table)} where i_is_delete = 0 order by i_id limit {$offset},{$length}";
            }
            //$sql = "select * from {$this -> getTable($table)} where i_is_delete = 0 order by i_id limit {$offset},{$length}";
            //echo '<pre>';
            //var_dump($this -> DB_object -> s_arrs($sql));exit;
            return $this -> DB_object -> s_arrs($sql);
        }
        /*
        *获取所有的商品记录数
        *@return int 总记录数
        */
        public function getAllGoodsCounts($condition = 0,$table = 'infos'){
            if($condition == 0){
                $sql = "select count(*) as c from {$this -> getTable($table)} where i_is_delete = 0";
            }else{
                $sql = "select count(*) as c from {$this -> getTable($table)} where i_is_delete = 1";
            }
            //执行sql
            $arr_res = $this -> DB_object -> s_arr($sql);
            //返回记录数,使用别名作为下标
            return $arr_res['c'];
        }
        /*
        *更新商品状态
        *@param1 int $id 要更新的商品ID
        *@return bool 成功受影响的行数 失败false
        */
        public function updateGoodsById($id,$arg = 1){
            $sql = "update {$this -> getTable()} set i_is_delete = '{$arg}' where i_id = '{$id}'";
            return $this -> DB_object -> udi($sql);
        }
        /*
        *定义一个获取所有g_is_delete参数为1的数据
        *@return array 返回一个二维数组
        */
        public function getTrashes($page){
            //从配置文件获取每页显示数据量
            $length = $GLOBALS['config']['admin_goods_pagecount'];
            //计算起始位置
            $offset = ($page - 1) * $length;
            $sql = "select * from m_infos where i_is_delete = 1 order by i_id limit {$offset},{$length}";
            return $this -> DB_object -> s_arrs($sql);
        }
        /*
        *定义一个通过商品名称和c_id来判断是否有同类商品名重复的方法
        *@param1 int c_id 需要判断的商品名称
        *@param2 string g_name 需要判断的商品分类
        *@return 如果重复则返回true，反之false
        */
        public function checkGoodsByCategoryIdAndName($data){
            $sql = "select g_name from {$this -> getTable()} where c_id = '{$data['c_id']}' and g_name = '{$data['g_name']}'";
            //执行
            return (boolean)$this -> DB_object -> s_arr($sql);
        }
        /*
        *定义一个添加商品信息的方法
        *@param1 string $data['g_name']商品名称
        *@param2 string $data['g_sn'] 商品货号
        *@param3 int $data['c_id'] 商品所属分类id
        *@param4 string $data['g_image'] 商品图片所在路径
        *@param5 string $data['g_desc'] 商品描述
        *@param6 int $data['g_inv'] 商品库存
        *@param7 int $data['g_is_best'] 是否为精品
        *@param8 int $data['g_is_new'] 是否为新品
        *@param9 int $data['g_is_hot'] 是否为热销
        *@param10 int $data['g_is_on_sale']商品是否上架
        *@param11 int $data['g_sort'] 商品排序
        *@return 成功返回手影响行数，失败返回false或0
        */
        public function addGoods($data){
            //此处自动生成一个随机不重复货号
            
            if($data['i_image'] == ''){
                $data['i_image'] = '没有上传电影图片';
            }
            if($data['i_is_hot'] == ''){
                $data['i_is_hot'] = 0;
            }
            if($data['i_is_delete'] == ''){
                $data['i_is_delete'] = 0;
            }
            //var_dump($data['g_is_best']);exit;
            $fields = $values = '';
            foreach($data as $k => $v){
                $fields .= $k.',';
                $values .= '"'.$v.'",';
            }
            //去除末尾的逗号
            $fields = trim($fields,',');
            $values = trim($values,',');
            $sql = "insert into {$this -> getTable()}($fields) values({$values})";
            //执行
            return $this -> DB_object -> udi($sql);
        }
        /*
         * 定义一个更新电影视频路径的方法
         * @param1 $id 待更新电影的数据表中对应的字段名称
         * @return bool 成功true 失败false
         * */
        public function setVideo($id,$url){
        	$sql = "update {$this -> getTable()} set i_video = '{$url}' where i_id = '{$id}'";
        	//执行并返回执行结果
        	return $this -> DB_object -> udi($sql);
        } 
        /*
         * 此方法用于获取评论信息
         * @param1 int default 0 0表示选择r_is_allowed=0的评论，否则选择r_is_allowed=1的
         * @return array 成功返回二维数组 失败返回false
         * */
        public function getReviews($state = 0,$table = 'infos'){
        	$sql = "select * from {$this -> getTable($table)}";
        	//执行
        	return $this -> DB_object -> s_arrs($sql);
        }
        public function delReviews($id,$column = 'i_id',$table = 'infos'){
        	$sql = "delete from {$this -> getTable($table)} where {$column} = {$id}";
        	//执行
        	return $this -> DB_object -> udi($sql);
        }
        public function getMovieById($id,$column = 'i_id',$table = 'infos'){
        	$sql = "select i_name from m_infos where {$column} = {$id}";
        	//执行，此处返回一个数组
        	return $this -> DB_object -> s_arr($sql);
        }
        public function setAllow($id,$column = 'r_is_allowed',$table = 'review'){
        	$sql1 = "select $column from {$this -> getTable($table)} where r_id = '{$id}'";
        	$arr_res = $this -> DB_object -> s_arr($sql1);
        	if($arr_res[$column] == 1){
        		$sql2 = "update {$this -> getTable($table)} set {$column} = 0 where r_id = '{$id}'";
        	}else{
        		$sql2 = "update {$this -> getTable($table)} set {$column} = 1 where r_id = '{$id}'";
        	}
        	return $this -> DB_object -> udi($sql2);
        }
        public function getReviewCounts($table = 'infos'){
        	$sql = "select count(*) as c from {$this -> getTable($table)}";
        	$arr = $this -> DB_object -> s_arr($sql);
        	//var_dump($arr);exit;
        	return $arr['c'];
        }
        //用于通过mysql模糊匹配查找用户的方法
        //参数为用户名
        public function searchReview($user){
        	$sql = "select * from m_review where r_user like '%{$user}%' or r_user like '{$user}%'";
        	//执行
        	//var_dump($this -> DB_object -> s_arrs($sql));exit;
        	return $this -> DB_object -> s_arrs($sql);
        }
        public function getWholeReview($id){
        	$sql = "select * from m_review where r_id = '{$id}'";
        	return $this -> DB_object -> s_arr($sql);
        }
        public function updateReview($data,$id){
        	$sql = "update m_review set r_title='{$data['title']}',r_contents='{$data['content']}',r_score='{$data['score']}',r_user='{$data['user']}',r_date='{$data['date']}',i_id='{$data['id']}',r_is_allowed='{$data['allow']}' where r_id='{$id}'";
        	return $this -> DB_object -> udi($sql);
        }
    }

    