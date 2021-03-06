<?php
/***************************************************
 * 数据来源白名单 *
 ***************************************************/
$accepted_origins = array("http://localhost", "http://tinymce.rzpt.com", "http://example.com");

/*********************************************
 * 设置图片保存的文件夹 *
 *********************************************/
$imageFolder = "images/";


reset ($_FILES);
$temp = current($_FILES);
if (!is_uploaded_file($temp['tmp_name'])){
  // 通知编辑器上传失败
  header("HTTP/1.1 500 Server Error");
  exit;
}

if (isset($_SERVER['HTTP_ORIGIN'])) {
  // 验证来源是否在白名单内
  if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
  } else {
    header("HTTP/1.1 403 Origin Denied");
    exit;
  }
}

/*
  如果脚本需要接收cookie，在init中加入参数 images_upload_credentials : true
  并加入下面两个被注释掉的header内容
*/
// header('Access-Control-Allow-Credentials: true');
// header('P3P: CP="There is no P3P policy."');
// 简单的过滤一下文件名是否合格
// if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
//     header("HTTP/1.1 400 Invalid file name.");
//     exit;
// }
// 验证扩展名
if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png","jpeg"))) {
    header("HTTP/1.1 400 Invalid extension.");
    exit;
}

// 都没问题，就将上传数据移动到目标文件夹，此处直接使用原文件名，建议重命名
$name = $temp['name'];
$time = date("YmdHis", time());
$filetowrite = $imageFolder . $time .iconv("UTF-8", "gb2312", $name);//上传是汉化,上传文件名
$filetowrite1 = $imageFolder . $time . $name;//源文件命名，存入数据库的名
$res = move_uploaded_file($temp['tmp_name'], $filetowrite);
if($res){
	
	// 返回JSON格式的数据
	// 形如下一行所示，使用location存放图片URL
	// { location : '/your/uploaded/image/file.jpg'}
	echo json_encode(array('location' => "demo/".$filetowrite1));
}else{
	echo "图片上传失败";exit;
}


