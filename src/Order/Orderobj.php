<?php
namespace RoseKnife\Bianwoyou\Order;

/**
 * Created by PhpStorm.
 * User: RoseKnife Hua
 * Date: 2018/5/25 0025
 * Time: 17:13
 */

class Orderobj
{

    /**
     * @var第三方产品名称
     */
    public $thirdProdName="";

    /**
     * @var联系人电话
     */
    public $linkManPhone="";

    /**
     * @var门票类型
     */
    public $ticketType;

    /**
     * @var单价
     */
    public $price;

    /**
     * @var联系人身份证
     */
    public $linkManCardNo="";

    /**
     * @var订单金额
     */
    public $amount;

    /**
     * @var商品编码
     */
    public $productCode;

    /**
     * @var是否发短信
     */
    public $needSms=0;

    /**
     * @var联系人姓名
     */
    public $linkManName="";

    /**
     * @var第三方订单号
     */
    public $thirdSn;

    /**
     * @var产品名称
     */
    public $productName;

    /**
     * @var出行日期
     */
    public $date;

    /**
     * @var数量
     */
    public $quantity;

}
