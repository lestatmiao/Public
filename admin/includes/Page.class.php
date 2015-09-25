<?php
    //分页工具类
    //跟数据库无关，不需要继承PDO类
    class Page{
        //属性[也可以没有]
        private $pagecount;//每页像是的数据量
        public function __construct($pagecount = 0){
            //如果经常会发生变化的内容:config.php
            $this -> pagecount = ($pagecount == 0) ? $GLOBALS['config']['admin_goods_pagecount'] : $pagecount;
        }
        /*
        *生成分页链接
        *@param1 int $total 总记录数
        *@param2 int $page = 1 当前页码
        *
        *@return string 字符串分页链接
        */
        public function getPageStr($script,$act,$total,$page = 1){
            //求出总页数
            $pages = ceil($total / $this -> pagecount);
            if($pages == 0){
            	$pages = 1;
            }
            //文字提示
            $page_str = "一共有{$total}条记录,{$pages}页，每页显示{$this -> pagecount}条记录,当前是第{$page}页";
            //上一页和下一页
            $prev = $page > 1 ? $page - 1 : 1;
            $next = $page < $pages ? $page + 1 : $pages;
            //点击分页
            $page_click = <<<END
                <a href="{$script}?act={$act}&page=1">首页</a>
                <a href="{$script}?act={$act}&page={$prev}">上一页 </a>
                <a href="{$script}?act={$act}&page={$next}">下一页</a>
                <a href="{$script}?act={$act}&page={$pages}">末页</a>
END;
            //返回分页信息
            //var_dump($page_str.$page_click);exit;
            return $page_str.$page_click;
        }
    }