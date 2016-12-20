<?php
/**
 * Created by PhpStorm.
 * file: HeepayCompletePurchaseResponse.php
 * 异步通知接收响应.
 * project: omnipay-heepay
 * User: 李静
 * Date: 2016/12/20
 * Time: 15:35
 */

namespace Omnipay\Heepay\Message;

use Omnipay\Common\Message\AbstractResponse;

class HeepayCompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        // TODO: Implement isSuccessful() method.
        //支付成功
        if($this->data['payResult'] == 1){
            return true;
        }else{
            return false;
        }
    }
    public function getOrderId()
    {
        return isset($this->data['orderId'])?$this->data['orderId']:null;
    }
    public function getTransactionReference()
    {
        return isset($this->data['payResult']) ? $this->data['payResult'] : null;
    }
}