<?php
	//基类
	class Controller{
		//定义一个属性用于创建模板引擎
		protected $view;
		public function __construct(){
			$this -> view = new View();
		}
		//跳转方法
		public function success($str,$url,$time = 1){
			include VIEW_DIR."/redirect.html";
		}
		//跳转方法
		public function fail($str,$url,$time = 3){
			include VIEW_DIR."/redirect.html";
		}
		//验证码方法
		public function captcha(){
			//调用验证码
			$captcha = new Captcha();
			$captcha -> setCaptcha();
			//以下为当用户提交表单时候通过隐藏域传递过来的信息
		}
	}