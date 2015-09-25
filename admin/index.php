<?php
    //主页，用于判断用户请求类型，同时跳转和加载
    header("content-type:text/html;charset=utf-8");
    $act = isset($_GET['act']) ? $_GET['act'] : 'index' ;
    //echo '<hr />';
    //加载公共文件
    include_once 'includes/init.php';
    //var_dump($_SESSION);
    //var_dump($_SESSION);
    //判断用户是否已经登录,已登录则直接加载主页
        if($act == 'index'){
            include_once 'templates/index.html';
        }elseif($act == 'top'){
            include_once 'templates/top.html';
        }elseif($act == 'main'){
            include_once 'templates/main.html';
        }elseif($act == 'drag'){
            include_once 'templates/drag.html';
        }elseif($act == 'menu'){
            include_once 'templates/menu.html';
        }
?>