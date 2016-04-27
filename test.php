<?php
$str = '\Handler\TestHandler.php';
$need = ['\\', '\\'];
$new_need = [0,1];
$result = str_replace($need, $new_need, $str);
echo $result;
