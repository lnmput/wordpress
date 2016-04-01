<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/1 0001
 * Time: 上午 8:51
 *
 * 数据缓存,缓存到数据
 *
 * 默认缓存时间为一天
 *
 * yzcache   huan
 * yzcachetime
 */


function setCache($str){
    $file=wp_upload_dir();
    $filename=$file['path'].'/log.txt';
    $fileRe=file_put_contents($filename,$str);
    if(!$fileRe){
        echo("<script>console.log('cache failed');</script>");
    }else{
        echo("<script>console.log('cache success');</script>");
    }

}
function getCache(){
    $file=wp_upload_dir();
    $filename=$file['path'].'/log.txt';
    if(file_exists($filename)){
     $content= file_get_contents($filename);
     if($content){
         return $content;
     }else{
         return false;
     }
     }else{
        //echo "文件不存在";
        return false;
     }








}