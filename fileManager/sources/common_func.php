<?php
/**
 * Created by PhpStorm.
 * User: wenquanliu
 * Date: 16/6/17
 * Time: 21:11
 */
function alertMes($mes,$url){
    echo "<script type='text/javascript'>alert('{$mes}');location.href='{$url}';</script>";
}
function getUniqidName($length=10){
    return substr(md5(uniqid(microtime(true),true)),0,$length);
}
function getExt($filename){
    return strtolower(pathinfo($filename,PATHINFO_EXTENSION));
}
