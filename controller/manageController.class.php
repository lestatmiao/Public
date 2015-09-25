<?php
	//个人中心控制器
	class ManageController extends Controller{
		//增加一个id属性，方便对头像上传等请求进行处理
		//id确定操作对象
		private $id;
		//id1确定控制器
		private $id1;
		//构造方法完成对属性id的初始化
		public function __construct($id='',$id1=''){
			$this -> id = $id;
			$this -> id1 = $id1;
		}
		public function show(){
			//判断session是否存在
			if(isset($_SESSION['user'])){
				$user = $_SESSION['user'];
				$manage = new Manage();
				//获取该用户的详细信息
				$arr_infos = $manage -> getUserInfo($user);
				$review = new Review();
				//获取该用户发表的评论信息
				$arr_reviews = $review -> getUserReview($user);
				include_once VIEW_DIR.'/userdetails.html';
			}else{
				$this -> fail('请先登录才能进入个人中心,正在为您跳转','index.php?c=privilege&a=login&id=manage&id1=manage');
			}
			//加载个人中心页面
			//获取当前登陆账号相关信息
			//var_dump($_SESSION['user']);exit;
		}
		//增加一个方法用于对用户头像上传进行处理
		public function add(){
			$file = $_FILES['u_image'];
			//var_dump($file);exit;
			//定义文件上传的路径
			$path = ROOT_DIR.'/public/images/';
			//定义允许的文件类型
			$mime = 'image/jpg,image/jpeg,image/pjpeg,image/gif,image/png';
			//定义允许上传的文件大小
			$size = 2000000;
			$file_name = Upload::uploadSingle($file,$path,$mime,$size);
			//对上传结果进行判断
			if($file_name){
				//上传成功
				//进一步进行缩略图处理
				//获取图片文件包含文件名的完整路径
				$full_name = $path.$file_name;
				//var_dump($full_name);exit;
				$path_thumb = ROOT_DIR.'/public/thumb/';
				$width = 20;
				$height = 20;
				$thumb_name = Image::makeThumb($full_name,$path_thumb,$width,$height);
				//var_dump($thumb_name);exit;
				//判断缩略图制作是否成功
				if($thumb_name){
					//成功
					//将完整图和缩略图文件路径都保存至数据表相应字段
					$manage = new Manage();
					$res = $manage -> updateInfo($file_name,$thumb_name,$this -> id);
					if($res){
						//成功
						$str = '个人形象上传成功,即将为您跳转';
						//为用户跳转至用户中心页面
						$this -> fail($str,'index.php?c=manage&a=show');
					}else{
						//失败
						$str = '抱歉,个人形象上传失败,错误信息:'.Image::$thumb_error.'请重新尝试';
						//为用户跳转至用户中心页面
						$this -> fail($str,'index.php?c=manage&a=show');
					}
				}else{
					//失败
					$str = '抱歉,个人形象上传失败,错误信息:'.Image::$thumb_error.'请重新尝试';
					//为用户跳转至用户中心页面
					$this -> fail($str,'index.php?c=manage&a=show');
				}
			}else{
				//上传失败
				$str = '抱歉,头像上传失败,错误信息:'.Upload::$error.'请重新尝试';
				//为用户跳转至用户中心页面
				$this -> fail($str,'index.php?c=manage&a=show');
			}
		}
	}