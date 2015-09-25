<?php
    //商品分类表专门操作类
    class category extends PDOSeries{
        //属性
        protected $table = 'm_infos';
        protected $DB_object;
        //获取所有的商品分类
        public function __construct(){
            $this -> DB_object = PDOSeries :: getInstance();
        }
        public function getAllCategories($table = 'm_news'){
            //sql
            //PDOSeries::getInstance();
            $sql = "select * from {$table}";
            $categories = $this -> DB_object -> s_arrs($sql);
            //此处调用无限极分类方法
            return $categories;
        }
        /*
        *无限级分类
        *@param1 array $categories需要进行无限极分类的二维数组
        *@param2 int $parent_id需要查询的父分类的ID
        *@param3 int $level递归层数/缩进次数
        *@return array已经进行无限极分类的二维数组
        */
        /* private function noLimitCategories($categories,$parent_id = 0,$level = 0){
            //定义数组存储结果
            static $lists = array();
            //查出所有的顶级分类
            foreach($categories as $category){
                //顶级分类:c_parent_id=0
                //此时判断当前字段的父类id是否等于0，如果是0，则需要继续向下递归
                if($category['c_parent_id'] == $parent_id){
                    //此参数决定当前缩进次数，也就是递归层数
                    $category['level'] = $level;
                    //是顶级分类
                    $lists[] = $category;
                    //继续调用自身
                    $this -> noLimitCategories($categories,$category['c_id'],$level + 1);
                }
            }
            return $lists;
        } */
        public function checkCategory($c_parent_id,$c_name){
            //sql
            $c_name = addslashes($c_name);
            $sql = "select c_id from m_news where c_parent_id = '{$c_parent_id}' and c_name = '{$c_name}'";
            //执行
            //此处使用强制转换
            return (boolean)$this -> DB_object -> s_arr($sql);
        }
        /*
        *新增商品分类
        *@param1 int $c_parent
        *@param2 string $c_name
        *@param3 int $c_sort
        *$return 成功返回自增长
        */
        public function insertCategory($n_title,$n_contents,$table = 'm_news',$prefix = 'n_'){
        	$date = time();
        	//var_dump($_SESSION);exit;
        	//var_dump($_SESSION['user']);exit;
        	@$user = $_SESSION['id']['a_user'];
        	//var_dump($_SESSION['user']);exit;
            $sql ="insert into {$table} values(null,'{$n_title}','{$n_contents}','{$user}','{$date}',0)";
            //exit;
            return $this -> DB_object -> udi($sql);
        }
        /*
        *判断当前类是否为末类
        *@param1 int $id 当前类的id
        *@return bool 如果是末类，则返回true
        */
        public function isLeaf($id){
            //如果其他类的c_parent_id=当前id,则说明当前类有子类
            $sql = "select c_id from m_news where c_parent_id = '{$id}'";
            //执行查询操作
            //同时返回结果并进行布尔转换再取反
            return !(boolean)$this -> DB_object -> s_arr($sql);
        }
        /*
        *检测当前id所在的子类是否有数据存在
        *@param1 int $id
        *@return bool 
        */
        public function haveData($id){
            $sql ="select * from m_news where c_id = '{$id}' and c_number>0";
            //执行操作
            return (boolean)$this -> DB_object -> s_arr($sql);
        }
        /*
        *对当前类或自行删除操作
        *@param1 int $id 当前类的id
        *@return bool 返回执行操作的影响行数
        */
        public function delData($id){
            $sql = "delete from m_news where n_id = '{$id}'";
            //执行语句
            return (boolean)$this -> DB_object -> udi($sql);
        }
        /*
        * 获取一个商品分类的方法
        *@param1 int $id
        *@return mixed,成功返回数组，失败返回false
        */
        public function getCategoryById($id){
            $sql = "select * from {$this -> getTable()} where w_id = '{$id}'";
            return $this -> DB_object -> s_arr($sql);
        }
        //验证商品分类的名字是否存在
        /*
        *@param1 int $c_parent_id 
        *@param2 string $c_name
        *@param3 int $c_id要排除的商品分类的id
        *@return 存在true 不存在false
        */
        public function checkCategoryByParentIdAndName($c_parent_id,$c_name,$c_id){
            //防sql注入
            $c_name = addslashes($c_name);
            $sql = "select c_id from {$this -> getTable()} where c_parent_id = '{$c_parent_id}' and c_id = '{$c_id}' and c_name = '{$c_name}'";
            //执行
            //返回的原始结果不一定为bool，而此时需要一个bool作为返回值，因此使用强制转换
            return (boolean)$this -> DB_object -> s_arr($sql);
        }
        /*
        *更新商品信息方法updateCategoryById()
        *@param1 int $id 要更新的商品分类ID
        *@param2 string $c_name 要更新的商品分类的新的名字
        *@param3 int $c_parent_id 要更新的商品分类的父分类的ID
        *@param4 int $c_sort 新的排序
        *@return 成功返回受影响的行数,失败返回false
        */
        public function updateCategoryById($id,$c_name,$c_parent_id,$c_sort){
            //sql
            $sql = "update {$this -> getTable()} set c_name = '{$c_name}',c_parent_id = '{$c_parent_id}',c_sort = '{$c_sort}' where c_id = '{$id}'";
            //执行sql
            return $this -> DB_object -> udi($sql);
        }
        public function setDefault($id){
        	$sql1 = "update m_web set w_is_default = 0";
        	$sql2 = "update m_web set w_is_default = 1 where w_id = '{$id}'";
        	$this -> DB_object -> udi($sql1);
        	return (boolean)$this -> DB_object -> udi($sql2);
        }
    }
    