<?php
    header("content-type:text/html;charset=utf-8");
    //6.21作业，封装PDO类，并实现单例
    //PDO类
    //PDO三大类:PDO(负责数据库的连接，初始化工作。以及没有返回的操作),PDOStatement(数据处理类),PDOException(异常处理类)
    class PDOSeries{
        private $type;
        private $host;
        private $port;
        private $user;
        private $pwd;
        private $dbname;
        private $charset;
        private $pdo;
        private $stmt;
        protected $prefix = 'm_';
        //私有化常量，该常量用来保存最终给外部的对象
        private static $dbObject;
        //定义一个公共静态方法用来对外部开放
        public static function getInstance($options = array()){
            if(self :: $dbObject instanceof self){
                return self :: $dbObject;
            }else{
                return self :: $dbObject = new self($options);
            }
        }
        //定义一个tostring方法避免错误输出
        public function __tostring(){
            return '你在逗我吗?';
        }
        //私有化克隆方法
        private function __clone(){}
        //私有化构造方法
        public function __construct($options = array()){
            $this -> type = isset($options['type']) ? $options['type'] : 'mysql';
            $this -> host = isset($options['host']) ? $options['host'] : '127.0.0.1';
            $this -> port = isset($options['port']) ? $options['port'] : '3306';
            $this -> user = isset($options['user']) ? $options['user'] : 'root';
            $this -> pwd = isset($options['pwd']) ? $options['pwd'] : '1';
            $this -> dbname = isset($options['dbname']) ? $options['dbname'] : 'movie';
            $this -> charset = isset($options['charset']) ? $options['charset'] : 'utf8';
            $this -> prefix = isset($options['prefix']) ? $options['prefix'] : 'm_';
            $this -> sqlConnect();
        }
        private function sqlConnect(){
            //语句比较麻烦，因此使用变量去保存
            $dsn = "mysql:host={$this -> host};port={$this -> port};dbname={$this -> dbname};charset={$this -> charset}";
            $user = "{$this -> user}";
            $pwd = "{$this -> pwd}";
            try{
                $this -> pdo = new PDO($dsn,$user,$pwd);
                $this -> pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                echo '数据库连接失败!<br />';
                echo '错误编码是:'.$e -> getCode().'<br />';
                echo '错误信息是:'.$e -> getMessage().'<br />';
                exit;
            }
        }
        //对数据库进行update&delete&insert操作:返回受影响行数
        public function udi($sql){
            //此处手动返回受影响行数
            try{
                return $this -> pdo -> exec($sql);
            }catch(PDOException $e){
                echo '错误编码是:'.$e -> getCode().'<br />';
                echo '错误信息是:'.$e -> getMessage().'<br />';
                exit;
            }
        }
        //对数据库进行select操作,此时返回的是statement对象，因此需要进一步操作
        //根据传进来的值进行返回，返回所需要的值得类型,默认返回关联数组
        //返回一个数组
        public function s_arr($sql,$type='a'){
            try{
                $this -> stmt = $this -> pdo -> query($sql);
            }catch(PDOException $e){
                echo '错误编码是:'.$e -> getCode().'<br />';
                echo '错误信息是:'.$e -> getMessage().'<br />';
                exit;
            }
            //通过在fetch参数里设置PDO常量来控制返回结果，可以为混合数组，或者关联数组，或者索引数组
            if($type == 'a'){
                return $this -> stmt -> fetch(PDO::FETCH_ASSOC);
            }else{
                return $this -> stmt -> fetch(PDO::FETCH_NUM);
            }
        }
        //返回一个二维数组
        public function s_arrs($sql,$type='a'){
            try{
                $this -> stmt = $this -> pdo -> query($sql);
            }catch(PDOException $e){
                echo '错误编码是:'.$e -> getCode().'<br />';
                echo '错误信息是:'.$e -> getMessage().'<br />';
                exit;
            }
            //通过在fetch参数里设置PDO常量来控制返回结果，可以为混合数组，或者关联数组，或者索引数组
            if($type == 'a'){
                return $this -> stmt -> fetchAll(PDO::FETCH_ASSOC);
            }else{
                return $this -> stmt -> fetchAll(PDO::FETCH_NUM);
            }
        }
        public function getTable($table = ''){
            if($table){
				//用户已经指定了表名
				return $this->prefix.$table;
			}else{
				//DB类对象的prefix属性+具体类对象的table属性
				return $this->prefix.$this->table;
			}
        }
        //类结束
    }
    //测试
    //调用静态方法实例化对象
    /*$connect = PDOSeries :: getInstance();
    $sql = "update student set student_name = 'laastat' where student_No < 100";
    $sql1 = "select * from student;";
    $affected_rows = $connect -> udi($sql);
    var_dump($affected_rows);
*/