<?php
/**
 * Created by PhpStorm.
 * User: playboy
 * Date: 15/11/6
 * Time: 00:17
 */
//学习redis的del命令
$redis = new Redis();
$redis->connect('192.168.1.110',6379);
echo $redis->incr('count').'<br>';
if($redis->get('count') > 5){
    $redis->getSet('count',0);
}
echo $redis->get('count');
//删除单个key
$redis->set('myname','playboy');
echo $redis->get('myname')."<br>";  //返回playboy

$redis->del('myname'); //返回true
var_dump($redis->get('myname')); //返回false
echo "<br>";

//删除一个不存在的KEY
if(!$redis->exists('fake_')){ //不存在
    var_dump($redis->del('fake_')); //返回int(0)
}
echo "<br>";

//同时删除多个key
$array = array('first_key'=>'first_value','second_key'=>'second_value','third_key'=>'third_value');
$redis->mset($array); //用mset一次存储多个值
$array_get = array('first_key','second_key','third_key');
var_dump($redis->mget($array_get));//一次返回多个值array(3) { [0]=> string(11) "first_value" [1]=> string(12) "second_value" [2]=> string(11) "third_value" }
echo "<br>";
$redis->del($array_get); //同时删除多个key
var_dump($redis->mget($array_get)); //返回array(3) { [0]=> bool(false) [1]=> bool(false) [2]=> bool(false) }



