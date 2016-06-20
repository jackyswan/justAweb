<?php
/**
 * Created by PhpStorm.
 * User: wenquanliu
 * Date: 16/6/16
 * Time: 21:21
 */
function TransByte($size){
    $arr=array("B","KB","MB","GB","TB");
    $i=0;
    while($size>=1024){
        $size/=1024;
        $i++;
    }
    return round($size,2).$arr[$i];
}
//创建文件
function createFile($filename){
    //验证文件名是否合法
    $pattern="/[\/,\*,<>,\?,\|]/";
    if(preg_match($pattern,basename($filename))){
        return "非法文件名";
    }else{
        if(file_exists(strtolower($filename))){
            return "文件名已经存在,请重命名";

        }else {
            if (touch($filename)) {
                return "文件创建成功";
            }else{
                return "文件创建失败";
            }
        }

    }
}
function renameFile($oldname,$newname){
    if(checkFileName($newname)){
        $path=dirname($oldname);
        if(file_exists(basename($path."/".$newname))){
            return "文件名已存在,请重新命名!";
        }else{
            if(rename($oldname,$path."/".$newname)) {
                return "重命名成功";
            }else{
                return "重命名失败";
            }
        }
    }else{
        return "文件名不合法!";
    }
}
function checkFileName($filename){
    $pattern="/[\/,\*,<>,\?,\|]/";
    if(preg_match($pattern,basename($filename))) {
        return false;
    }else{
        return true;
    }
}
function delFile($filename){
    if(unlink($filename)){
    $mes="文件已经删除";
}else{
    $mes="文件删除失败,请重试";
}
    return $mes;
}

function downFile($filename){
    header("content-disposition:attachment;filename=".basename($filename));
    header("content-length:".filesize($filename));
    readfile($filename);
}
function copyFile($filename,$dstname){
    if(file_exists($dstname)){
        if(!file_exists($dstname."/".basename($filename))){
            if(copy($filename,$dstname."/".basename($filename))){
                $mes="复制成功";
            }else{
                $mes="复制失败";
            }
        }else{
            $mes="存在同名文件";
        }
    }else{
        $mes="目标目录不存在";
    }
    return $mes;
}
function cutFile($filename,$dstname){
    if(file_exists($dstname)){
        if(!file_exists($dstname."/".basename($filename))){
            if(rename($filename,$dstname."/".basename($filename))){
                $mes="剪切成功";
            }else{
                $mes="剪切失败";
            }
        }else{
            $mes="存在同名文件";
        }
    }else{
        $mes="目标目录不存在";
    }
    return $mes;
}
?>