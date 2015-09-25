<?php
	class PrivilegeController extends Controller{
		//类开始
		//创建一个id属性，来确定请求来源页面
		private $id;
		private $id1;
		public function __construct($id = '',$id1=''){
			$this -> id = $id;
			$this -> id1 = $id1;
		}
		public function login(){
			//进行登陆页面的加载
			if(isset($_SESSION['user'])){
				if($this -> id != '' && $this -> id1 != ''){
					$this -> success('您已登录，即将为您跳转','index.php?c='.$this -> id1.'&a=show&id='.$this -> id,1);
					exit;
				}else{
					$this -> success('您已登录，即将为您跳转','index.php?c=index&a=show',1);
					exit;
				}
			}else{
				if(!empty($_COOKIE['user'])){
					//var_dump($_COOKIE['user']);
					//var_dump($_SESSION['user']);exit;
					$_SESSION['user'] = $_COOKIE['user'];
					if($this -> id != '' && $this -> id1 != ''){
						$this -> success('您已登录，即将为您跳转','index.php?c='.$this -> id1.'&a=show&id='.$this -> id,1);
						exit;
					}else{
						$this -> success('您已登录，即将为您跳转','index.php?c=index&a=show',1);
						exit;
					}
				}
			}
			include_once VIEW_DIR.'/login.html';
		}
		public function signin(){
			//进行登录验证
			//var_dump($_SESSION['user']);exit;
			//首先判断用户是否有设置cookie['user'],如果有，则自动登录
			/* if($cookie['user']){
			} */
			$user = trim($_POST['user']);
			$pwd = trim($_POST['pwd']);
			$captcha = trim($_POST['captcha']);
			//var_dump($_SESSION['captcha']);exit;
			$rem = isset($_POST['rem']) ? $_POST['rem']: '';
			//合法性验证，数据，验证码是否为空
			if(empty($user) || empty($pwd) || empty($captcha)){
				$this -> fail('用户名,密码或验证码都不能为空,请重新填写<br />页面即将跳转','index.php?c=privilege&a=login');
				exit;
			}
			//验证验证码是否正确
			if($captcha != $_SESSION['captcha']){
				$this -> fail('验证码错误,请重新填写<br />页面即将跳转','index.php?c=privilege&a=login');
				exit;
			}
			//密码进行md5加密处理
			$pwd = md5('m_'.$pwd);
			$privilege = new Privilege();
			//调用模型进行合理性验证
			if($privilege -> checkUserPwd($user,$pwd)){
				//验证成功后首先判断用户是否选择了记住账号
				if($rem == 1){
					//如果选择了，则为用户设置cookie
					setcookie('user',$user,time() + 3600 * 24 * 7,'/');
				}
				//设置session
				$_SESSION['user'] = $user;
				//用户本次登陆操作的ip和时间信息入库
				$privilege -> setIpTime($user);
				if($this -> id != '' && $this -> id1 != ''){
					$this -> success('登陆成功<br />页面即将跳转','index.php?c='.$this -> id1.'&a=show&id='.$this -> id);
					exit;
				}else{
					$this -> success('登陆成功<br />页面即将跳转','index.php?c=index&a=show');
					exit;
				}
			}else{
				$this -> fail('登录失败,用户名或密码错误<br />页面即将跳转','index.php?c=privilege&a=login');
				exit;
			}
		}
		public function logout(){
			//销毁session，cookie
			$_SESSION['user'] = null;
			unset($_SESSION['user']);
			//此处一定切记，路径的参数如果在创建cookie的时候设置了，现在就一定要设置，否则无法删除cookie！
			setcookie('user','',time() - 1,'/');
			//退出登陆之后跳转至首页
			$this -> success('退出成功<br />即将为您跳转','index.php?c=index&a=show');
			exit;
		}
		public function captcha(){
				//调用验证码
				$captcha = new Captcha();
				$captcha -> setCaptcha();
				//以下为当用户提交表单时候通过隐藏域传递过来的信息
		}
		//类结束
	}