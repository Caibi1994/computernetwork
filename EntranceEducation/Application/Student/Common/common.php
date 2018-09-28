<?php
//文件命名应该是 function.php


function getOpenId(){
    $openId = I('openId');
    if(empty($openId)){
        echo "<script>alert('你访问的页面不存在');</script>";
        exit();
    }
    return $openId;
}

