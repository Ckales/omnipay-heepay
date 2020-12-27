<?php
/**
 * Created by PhpStorm.
 * file: HeepayCompletePurchaseResponse.php
 * 异步通知接收响应.
 * project: omnipay-heepay
 * User: ChingLi
 * Date: 2016/12/20
 * Time: 15:35
 */

namespace Omnipay\Heepay\Message;

use Omnipay\Common\Message\AbstractResponse;

class HeepayCompletePurchaseResponse extends AbstractResponse
{
    /**
     * 根据回调数据判断订单状态是否成功
     * @return bool true|false
     * @author ChingLi
     */
    public function isSuccessful()
    {
        // TODO: Implement isSuccessful() method.
        //根据订单状态判断是否支付成功
        if($this->data['bill_status'] == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return null|string  订单id
     * @author ChingLi
     */
    public function getOrderId()
    {
        return isset($this->data['bill_id'])?$this->data['bill_id']:null;
    }

    /**
     * 订单状态
     * @return null|string 订单状态
     * @author ChingLi
     */
    public function getTransactionReference()
    {
        return isset($this->data['bill_status']) ? $this->data['bill_status'] : null;
    }
}