<?php
/**
 * Created by PhpStorm.
 * User: playboy
 * Date: 15/11/11
 * Time: 18:53
 */
header('content-type:text/html;charset=utf8');

echo crypt('shougongke','erdong')."<br>";

echo sha1('shougongke');

echo '<br>';

if($_GET){
    print_r($_GET);
}
$param = 'username=erdong&hdfndf&uid=165191';
$param = urlencode($param);
echo "<a href='jiami.php?".$param."'>哈哈</a>";

//echo "<img src='1.jpg' />";
echo '<br>';
$content = file_get_contents('1.jpg');
$pic = base64_encode($content);
echo $pic;