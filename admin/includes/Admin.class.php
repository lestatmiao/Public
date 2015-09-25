<?php
    //操作数据表的类继承PDO类
    class Admin extends PDOSeries{
        public $user;
        public $pwd;
        public $DB_connect;
        //构造方法为属性初始化
        public function __construct($user='',$pwd=''){
            //在初始化的时候就建立数据库连接
            $this -> user = $user;
            $this -> pwd = $pwd;
            $this -> DB_connect = PDOSeries :: getInstance();
        }
        //获取用户信息的方法
        public function getUserInfoByUsername(){
            $sql = "select * from m_admin where a_user = '{$this -> user}'";
            $res = $this -> DB_connect -> s_arr($sql);
            if($res){
                return $res;
            }
        }
        //更新用户信息的方法
        public function setUserInfo($time,$ip){
            //更新用户信息sql语句
            $sql = "update m_admin set a_last_log_time='{$time}',a_last_log_ip='{$ip}' where a_user='{$this -> user}'";
            $this -> DB_connect -> udi($sql);
        }
    }
?>