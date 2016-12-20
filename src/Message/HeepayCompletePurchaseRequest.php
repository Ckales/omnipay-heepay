<?php
/**
 * Created by PhpStorm.
 * file: HeepayCompletePurchaseRequest.php
 * project: omnipay-heepay
 * User: 李静
 * Date: 2016/12/20
 * Time: 15:43
 */

namespace Omnipay\Heepay\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class WechatCompletePurchaseRequest extends HeepayPurchaseRequest
{
    public function getData()
    {
        $orderId = $this->httpRequest->request->get('orderId');
        $payResult = $this->httpRequest->request->get('payResult');
        $payDetails = $this->httpRequest->request->get('payDetails');
        $sign=md5($this->getPartnerId().$this->getAmount().$this->getOrderId().$payResult.$this->getPrivateField().$payDetails.$this->getApiKey());
        if ($this->httpRequest->request->get('md5String') !== $sign) {
            throw new InvalidResponseException('Invalid md5String');
        }
        return $this->httpRequest->request->all();
    }
    public function sendData($data)
    {
        return $this->response = new HeepayCompletePurchaseResponse($this, $data);
    }
}