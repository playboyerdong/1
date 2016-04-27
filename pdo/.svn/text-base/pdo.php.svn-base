<?php
/**
 * Created by PhpStorm.
 * User: playboy
 * Date: 15/10/31
 * Time: 19:41
 */
header('Content-type: text/html; charset=utf-8');
//pdo的几种异常处理模式  默认是不显示的，需要errorCode()和errorInfo()来实现
try{
    $pdo = new PDO('mysql:host=localhost;dbname=test','root','123456');
    //$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING); //警告的方式
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); //获取错误信息的方式
}catch (PDOException $e){
    die('数据库连接失败'.$e->getMessage());
}
$sql = "insert into user1 VALUES (NULL ,'erdong')";

try{
    $result = $pdo->exec($sql);
}catch(PDOException $e){
    echo $e->getMessage();
}
//if($result){
  //  echo 'OK';
//}else{
    //echo $pdo->errorCode();
    //print_r($pdo->errorInfo());
//}