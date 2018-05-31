<?php
/**
 * Created by PhpStorm.
 * User: RoseKnife Hua
 * Date: 2018/5/25 0025
 * Time: 18:01
 */
include_once "src/Lite.php";
include_once "src/Order/Orderobj.php";

$config = [
    'appid' => '111222333',
    'secret' => 'asefsdfsdf',
    'apiurl' => 'http://api.ytxapp.com/service/rest/haoxing/'
];


$o = new RoseKnife\Bianwoyou\Order\Orderobj();
$o->thirdSn = "222333";


$l = new RoseKnife\Bianwoyou\Lite($config);
print_r($l->postOrder($o));