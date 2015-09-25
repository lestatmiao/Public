<?php
    //商品处理脚本
    $act = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'list' ;
    //加载公共文件
    include_once 'includes/init.php';
    //处理动作
    //var_dump($act);exit;
    if($act == 'list'){
        $goods = new Goods();
        $pagenow = isset($_GET['page']) ? $_GET['page'] : 1;
        //获取商品信息
        $lists = $goods -> getAllGoods($pagenow);
        //获取记录总数
        $total = $goods -> getAllGoodsCounts();
        //分页
        $page = new Page();//echo $total;exit;
        $page_str = $page -> getPageStr('goods.php','list',$total,$pagenow);
        //加载模板
        include_once TEMPDIR.'goods_list.html';
        //商品移除功能
    }elseif($act == 'remove'){
        $goods = new Goods();
        $id = $_GET['id'];
        //通过该ID对相应数据进行字段值得改变操作
        if($goods -> updateGoodsById($id)){
            //成功
            admin_redirect('成功加入回收站','goods.php?act=list',1);
        }else{
            //失败
            admin_redirect('加入回收站失败','goods.php?act=list');
        }
        //回收站相关操作
    }elseif($act == 'trash'){
        $goods = new Goods();
        //获取当前所有g_is_delete字段为1的数据，将其全部遍历显示在将被加载的模板goods_trash.html中并分页
        //获取当前页
        $pagenow = isset($_GET['page']) ? $_GET['page'] : 1;
        //获取回收站商品总数
        $total = $goods -> getAllGoodsCounts(1);
        //获取回收站商品列表
        $list = $goods -> getTrashes($pagenow);
        //实例化分页工具类对象
        $page = new Page();
        //调用分页方法同时使用变量接收分页返回的结果
        $page_string = $page -> getPageStr('goods.php','trash',$total,$pagenow);
        //包含模板页面并输出效果
        include_once TEMPDIR.'goods_trash.html';
    }elseif($act == 'rollback'){
        $goods = new Goods();
        $id = $_GET['id'];
        //通过id将对应数据的g_is_delete设置为0
        if($goods -> updateGoodsById($id,0)){
            //成功还原
            admin_redirect('还原成功','goods.php?act=trash',1);
        }else{
            //还原失败
            admin_redirect('还原失败','goods.php?act=trash');
            //添加新商品
        }
    }elseif($act == 'add'){
        $category = new Category();
        //获取全部分类供用户选择
        $lists = $category -> getAllCategories();
        //加载新增页面
        include_once TEMPDIR.'goods_add.html';
    }elseif($act == 'insert'){
        //此时为用户提交动作
        $data['i_name'] = isset($_POST['i_name']) ? $_POST['i_name'] : '';
        $data['i_nation'] = isset($_POST['i_nation']) ? $_POST['i_nation'] : '';
        $data['i_starrings'] = isset($_POST['i_starrings']) ? $_POST['i_starrings'] : '';
        $data['i_length'] = isset($_POST['i_length']) ?$_POST['i_length'] : '';
        /*图片文件的路径将被数据库保存，但要在提交之后才能获取*/
        $data['i_desc'] = isset($_POST['i_desc']) ? $_POST['i_desc']: '';
        $data['i_type'] = isset($_POST['i_type']) ? $_POST['i_type']: '';
        
        //var_dump($data['i_type']);exit;
        $data['i_director'] = isset($_POST['i_director'])?$_POST['i_director']: '';
        $data['i_is_hot'] = isset($_POST['i_is_hot'])? $_POST['i_is_hot']: '';
        $data['i_is_delete'] = isset($_POST['i_is_delete']) ? $_POST['i_is_delete']: '';
        $data['i_sort'] = isset($_POST['i_sort']) ? $_POST['i_sort']: '';
        $data['i_date'] = date("Y-m-d H:i:s");//添加时间
        $data['i_video'] = addslashes($_POST['i_video']);//视频上传链接
        //var_dump($data['i_video']);exit;
        //var_dump($data['i_date']);exit;
        $file = isset($_FILES['goods_image']) ?  $_FILES['goods_image'] : '';
        //判断带*号选项是否为空
        //所有选项都不能为空
        
        if(empty($data['i_name']) || empty($data['i_nation']) ||empty($data['i_starrings']) ||empty($data['i_length']) ||empty($data['i_desc']) ||empty($data['i_is_hot']) ||empty($data['i_director']) ||empty($data['i_sort']) ||empty($data['i_type']) ||!is_array($file)){
        	//var_dump($data);exit;
        	admin_redirect('所有选项都不能为空，请重试','goods.php?act=add');
        }
        $data['i_type'] = implode(',',$data['i_type']);
        //var_dump($data['i_type']);exit;
        $data['i_desc'] = addslashes($data['i_desc']);
        //判断完毕之后应该先进行图片上传，如果成功则导出图片存储路径，连同其他信息一起继续上传
        //接收图片文件
        //var_dump($_FILES);exit;
        $file = isset($_FILES['goods_image']) ?  $_FILES['goods_image'] : '';
        //在此定义一个变量用来限制上传文件的大小(int)
        $size = 500000;
        $data['i_image'] = '';
        //定义一个允许上传的文件数组
        $mime = array('image/jpeg','image/png','image/gif','image/jpg','image/pjpeg');
        //此处需要进行判断，如果没有被成功上传，则不需要存储路径以及制作缩略图
        if($file_upload = new filesManage($file,GIMGDIR,$size,$mime)){
            $arr_upload_info = $file_upload -> fileSaveInfo($file_upload -> path,$file_upload -> new_name);
            $data['i_image'] = $arr_upload_info[1];
            //制作缩略图
            if($thumb_name = Image :: makeThumb(GIMGDIR.'/'.$data['i_image'],GIMGDIR,$GLOBALS['config']['admin_goods_thumb_width'],$GLOBALS['config']['admin_goods_thumb_height'])){
                //制作成功
                //var_dump($thumb_name);exit;
                $data['i_thumb'] = $thumb_name;
            }else{
                //制作失败
            }
        }else{
        	admin_redirect('电影图片上传失败，请重试','goods.php?act=add');
        }
        $goods = new Goods();
        //上传已经完成，现在需要将数据导入数据库对应表中
        if($goods -> addGoods($data)){
            //成功
            admin_redirect('添加电影信息成功'.str_repeat('&nbsp;',3).FilesManage::$error,'goods.php?act=list');
        }else{
            //失败
            admin_redirect('添加电影信息失败','goods.php?act=add');
        }
    }elseif($act == 'change'){
    	$id = $_REQUEST['id'];
    	
    	include_once TEMPDIR.'infoupdate.html';
    	//定义一个用于更新影片路径的判断
    }elseif($act == 'update'){
    	$id = $_POST['id'];
    	$url = addslashes($_POST['i_video']);
    	if(empty($id) || empty($url)){
    		admin_redirect('内容不能为空。请重新输入','goods.php?act=list');
    	}
    	//echo $id;
    	//实例化相关工具类
    	$goods = new Goods();
    	if($goods -> setVideo($id,$url)){
    		admin_redirect('更新成功','goods.php?act=list');
    	}else{
    		admin_redirect('更新失败','goods.php?act=list');
    	}
    }elseif($act == 'review'){
    	//此动作显示字段r_is_allowed=0的数据
    	//首先获取这些数据
    	review:
    	$goods = new Goods();
    	$pagenow = isset($_GET['page']) ? $_GET['page'] : 1;
    	//获取商品信息
    	$arr_reviews = $goods -> getAllGoods($pagenow,'review');
    	//获取记录总数
    	$total = $goods -> getReviewCounts('review');
    	//var_dump($total);exit;
    	//分页
    	$page = new Page();//echo $total;exit;
    	$page_str = $page -> getPageStr('goods.php','review',$total,$pagenow);
    	//加载模板
    	//此处传值为0表示未审核，即不能显示在首页
    	//$arr_reviews = $goods -> getReviews(0,'review');
    	//var_dump($arr_reviews);exit;
    	include_once TEMPDIR.'reviews.html';
    }elseif($act == 'reviews_remove'){
    	//评论内容删除
    	$id = $_GET['id'];
    	$goods = new Goods();
    	//此处是删除评论的方法，第一个参数为被操作的id，第二个参数为该id对应的字段名，第三个参数为没有前缀的表名
    	if($goods -> delReviews($id,'r_id','review')){
    		admin_redirect('','goods.php?act=review',0);
    	}else{
    		admin_redirect('删除失败','goods.php?act=review');
    	}
    }elseif($act == 'reviews_allow'){
    	//评论内容审核
    	$id = $_GET['id'];
    	$goods = new Goods();
    	//因为只有两种情况：显示，或不显示，所以直接获取当前值通过判断取反
    	if($goods -> setAllow($id)){
    		admin_redirect('','goods.php?act=review',0);
    	}else{
    		admin_redirect('状态更改失败','goods.php?act=review');
    	}
    }elseif($act == 'search'){
    	$user = $_POST['user'];
    	if($user == ''){
    		goto review;
    	}
    	$goods = new Goods();
    	$arr_res = $goods -> searchReview($user);
    	include_once TEMPDIR.'results.html';
    }elseif($act == 'reviews_edit'){
    	//评论内容编辑
    	//从数据库获取当前评论的详细新消息并显示在加载的模板上
    	$id = $_GET['id'];
    	$goods = new Goods();
    	$arr_details = $goods -> getWholeReview($id);
    	include_once TEMPDIR.'edit.html';
    }elseif($act == 'reviews_update'){
    	//接收来自修改页面的数据
    	//echo '123';
    	$id = $_POST['id'];
    	$data['title'] = trim($_POST['title']);
    	$data['content'] = trim($_POST['content']);
    	$data['score'] = trim($_POST['score']);
    	$data['user'] = trim($_POST['user']);
    	$data['date'] = trim($_POST['date']);
    	$data['id'] = trim($_POST['i_id']);
    	$data['allow'] = trim($_POST['allow']);
    	//进行是否为空验证
    	if(empty($data['title']) || empty($data['content']) || empty($data['score']) || empty($data['user']) || empty($data['date']) || empty($data['id']) || empty($data['allow'])){
    		admin_redirect('所有选项都不能为空,请重新填写!','goods.php?act=review');
    	}
    	//var_dump(strlen($data['title']));exit;
    	if(strlen($data['title']) > 15){
    		admin_redirect('评论标题的长度不能大于15,请重新填写!','goods.php?act=review');
    	}
    	if($data['score'] > 10 || $data['score'] < 0){
    		admin_redirect('评分只能在0-10的范围内的正整数,请重新填写!','goods.php?act=review');
    	}
    	if(strlen($data['user']) > 20 || strlen($data['user']) < 4){
    		admin_redirect('用户名长度超过限制,请重新填写!','goods.php?act=review');
    	}
    	$goods = new Goods();
    	//var_dump($data,$id);exit;
    	if($goods -> updateReview($data,$id)){
    		admin_redirect('修改成功，即将为您跳转!','goods.php?act=review');
    	}else{
    		admin_redirect('服务器正忙,请重新尝试!','goods.php?act=review');
    	}
    }