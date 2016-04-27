<?php
/**
 * Created by PhpStorm.
 * User: playboy
 * Date: 15/11/7
 * Time: 22:20
 */

//学习php的命名空间

//创建一个名为Lakers的命名空间
namespace Lakers;
const name = 'kobe';
class playball{

}
function getReb(){
    return 10;
}

//创建一个名为Houston的命名空间
namespace Houston;
const name = 'haden';
class playball{

}
function getReb(){
    return 5;
}

$houston_playball = new playball();
echo name;
echo getReb();

echo '<br>';

$lakers_playball = new \Lakers\playball();
echo \Lakers\name;
echo \Lakers\getReb();

