<?php
    //该类暂时不需要调用方法，因此出构造方法外全部封装成private,也不需要被继承，因此直接使用final
    header("content-type:text/html;charset=utf-8");
    final class FilesManage{
            private $files;
            public $path;
            private $size;
            private $mime;
            public $new_name;
            public $full_path;
            public static $error = '';
            public function __construct($files,$path,$size,$mime){
                $this -> files = $files;
                $this -> path = $path;
                $this -> size = $size;
                $this -> mime = $mime;
                $this -> files_manage($this -> files,$this -> path,$this -> size,$this -> mime);
            }
            private function files_manage($files,$path,$size,$mime){
                //符合图片上传5要素
                if(!is_array($files) || count($files) != 5){
                	//var_dump($files);exit;
                    self :: $error = '图片错误，不是一个合理的上传图片';
                    return false;
                }else{
                    $keys = array_keys($files);
                    $correct_types = array('name','type','tmp_name','error','size');
                    if($keys !== $correct_types){
                        self :: $error = '图片不合理，请重新选择';
                        return false;
                    }
                }
                switch($files['error']){
                            case 1:
                                self :: $error = '图片超过允许的大小,实际允许的大小为:'.ini_get('upload_max_filesize');
                                return false;
                            case 2:
                                self :: $error = '上传图片超过表单限定大小';
                                return false;
                            case 3:
                                self :: $error = '图片上传不完整';
                                return false;
                            case 4:
                                self :: $error = '没有图片被上传,不过也没事哦';
                                return false;
                            case 6:
                            case 7:
                                self :: $error = '服务器正忙';
                                return false;
                        }
                if(!in_array($files['type'],$mime)){
                    self :: $error = '图片类型不正确，只允许上传:';
                    for($i = 0,$length = count($mime); $i < $length; $i++){
                        self :: $error .= $mime[$i].' ';
                    }
                    self :: $error .= '格式的图片';
                    self :: $error .= '当前图片格式为:'.$files['type'];
                    return false;
                }
                if($files['size'] > $size){
                            self :: $error = '上传图片超过限制大小最大允许200KB';
                            return false;
                        }
                        //处于安全性考虑，需要判断其是否为一个真正的上传图片
                if(!is_uploaded_file($files['tmp_name'])){
                   self :: $error = '上传图片可疑请重新选择!';
                   return false;
                }
                        //上传图片验证成功，此时需要对图片重命名，调用命名函数，将命名结果赋值给一个变量
                $new_name = $this -> newName($files['type']);
                $this -> new_name = $new_name;
                $this -> full_path = $path.'/'.$new_name;
                if(move_uploaded_file($files['tmp_name'],$path.'/'.$new_name)){
                    self :: $error = '图片上传成功';
                    return true;
                }else{
                    self :: $error = '图片上传失败';
                    return false;
                }
            }
            //随机命名方法
            private function newName($files_type){
                $str = 'qwertyuiopasdfghlkjzxcmnbvQWERTYPOIUASDFGHLKJZXCMNBV1234567890';
                //截取图片后缀
                $files_type = substr($files_type,strpos($files_type,'/') + 1);
                //定义一个字符串用来存放新图片名
                $new_name = date('YmdHms');
                //问题:为什么$i<6却能输出12个字符
                for($i = 0,$length = strlen($str);$i < 6; $i++){
                    $new_name .= $str[mt_rand(0,$length - 1)];
                }
                //将新图片名返回至调用处
                return $new_name.'.'.$files_type;
            }
            //创建一个保存用户上传图片详细信息公共方法
            //该方法需求：
            public function fileSaveInfo($path,$new_name){
                $arr_info = array($path,$new_name);
                return $arr_info;
            }
        }
?>