<?php
	//电影评论控制器
	class ReviewController extends Controller{
		public $id;
		public $id1;
		public function __construct($id='',$id1=''){
			if(empty($id)){
				$this -> fail('错误的请求，请通过评论链接访问该页面，页面即将跳转到首页','index.php?c=index&a=show');
				exit;
			}
			$this -> id = $id;
			$this -> id1 = $id1;
		}
		public function show(){
			if(isset($_SESSION['user'])){
				$review = new Review();
				$arr_infos = $review -> getInfos($this -> id);
				include_once VIEW_DIR.'/review.html';
			}else{
				//var_dump($this -> id,$this -> id1);exit;
				$this -> fail('登陆后才能进行评论，正在为您跳转','index.php?c=privilege&a=login&id='.$this -> id.'&id1='.$this -> id1);
				exit;
			}
			//用户请求页面，此时进行页面加载
		}
		public function submit(){
			//用户提交评论，此时进行验证
			$data['title'] = trim($_POST['title']);
			$data['content'] = addslashes($_POST['content']);
			$data['id'] = $_POST['id'];
			$data['score'] = $_POST['score'];
			$data['time'] = time();
			$data['allowed'] = 0;
			$captcha = $_POST['captcha'];
			//var_dump($_SESSION['user']);exit;
			$data['user'] = $_SESSION['user'];
			if(empty($captcha) || empty($data['score']) || empty($data['title']) || empty($data['content'])){
				//验证失败，跳转
				$this -> fail('评论标题,分数,评论内容都不能为空,请重新填写<br />页面即将跳转','index.php?c=review&a=show&id='.$data['id']);
				exit;
			}
			//判断验证码是否正确
			if($captcha != $_SESSION['captcha']){
				$this -> fail('验证码错误,请重新填写<br />页面即将跳转','index.php?c=review&a=show&id='.$data['id']);
				exit;
			}
			//判断标题长度在20字符以内,内容长度在300个字符以内
			if(strlen($data['title']) > 60 || strlen($data['content']) > 900){
				$this -> fail('输入标题或内容长度不合法，请参考评论页面提示<br />页面即将跳转','index.php?c=review&a=show&id='.$data['id']);
				exit;
			}
			$data['content'] = addslashes($data['content']);
			//简单验证成功，进行信息入库操作，调用模型类方法
			$review = new Review();
			if($review -> addContent($data)){
				//成功
				//获取r_score对应当前电影的平均分
				$average = $review -> getAverage($data['id']);
				//将平均分放入m_infos对应的i_score字段
				$details = new Details();
				$details -> setScore($data['id'],$average);
				$this -> success('评论成功<br />页面即将跳转','index.php?c=details&a=details&id='.$data['id']);
				exit;
			}else{
				//失败
				$this -> fail('非常抱歉，系统繁忙，请稍后再试<br />页面即将跳转','index.php?c=review&a=show&id='.$data['id']);
				exit;
			}
		}
		public function del(){
			//删除评论--删除id为当前id属性的评论
			$review = new Review();
			if($review -> delReview($this -> id)){
				$this -> success('删除成功<br />页面即将跳转','index.php?c=manage&a=show');
				exit;
			}else{
				$this -> fail('服务器正忙，请稍后再试<br />页面即将跳转','index.php?c=manage&a=show');
				exit;
			}
		}
		
	}