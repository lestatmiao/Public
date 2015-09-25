<?php
	class RegisterController extends Controller{
		public function register(){
			//获取当前所有用户名
			$manage = new Manage();
			$arr_all_users = $manage -> getAllUsers();
			$arr_users = array();
			//var_dump($arr_all_users);exit;
			foreach($arr_all_users as $v){
				$arr_users[] = $v['u_user'];
			} 
			$str_users = implode(',',$arr_users);
			//var_dump($arr_users);exit;
			//进行加载注册页面
			include_once VIEW_DIR.'/register.html';
		}
		public function submit(){
			//进行注册用户信息验证及入库
			//接收用户提交数据
			$data['user'] = trim($_POST['user']);
			$data['pwd'] = trim($_POST['pwd']);
			$data['repwd'] = trim($_POST['repwd']);
			$data['tele'] = trim($_POST['tele']);
			$data['email'] = trim($_POST['email']);
			$captcha = trim($_POST['captcha']);
			//var_dump($data['user']);exit;
			//所有信息都不能为空
			if(empty($captcha) || empty($data['user']) || empty($data['pwd']) || empty($data['email']) || empty($data['tele'])){
				//$redirect = new RedirectController;
				$this -> fail('所有选项都为必填项，请重新填写<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//判断验证码是否正确
			if($captcha != $_SESSION['captcha']){
				$this -> fail('验证码错误，请重新填写<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//判断用户名中是否包含中文
			if (preg_match("/[\x7f-\xff]/", $data['user'])) {
				$this -> fail('用户名不能含有中文，请重新填写<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//判断用户名长度
			if(strlen($data['user']) > 20 || strlen($data['user']) < 4){
				$this -> fail('用户名长度不符合要求(长度为4-20位)，请重新填写<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//判断两次输入密码是否相同
			if($data['repwd'] !== $data['pwd']){
				$this -> fail('两次密码输入不相同，请重新填写<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//判断密码长度是否符合要求
			if(strlen($data['pwd']) > 20 || strlen($data['pwd']) < 6){
				$this -> fail('密码长度不符合要求(长度为6-20位)，请重新填写<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			
			//检测同户名是否重复
			//调用register.class.php模型通过数据库获取当前用户信息
			$register = new Register;
			$res = $register -> checkRepeat($data['user'],'u_user');
			if($res){
				$this -> fail('用户名已被注册，请重新填写或直接登陆<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//检测手机号是否为纯数字
			if(!is_numeric($data['tele']) || strlen($data['tele']) != 11){
				$this -> fail('手机号输入错误,需要输入11为纯数字,请重新填写<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//检测手机号是否重复
			$res = $register -> checkRepeat($data['tele'],'u_tele');
			if($res){
				$this -> fail('手机号已被占用，请重新填写或直接登陆<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//检测邮箱是否重复
			$res = $register -> checkRepeat($data['email'],'u_email');
			if($res){
				$this -> fail('邮箱已被占用，请重新填写<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
			//对密码进行md5加密处理
			$data['pwd'] = md5('m_'.$data['pwd']);
			//验证结束，进行信息入库操作
			$res = $register -> addUser($data);
			if($res){
				//注册成功，创建session['user']
				$_SESSION['user'] = $data['user'];
				//var_dump($_SESSION['user']);exit;
				$this -> success('恭喜。注册成功，即将为您跳转至主页<br />页面将在2秒内跳转至主页','index.php?c=index&a=show');
			}else{
				$this -> fail('抱歉,系统正忙,请稍后再试<br />页面将在2秒内跳转','index.php?c=register&a=register');
				exit;
			}
		}
}