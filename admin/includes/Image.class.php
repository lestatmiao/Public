<?php
    //缩略图工具类
    class Image{
        public static $thumb_error = '';
        public static function makeThumb($file,$path,$width,$height){
            if(!is_file($file)){
                self :: $thumb_error = '原图文件不存在';
                return false;
            }
            $funcs = array(
                'image/gif' => 'gif',    
                'image/png' => 'png',    
                'image/jpg' => 'jpg',    
                'image/jpeg' => 'jpeg',    
                'image/pjpeg' => 'pjpeg'    
            );
            $info = getimagesize($file);
            //确定函数名
            $create = 'imagecreatefrom'.$funcs[$info['mime']];
            $save = 'image'.$funcs[$info['mime']];
            //利用可变函数获取资源
            $src = $create($file);
            //获取缩略图资源
            $dst = imagecreatetruecolor($width,$height);
            //补白调色
            $dst = imagecreatetruecolor($width,$height);
            $bgc = imagecolorallocate($dst,255,255,255);
            imagefill($dst,0,0,$bgc);
            //计算缩略图实际宽高比
            $src_cmp = $info[0] / $info[1];
            $dst_cmp = $width / $height;
            //比较比例
            //就是说如果原图的宽高比大于缩略图的宽高比，则以宽的变化为准，高随其比例变化；如果原图的宽高比小于缩略图的宽高比，则以高的变化为准，宽随其变化。
            if($src_cmp > $dst_cmp){
                $dst_width = $width;
                $dst_height = floor($dst_width / $src_cmp);
            }else{
                $dst_height = $height;
                $dst_width = floor($dst_height * $src_cmp);
            }
            //计算缩略图起始位置
            $dst_x = floor(($width - $dst_width) / 2);
            $dst_y = floor(($height - $dst_height) / 2);
            //采样复制
            if(!imagecopyresampled($dst,$src,$dst_x,$dst_y,0,0,$dst_width,$dst_height,$info[0],$info[1])){
                //采样失败
                self :: $thumb_error = '采样复制失败';
                return false;
            }
            //保存缩略图:在原文件名前面加前缀
            //拼凑名字
            $thumb_name = 'thumb_'.basename($file);
            if($save($dst,$path.'/'.'thumb_'.$thumb_name)){
                //成功，返回缩略图名字
                //var_dump($thumb_name);exit;
                return $thumb_name;
            }else{
                self::$thumb_error = '缩略图保存失败';
                return false;
            }
            //可以不需要释放资源，局部变量会在执行结束之后自动释放
        }
        public function makeWater(){
            
        }
    }