<?php

namespace RoseKnife\Bianwoyou;

/**
 * Created by PhpStorm.
 * User: RoseKnife Hua
 * Date: 2018/5/25 0025
 * Time: 17:03
 */


class Lite
{
    private $APPID = "";
    private $SECRET = "";
    private $APIURL = "";

    /**
     * Lite 构造.
     * @param $config
     */
    public function __construct($config)
    {
        $this->APPID = $config['appid'];
        $this->SECRET = $config['secret'];
        $this->APIURL = $config['apiurl'];
    }

    /**
     * 好行下单
     * @param 订单数据对象
     * 接口说明
     * 票务统一下单接口，下单时需要根据“产品列表”接口获取产品的编码，进行下单。
     * 参数说明
     * needSms:1需要发短信，0不发短信
     * quantity：电子票每次只能购买3张，实体票100张
     * thirdSn：传你们自己的订单号，用于判重
     * date：出行日期，不能小于下单当天
     * ticketType：门票类型，电子门票1，实体门票2，巴士票4（产品列表可以查看门票的类型）
     * 返回值说明
     * sn:code值为1时才有值，返回商品的订单号，退票时需要使用
     * code：1下单成功，1005联系人信息有误请检查联系人相关信息，1007订单金额有误，1008商品不存在（请检查商品编码是否存在于商品列表），1009订单已经存在，
     */
    public function postOrder($object)
    {
        $objData = array_filter((array)$object); //转数组移除空值
        $data = $this->createSign($objData);
        return $this->postData($this->APIURL . "ordercreate", $data);
    }

    /**
     * 查询苏州好行门票产品信息
     * 返回值说明
     * sn：产品编码，下单时需要对应具体产品
     * productName：产品名称
     * type:门票类型，1电子票，2实体票，4巴士票
     * code：接口状态，1成功
     */
    public function getProductList()
    {
        $data = $this->createSign(array());
        return $this->postData($this->APIURL . "hxproductlist", $data);
    }

    /**
     * 处理订单退票，只支持整单退票，如果订单中存在已经使用的门票则无法成功退票。
     * 参数说明
     * sn：订单号，下单时会返回请保存。
     * 返回值说明
     * code：1退票成功，1001订单不存在，1004退票失败，1003订单不是已出票状态
     */
    public function orderRefund($tid)
    {
        $data = $this->createSign(array("sn" => $tid));
        return $this->postData($this->APIURL . "orderrefund", $data);
    }

    /**
     * 查询订单状态信息
     * 参数说明
     * sn：订单号
     * 返回值
     * code：1成功，1001订单不存在
     * sn：订单号
     * orderStatus：订单状态；2已支付(纸质票可以使用了)3出票成功，4出票失败，5已取票(实体票、巴士)，9已检票(电子票),10部分检票
     * item:[
     * {fullName:票型名称,
     * status:订单状态,3出票成功，4出票失败，5已取票(实体票、巴士)，9已检票(电子票)，0未检票/未取票
     * serialid:取票号}
     * ]
     */
    public function orderStatus($tid)
    {
        $data = $this->createSign(array("sn" => $tid));
        return $this->postData($this->APIURL . "orderstatus", $data);
    }

    /**
     * 生成包含签名的数据
     */
    private function createSign($arr)
    {
        $signData = array(
            "appid" => $this->APPID,
            "nonce" => rand(100000, 999999),
            "timestamp" => $this->getMillisecond(),
            "secret" => $this->SECRET,
        );
        $signData["sign"] = md5($signData['appid'] . $signData['nonce'] . $signData['timestamp'] . $signData['secret']);
        return array_merge($signData, $arr);
    }

    /**
     * @return 生成13位时间
     */
    private function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    /**
     * 发送请求
     * @param $url
     * @param $data
     * @return mixed
     */
    private function postData($url, $data)
    {
        $ch = curl_init();
        $timeout = 300;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        $handles = curl_exec($ch);
        curl_close($ch);
        return $handles;
    }
}