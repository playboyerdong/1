<?php
/**
 * Created by PhpStorm.
 * User: playboy
 * Date: 15/10/31
 * Time: 22:12
 */

//采用预处理＋事务处理执行SQL语句

//连接数据库
try{
    $pdo = new PDO("mysql:host=localhost;dbname=test","root","123456");
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die('数据库连接失败'.$e->getMessage());
}

//执行数据操作，要么全部插入成功，要么全部失败
/*try{
    $pdo->beginTransaction();//开启事务
    $sql = "insert into user (uid,uname) VALUES (?,?)";
    $stmt = $pdo->prepare($sql);
    //传入参数
    $stmt->execute(array(null,'erdong4'));
    $stmt->execute(array(null,'erdong5'));
    $stmt->execute(array(null,'erdong6'));
    //提交事务
    $pdo->commit();
}catch(PDOException $e){
    die("添加用户失败".$e->getMessage());
    //事务回滚
    $pdo->roolback();
}*/
try{
    //开启事务
    $pdo->beginTransaction();
    $sql = "update user set uname = ? where uid = ?";
    //准备SQL语句
    $stmt = $pdo->prepare($sql);
    //传入参数
    $stmt->execute(array('erdong13',2));
    $stmt->execute(array('erdong1',3));
    //提交事务
    $pdo->commit();
}catch(PDOException $e){
    die("修改信息失败".$e->getMessage());
    //事务回滚
    $pdo->roolback();
}
