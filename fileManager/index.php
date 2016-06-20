<?php
/**
 * Created by PhpStorm.
 * User: wenquanliu
 * Date: 16/6/16
 * Time: 19:34
 */
$rootPath="../fileManager";
require_once('./sources/dir_func.php');
require_once('./sources/file_func.php');
require_once('./sources/common_func.php');
//$path="/Users/wenquanliu/Desktop/MyHtml/webstrom/website/justAweb/newfile";
$path=$rootPath;
$path=$_REQUEST['path']?$_REQUEST['path']:$path;
$act=$_REQUEST['act'];
$info=ReadDirectory($path);
$redirect="index.php?path={$path}";
//if(!$info){
//    echo "<script>alert('没有文件或文件目录不正确!!!');location.href='index.php';</script>";
//}
$filename=$_REQUEST['filename'];
if($act=="createFile"){
    $mes=createFile($path."/".$filename);
    alertMes($mes,$redirect);
}elseif($act=="showContent"){
    $content=file_get_contents($filename);
    if(strlen($content)){
        $newContent=highlight_string($content,true);
        $str=<<<EOF
    <table width='100%' bgcolor='pink' cellpadding='5' cellpacing="0">
    <tr>
    <td>{$newContent}</td>
    </tr>
    </table>
EOF;
        echo $str;
    }else{
        alertMes("文件为空",$redirect);
    }
}elseif($act=="editContent"){
    $content=file_get_contents($filename);
    $str=<<<EOF
    <form action='index.php?act=doEdit' method='post'>
        <textarea name='content' cols='190' rows='10'>{$content}</textarea><br/>
        <input type='hidden' name='filename' value="{$filename}"/>
        <input type='hidden' name='path' value="{$path}"/>
        <input type='submit' value="修改文件"/>
    </form>
EOF;
    echo $str;
}elseif($act=="doEdit"){
    $content=$_REQUEST['content'];
    if(file_put_contents($filename,$content)){
        $mes="文件修改成功";
    }else{
        $mes="文件修改失败";
    }
    alertMes($mes,$redirect);
}elseif($act=="renameFile"){
    $filename=$_REQUEST['filename'];
    $str=<<<EOF
    <form action='index.php?act=doRenameFile' method='post'>
    请输入新文件名(如果是文件需要添加文件后缀名):<input type="text" name="newname" placehoder="重命名"/>
    <input type="hidden" name="filename" value='{$filename}'/>
    <input type="hidden" name="path" value='{$path}'/>
    <input type="submit" value="重命名" />
   </form>
EOF;
    echo $str;
}elseif($act=="doRenameFile"){
    $newname=$_REQUEST['newname'];
    $mes=renameFile($filename,$newname);
    alertMes($mes,$redirect);
}elseif($act=="delFile"){
    $filename=$_REQUEST['filename'];
    $mes=delFile($filename);
    alertMes($mes,$redirect);
}elseif($act=="downFile"){
    $filename=$_REQUEST['filename'];
    downFile($filename);
}elseif($act=="创建文件夹"){
    $dirname=$_REQUEST['dirname'];
    $mes=createFolder($path."/".$dirname);
    alertMes($mes,$redirect);
}elseif($act=="copyFile"){
    $str=<<<EOF
    <form action='index.php?act=doCopyFile' method='post'>
    将文件复制到:<input type="text" name="dstname" placehoder="将文件复制到"/>
    <input type="hidden" name="filename" value="{$filename}"/>
    <input type="hidden" name="path" value="{$path}"/>
    <input type="submit" value="复制文件" />
   </form>
EOF;
    echo $str;
}elseif($act=="doCopyFile"){
    $dstname=$_REQUEST['dstname'];
    $mes=copyfile($filename,$path."/".$dstname);
    alertMes($mes,$redirect);
}elseif($act=="copyFolder"){
    $str=<<<EOF
    <form action='index.php?act=doCopyFolder' method='post'>
    将文件夹复制到:<input type="text" name="dstname" placehoder="将文件夹复制到"/>
    <input type="hidden" name="filename" value="{$filename}"/>
    <input type="hidden" name="path" value="{$path}"/>
    <input type="submit" value="复制文件夹" />
   </form>
EOF;
    echo $str;
}elseif($act=="doCopyFolder"){
    $dstname=$_REQUEST['dstname'];
    $dirname=$filename;
   $mes=copyFolder($dirname,$path."/".$dstname."/".basename($dirname));
   alertMes($mes,$redirect);
}elseif($act=="cutFile"){
    $str=<<<EOF
    <form action='index.php?act=doCutFile' method='post'>
    将文件剪切到:<input type="text" name="dstname" placehoder="将文件剪切到"/>
    <input type="hidden" name="filename" value="{$filename}"/>
    <input type="hidden" name="path" value="{$path}"/>
    <input type="submit" value="剪切文件" />
   </form>
EOF;
    echo $str;
}elseif($act=="doCutFile"){
    $dstname=$_REQUEST['dstname'];
    $mes=cutFile($filename,$path."/".$dstname);
    alertMes($mes,$redirect);
}elseif($act=="delFolder"){
    $filename=$_REQUEST['filename'];
    $mes=delFolder($filename);
    alertMes($mes,$redirect);
}elseif($act=="上传文件"){
    $fileinfo=$_FILES['myFile'];
    //print_r($fileinfo);
    $mes=upLoadFile($fileinfo,$path);
    alertMes($mes,$redirect);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-CN">
<head>
    <meta charset="UTF-8" />
    <title>FIleManager</title>
    <link rel="stylesheet" href="cikonss.css" />
    <script src="jquery-ui/js/jquery-1.10.2.js"></script>
    <script src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
    <link rel="stylesheet" href="jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css"  type="text/css"/>
    <style type="text/css">
        body,p,div,ul,ol,table,dl,dd,dt{
            margin:0;
            padding: 0;
        }
        a{
            text-decoration: none;
        }
        ul,li{
            list-style: none;
            float: left;
        }
        #top{
            width:100%;
            height:48px;
            margin:0 auto;
            background: #E2E2E2;
        }
        #navi a{
            display: block;
            width:48px;
            height: 48px;
        }
        #main{
            margin:0 auto;
            border:2px solid #ABCDEF;
        }
        .small{
            width:25px;
            height:25px;
            border:0;
        }
        h1{
            text-align: center;
            margin: 0 auto;
        }
    </style>
    <script type="text/javascript">
        function show(dis){
            document.getElementById(dis).style.display="block";
        }
        function delFile(filename,path){
            if(window.confirm("您确定要删除嘛?删除之后无法恢复哟!!!")){
                location.href="index.php?act=delFile&filename="+filename+"&path="+path;
            }
        }
        function delFolder(filename,path){
            if(window.confirm("您确定要删除嘛?删除之后无法恢复哟!!!")){
                location.href="index.php?act=delFolder&filename="+filename+"&path="+path;
            }
        }
        function showDetail(t,filename){
            $("#showImg").attr("src",filename);
            $("#showDetail").dialog({
                height:"auto",
                width: "auto",
                position: {my: "center", at: "center",  collision:"fit"},
                modal:false,//是否模式对话框
                draggable:true,//是否允许拖拽
                resizable:true,//是否允许拖动
                title:t,//对话框标题
                show:"slide",
                hide:"explode"
            });
        }
        function goBack($back){
            location.href="index.php?path="+$back;
        }
    </script>
</head>

<body>
<div id="showDetail"  style="display:none"><img src="" id="showImg" alt=""/></div>
<h1>在线文件管理器</h1>
<div id="top">
    <ul id="navi">
        <li><a href="index.php" title="主目录"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-home"></span></span></a></li>
        <li><a href="#"  onclick="show('createFile')" title="新建文件" ><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-file"></span></span></a></li>
        <li><a href="#"  onclick="show('createFolder')" title="新建文件夹"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-folder"></span></span></a></li>
        <li><a href="#" onclick="show('uploadFile')"title="上传文件"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-upload"></span></span></a></li>
        <?php
        $back=($path==$rootPath)?$rootPath:dirname($path);
        ?>
        <li><a href="#" title="返回上级目录" onclick="goBack('<?php echo $back;?>')"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-arrowLeft"></span></span></a></li>
    </ul>
</div>
<form action="index.php" method="post" enctype="multipart/form-data">
    <table width="100%" border="1" cellpadding="5" cellspacing="0" bgcolor="#ABCDEF" align="center">
        <tr id="createFolder"  style="display:none;">
            <td>请输入文件夹名称</td>
            <td >
                <input type="text" name="dirname" />
                <input type="hidden" name="path"  value="<?php echo $path;?>"/>
                <input type="submit"  name="act"  value="创建文件夹"/>
            </td>
        </tr>
        <tr id="createFile"  style="display:none;">
            <td>请输入文件名称</td>
            <td >
                <input type="text"  name="filename" />
                <input type="hidden" name="path" value="<?php echo $path;?>"/>
                <input type="submit"  name="act" value="createFile" />
            </td>
        </tr>
        <tr id="uploadFile" style="display:none;">
            <td >请选择要上传的文件</td>
            <td ><input type="file" name="myFile" />
                <input type="hidden" name="path" value="<?php echo $path ?>"/>
                <input type="submit" name="act" value="上传文件" />
            </td>
        </tr>
        <tr>
            <td>编号</td>
            <td>名称</td>
            <td>类型</td>
            <td>大小</td>
            <td>可读</td>
            <td>可写</td>
            <td>可执行</td>
            <td>创建时间</td>
            <td>修改时间</td>
            <td>访问时间</td>
            <td>操作</td>
        </tr>
<?php
if($info['file']){
    $i=1;
    foreach($info['file'] as $val){
        $p=$path."/".$val;
?>
    <tr>
        <td><?php echo $i?></td>
        <td><?php echo $val;?></td>
        <td><?php $src=filetype($p)=="file"?"file_ico.png":"folder_ico.png"; ?><img src="images/<?php echo $src;?>" alt="" title="文件"/></td>
        <td><?php echo TransByte(filesize($p));?></td>
        <td><?php $src=is_readable($p)?"correct.png":"error.png";?><img class="small" src="images/<?php echo $src;?>"alt=""  /></td>
        <td><?php $src=is_writable($p)?"correct.png":"error.png";?><img class="small" src="images/<?php echo $src;?>"alt=""  /></td>
        <td><?php $src=is_executable($p)?"correct.png":"error.png";?><img class="small" src="images/<?php echo $src;?>"alt=""  /></td>
        <td><?php echo date("Y-m-d H:i:s",filectime($p))?></td>
        <td><?php echo date("Y-m-d H:i:s",filemtime($p))?></td>
        <td><?php echo date("Y-m-d H:i:s",fileatime($p))?></td>
        <td>
            <?php
            $temp_ext=explode(".",$val);
            $ext=strtolower(end($temp_ext));

            //$ext=strtolower(end(explode(".",$val)));
            $imgExt=array("gif","jpg","png","jpeg");
            if(in_array($ext,$imgExt)){
                ?>
                <a href="#" onclick="showDetail('<?php echo $val;?>','<?php echo $p;?>')" ><img class="small" src="images/show.png" alt=" " title="查看"/> </a>
            <?php
            }else {
            ?>
            <a href="index.php?act=showContent&path=<?php echo $path; ?>&filename=<?php echo $p; ?>"><img class="small" src="images/show.png" alt=""/></a>
            <?php } ?>
            <a href="index.php?act=editContent&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/edit.png" alt=""></a>
            <a href="index.php?act=renameFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/rename.png" alt=""></a>
            <a href="index.php?act=copyFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/copy.png" alt=""></a>
            <a href="index.php?act=cutFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/cut.png" alt=""></a>
            <a href="#" onclick='delFile("<?php echo $p;?>","<?php echo $path;?>")'><img class="small" src="images/delete.png" alt=""/></a>
            <a href="index.php?act=downFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/download.png" alt=""></a>
        </td>
    </tr>
<?php
        $i++;
    }
}
?>
        <?php
        if($info['dir']){
            foreach($info['dir'] as $val){
                $p=$path."/".$val;
                ?>
                <tr>
                    <td><?php echo $i?></td>
                    <td><?php echo $val;?></td>
                    <td><?php $src=filetype($p)=="file"?"file_ico.png":"folder_ico.png"; ?><img src="images/<?php echo $src;?>" alt="" title="文件"/></td>
                    <td><?php $sum=0;echo TransByte(dirSize($p));?></td>
                    <td><?php $src=is_readable($p)?"correct.png":"error.png";?><img class="small" src="images/<?php echo $src;?>"alt=""  /></td>
                    <td><?php $src=is_writable($p)?"correct.png":"error.png";?><img class="small" src="images/<?php echo $src;?>"alt=""  /></td>
                    <td><?php $src=is_executable($p)?"correct.png":"error.png";?><img class="small" src="images/<?php echo $src;?>"alt=""  /></td>
                    <td><?php echo date("Y-m-d H:i:s",filectime($p))?></td>
                    <td><?php echo date("Y-m-d H:i:s",filemtime($p))?></td>
                    <td><?php echo date("Y-m-d H:i:s",fileatime($p))?></td>
                    <td>

                        <a href="index.php?path=<?php echo $p; ?>"><img class="small" src="images/show.png" alt=""/></a>
                        <a href="index.php?act=renameFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/rename.png" alt=""></a>
                        <a href="index.php?act=copyFolder&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/copy.png" alt=""></a>
                        <a href="index.php?act=cutFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/cut.png" alt=""></a>
                        <a href="#" onclick='delFolder("<?php echo $p;?>","<?php echo $path;?>")'><img class="small" src="images/delete.png" alt=""/></a>
                        <a href="index.php?act=downFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/download.png" alt=""></a>
                    </td>
                </tr>
         <?php
         $i++;
            }
        }
        ?>
</table>
</form>
</body>
</html>