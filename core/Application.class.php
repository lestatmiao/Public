<?php
	//判断是否定义密钥文件
	if(!defined('ACCESS')){
		exit;
	}
	//初始化Application类，用于初始化项目信息
	class Application{
		//类开始
		//该静态属性用于传递需要操作的指定个体
		public static $id;
		public static $id1;
		/*
		 * 静态方法run，用于初始化项目
		 * */
		public static function run(){
			//初始化项目字符编码
			self::setHeader();
			//初始化项目常量
			self::setConst();
			//初始化项目错误级别
			self::setError();
			//初始化项目配置文件信息
			self::setConfig();
			//初始化session信息
			self::setSession();
			//实现自动加载机制
			self::setAutoload();
			//url初始化
			self::setUrl();
			//权限验证
			self::setPrivilege();
			//设置请求分发
			self::setDispatch();
		}
		/*
		 * 定义项目字符集设置静态方法
		 * */
		public static function setHeader(){
			header('Content-Type:text/html;Charset=utf-8');
		}
		/*
		 * 定义初始化系统常量信息
		 * */
		public static function setConst(){
			//定义根目录
			define('ROOT_DIR',str_replace('core','',str_replace('\\','/',__DIR__)));
			define('CORE_DIR',ROOT_DIR.'core');
			define('CONT_DIR',ROOT_DIR.'controller');
			define('MODEL_DIR',ROOT_DIR.'model');
			define('VIEW_DIR',ROOT_DIR.'view');
			define('CONF_DIR',ROOT_DIR.'config');
			define('PUB_DIR',ROOT_DIR.'public');
			define('IMG_DIR',ROOT_DIR.'admin/goodsImage');
		}
		/*
		 * 设置项目错误级别方法
		 * */
		public static function setError(){
			ini_set('display_errors',1);
			error_reporting(E_ALL);
		}
		/*
		 * 设置项目配置文件方法
		 * */
		public static function setConfig(){
			$GLOBALS['config'] = include CONF_DIR.'/config.php';
		}
		/*
		 * 用于初始化session信息
		 * 
		 * */
		public static function setSession(){
			@session_start();
		}
		/*
		 * 定义方法用于自动加载机制
		 * */
		
		public static function loadController($class){
			if(is_file(CONT_DIR."/$class.class.php")){
				include CONT_DIR."/$class.class.php";
			}
		}
		public static function loadModel($class){
			if(is_file(MODEL_DIR."/$class.class.php")){
				include MODEL_DIR."/$class.class.php";
			}
		}
		public static function loadCore($class){
			if(is_file(CORE_DIR."/$class.class.php")){
				include CORE_DIR."/$class.class.php";
			}
		}
		private static function setAutoload(){
			//var_dump(self :: loadCore());exit;
			spl_autoload_register("self::loadCore");
			spl_autoload_register("self::loadController");
			spl_autoload_register("self::loadModel");
		}
		/*
		 * 定义方法用于url初始化
		 * */
		public static function setUrl(){
			$controller = isset($_REQUEST['c'])? $_REQUEST['c']: 'index';
			$action = isset($_REQUEST['a'])? $_REQUEST['a']: 'show';
			isset($_REQUEST['id'])?self::$id=$_REQUEST['id']: '';
			isset($_REQUEST['id1'])?self::$id1=$_REQUEST['id1']: '';
			
			//转化所有参数为小写
			$controller = strtolower($controller);
			$action = strtolower($action);
			//将$controller和$action保存为常量以便全局访问
			//将类名首字母大写
			//var_dump($controller);exit;
			$controller = ucfirst($controller);
			//var_dump($controller);exit;
			define('CONTROLLER',$controller);
			//var_dump(CONTROLLER);exit;
			define('ACTION',$action);
		}
		/*
		 * 定义方法用于权限判断
		 * */
		public static function setPrivilege(){
			if(!CONTROLLER == 'privilege' && (ACTION == 'login' || ACTION == 'signin' || ACTION == 'captcha')){
				if(!isset($_SESSION['user'])){
					header("location:index.php");
				}
		    }
		}
		/*
		 * 根据a和c的参数，把url请求到相关页面
		 * 
		 * */
		private static function setDispatch(){
			//获取控制器方法
			//var_dump(CONTROLLER.'Controller()');exit;
			$class = CONTROLLER.'Controller';
			/* var_dump(self::$id);
			var_dump(self::$id1);exit; */
			//在此判断类是否存在，不存在则直接加载404页面
			if(!class_exists($class)){
				include_once VIEW_DIR.'/404.html';
				exit;
			}
			if(self::$id){
				if(self::$id1){
					$controller = new $class(self::$id,self::$id1);
				}else{
					//echo 'id1不存在';
					$controller = new $class(self::$id);
				}
			}else{
				$controller = new $class();
			}
			//判断类中的方法是否存在,如果不存在，则加载404页面
			$action = ACTION;
			if(!method_exists($controller,$action)){
				include_once VIEW_DIR.'/404.html';
				exit;
			}
			$controller -> $action();
		}
		//类结束
	}