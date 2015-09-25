<?php
    //处理所有与商品分类有关的增删改查
    $act = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'list';
    //加载公共文件
    include_once 'includes/init.php';
    $category = new Category();
    //判断动作,处理动作
    if($act == 'list'){
        //商品列表
        //查询所有分类信息
        //使用一个对应的类来完成对该表的操作
        $lists = $category -> getAllCategories();
        //加载显示的模板
        include_once TEMPDIR.'category_list.html';
    }elseif($act == 'weblist'){
        //商品列表
        //查询所有分类信息
        //使用一个对应的类来完成对该表的操作
        $lists = $category -> getAllCategories('m_web');
        //加载显示的模板
        include_once TEMPDIR.'weblist.html';
    }elseif($act == 'add'){
        $lists = $category -> getAllcategories();
        include_once TEMPDIR.'category_add.html';
    }elseif($act == 'web'){
        $lists = $category -> getAllcategories();
        include_once TEMPDIR.'web.html';
    }elseif($act == 'insert'){
        $n_title = trim($_POST['n_title']);
        $n_contents = $_POST['n_contents'];
        //合法性验证
        //名字不能为空
        if(empty($n_title) || empty($n_contents)){
            //跳转，商品分类名字不能为空
            admin_redirect('新闻标题和内容都不能为空','category.php?act=add');
        }
        if($category -> insertCategory($n_title,$n_contents)){
            admin_redirect('新闻发布成功','category.php?act=list');
        }else{
            admin_redirect('新闻发布失败','category.php?act=add');
        }
    }elseif($act == 'web_insert'){
        $n_title = trim($_POST['n_title']);
        $n_contents = $_POST['n_contents'];
        //合法性验证
        //名字不能为空
        if(empty($n_title) || empty($n_contents)){
            //跳转，商品分类名字不能为空
            admin_redirect('信息标题和内容都不能为空','category.php?act=add');
        }
        if($category -> insertCategory($n_title,$n_contents,'m_web','w_')){
            admin_redirect('信息发布成功','category.php?act=weblist');
        }else{
            admin_redirect('信息发布失败','category.php?act=add');
        }
    }elseif($act == 'delete'){
        //如果是删除操作，需要先获得将被删除的数据的具体信息
        $id = $_GET['id'];
        //先判断此类是否为叶子类
        if($category -> delData($id)){
            //删除成功
            admin_redirect('新闻删除成功','category.php');
        }else{
            //删除失败
            admin_redirect('新闻删除失败','category.php?act=list');
        }
        //判断当请求为edit的时候
    }elseif($act == 'edit'){
        //得到当前请求编辑的行的id
        $id = $_GET['id'];
        //获取商品分类信息
        $lists = $category -> getCategoryById($id);
        //获取全部商品分类信息
        //因为用户可能不止修改当前类的信息，也可能修改上级类的信息，因此需要获取全部信息
        $categories = $category -> getAllCategories();

        include_once TEMPDIR.'category_edit.html';
        //echo $c_parent_id;exit;
    }elseif($act == 'update'){
        //更新商品分类信息，接收用户提交的数据
        $c_name = trim($_POST['category_name']);
        $c_parent_id = $_POST['parent_id'];
        $c_sort = trim($_POST['sort']);
        $id = trim($_POST['id']);
        
        //echo $id;exit;
        //合法性验证
        //分类名字不能为空
        
        if(empty($c_name)){
            //跳转，商品分类名字不能为空
            admin_redirect('分类名字不能为空','category.php?act=edit&id='.$id);
        }
        //排序必须是正整数
        if(!is_numeric($c_sort) || !(integer)$c_sort == $c_sort || $c_sort < 0){
            //跳转，排序必须是大于0的整数
            admin_redirect('排序必须是大于0的整数','category.php?act=edit&id='.$id);
        }
        //通过合法性验证，继续合理性验证
        //验证更改的类如果出现在同一个父类下的时候不能重复
        if($category -> checkCategoryByParentIdAndName($c_parent_id,$c_name,$id)){
            //重复，失败，跳转至编辑页面
            admin_redirect('分类名字重复','category.php?act=edit&id='.$id);
        }
        //此时进行update操作
        if($id = $_GET['id']){
            //成功，跳转
            admin_redirect('分类修改成功','category.php?act=list',1);
        }else{
            admin_redirect('分类修改失败','category.php?act=list');
        }
    }elseif($act == 'default'){
    	$id = $_GET['id'];
    	//改变默认参数为1
    	if($category -> setDefault($id)){
    		admin_redirect('修改默认设置成功','category.php?act=weblist',1);
    	}else{
    		admin_redirect('修改默认设置失败','category.php?act=weblist');
    	}
    }