<?php
    //登陆页面
    //判断请求类型，选择验证或加载表单页面
    include_once 'includes/init.php';
    $act = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'login';
    if($act == 'login'){
		//用户获取登录表单
		//获取登录表单
		include_once TEMPDIR."login.html";
	}elseif(isset($_REQUEST['user']) && isset($_REQUEST['pwd'])){
        //进行验证
        //防止SQL注入处理
        $user = addslashes($_REQUEST['user']);
        $pwd = md5('m_'.$_REQUEST['pwd']);
        //接收输入的验证码
        $captcha = $_REQUEST['captcha'];
        //echo $captcha,$user;exit;
        //通过admin类提取出该用户名对应的密码进行比对
        $admin = new Admin($user,$pwd);
        //验证验证码
        //$captcha = $_SESSION['captcha'];
        if(!Captcha::checkCaptcha($captcha)){
            admin_redirect('验证码错误<br />验证码也能填错??<br />智力是硬伤啊！','privilege.php');
        }
        if($arr_res = $admin -> getUserInfoByUsername()){
            //用户名正确，继续判断密码
            if($pwd == $arr_res['a_pwd']){
                //登陆成功，开启session
                
                //保存用户信息到session
                $_SESSION['id'] = $arr_res;
                //此时判断用户是否选择了记住密码，如果有，则设置cookie来保存用户信息
                if(isset($_REQUEST['rem']) && $_REQUEST['rem'] == 1){
                    setcookie('user',$arr_res['a_user'],time() + 24*3600,'/','shop.com');
                    setcookie('pwd',$arr_res['a_pwd'],time() + 24*3600,'/','shop.com');
                }
                //同时记录用户当次登陆时间和IP
                $time = time();
                $ip = $_SERVER['REMOTE_ADDR'];
                $admin -> setUserInfo($time,$ip);
                admin_redirect('登陆成功，系统将自动跳转至主页','index.php');
            }else{
               admin_redirect('密码错误，登陆失败，系统将自动跳转至登陆页面','privilege.php');
            }
        }else{
            admin_redirect('用户名不存在，登陆失败，系统将自动跳转至登陆页面','privilege.php');
        }
    }elseif($act == 'logout'){
		$_SESSION['id'] = array();
		unset($_SESSION['id']);
		setcookie('user','',time() - 1,'/','shop.com');
		setcookie('pwd','',time() - 1,'/','shop.com');
        include_once TEMPDIR."login.html";
		//$admin -> redirect('privilege.php','退出成功!');
	}elseif($act == 'captcha'){
        //调用验证码
        $captcha = new Captcha();
        $captcha -> setCaptcha();
        //以下为当用户提交表单时候通过隐藏域传递过来的信息
    }elseif($act == 'update'){
        //更新商品信息
        //此时需要接收用户提交表单传递过来的数据
        $c_name = trim($_POST['category_name']);
        $c_parent_id = $_POST['parent_id'];
        $c_sort = trim($_POST['sort_order']);
        
    }