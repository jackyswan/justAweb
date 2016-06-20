<?php
/**
 * 打开文件夹,遍历目录函数
 * User: wenquanliu
 * Date: 16/6/16
 * Time: 20:08
 * @param string $path
 * @return array
 */
function ReadDirectory($path){
    $handle = opendir($path);
    while(($item=readdir($handle)) !== false){
        if($item != "." && $item!=".."){
            if(is_file($path."/".$item)){
                $arr['file'][]=$item;
            }
            elseif(is_dir($path."/".$item)){
                $arr['dir'][]=$item;
            }
        }
    }
    closedir($handle);
    return $arr;
}

function dirSize($path){
    $handle = opendir($path);
    $sum=0;
    global $sum;
    while(($item=readdir($handle)) !== false){
        if($item != "." && $item!=".."){
            if(is_file($path."/".$item)){
            $sum+=filesize($path."/".$item);
            }
            elseif(is_dir($path."/".$item)){
                dirSize($path."/".$item);//递归调用
                //$func=__FUNCTION__;
                //$func($path."/".$item); 另一种递归调用写法
            }
        }
    }
    closedir($handle);
    return $sum;

}
function createFolder($dirname){
    if(checkFileName(basename($dirname))){
    if(!file_exists($dirname)){
        if(mkdir($dirname,0777,true)){
            $mes="创建成功";
        }else{
            $mes="文件夹创建失败";
        }
    }else{
        $mes="存在同名文件夹";
    }
    }else{
        $mes="文件名不合法";
    }
    return $mes;
}
function copyFolder($src,$dst){
   if(!file_exists($dst)) {
        mkdir($dst,0777,true);
    }
    $handle=opendir($src);
    while(($item=readdir($handle))!==false){
        if($item!="."&&$item!=".."){
            if(is_file($src."/".$item)){
                copy($src."/".$item,$dst."/".$item);
            }
            if(is_dir($src."/".$item)){
                $func=__FUNCTION__;
                $func($src."/".$item,$dst."/".$item);
            }
        }
    }
    closedir($handle);
    return "复制成功";
}
function delFolder($path){
    $handle=opendir($path);
    while(($item=readdir($handle))!==false){
        if($item!="."&&$item!=".."){
            if(is_file($path."/".$item)){
                unlink($path."/".$item);
            }
            if(is_dir($path."/".$item)){
                $func=__FUNCTION__;
                $func($path."/".$item);
            }
        }
    }
    closedir($handle);
    rmdir($path);
    return "文件夹删除成功";
}
function upLoadFile($fileinfo,$path,$maxSize=10485760){
    if($fileinfo['error']==UPLOAD_ERR_OK){
        if(is_uploaded_file($fileinfo['tmp_name'])){
            $ext=getEXT($fileinfo['name']);
            $uniqid=getUniqidName();
            $destination=$path."/".pathinfo($fileinfo['name'],PATHINFO_FILENAME)."_".$uniqid.".".$ext;
            if($fileinfo['size']<=$maxSize){
                if(move_uploaded_file($fileinfo['tmp_name'],$destination)){
                    $mes="文件上传成功";
                }else{
                    $mes="文件移动失败";
                }

            }else{
                $mes="文件过大";
            }
        }else{
             $mes="文件不是通过HTTP POST方式上传";
        }
    }else{
        switch($fileinfo['error']){
            case 1:$mes="超过了php.ini配置的大小"
                ;break;
            case 2:$mes="超过了网页允许大小";
                break;
            case 3:$mes="文件部分上传";
                ;break;
            case 4:$mes="没有文件被上传";
                break;

        }
    }
    return $mes;
}