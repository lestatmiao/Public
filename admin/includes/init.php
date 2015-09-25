<?php
    //公共文件
    //路径常量
    session_start();
    define('ADMINDIR',str_replace('includes','',str_replace('\\','/',__DIR__)));
    define('INCDIR',ADMINDIR.'includes/');
    define('TEMPDIR',ADMINDIR.'templates/');
    define('CONFDIR',ADMINDIR.'conf/');
    define('IMGDIR',ADMINDIR.'images/');
    //用于保存上传图片的路径
    define('GIMGDIR',ADMINDIR.'goodsImage/');
    include_once INCDIR.'functions.php';
    $config = include_once CONFDIR.'config.php';
    //echo CONFDIR;
    //此处进行判断，如果当前请求脚本不是privilege.php则继续向下执行
    $script = basename($_SERVER['SCRIPT_NAME']);
    if($script != 'privilege.php'){
        //继续执行
        if(!isset($_SESSION['id'])){
        //直接加载主页
        //var_dump($_SESSION);
        if(isset($_COOKIE['user'])){
            //echo '3';
            $user = $_COOKIE['user'];
            @$pwd = $_COOKIE['pwd'];
            $admin = new Admin($user,$pwd);
            $arr_res = $admin -> getUserInfoByUsername();
            if($arr_res){
                //成功
                //echo '4';
                $_SESSION['id'] = $arr_res;
                //更新用户信息
                $time = time();
                $ip = $_SERVER['REMOTE_ADDR'];
                $admin -> setUserInfo($time,$ip);
                header("location:index.php");
                //$admin -> redirect('欢迎回来'.$user,'index.php');
                }else{
                    //失败
                    admin_redirect('请重新登录','privilege.php');
                }
            }else{
                //没有记住密码
                $admin = new Admin();
                admin_redirect('请先登录','privilege.php');
            }
        }   
    }
        
?>