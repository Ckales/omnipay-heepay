<?php
/**
 * Created by PhpStorm.
 * file: HeepayPurchaseRequest.php
 * project: omnipay-heepay
 * User: 李静
 * Date: 2016/12/20
 * Time: 10:38
 */

namespace Omnipay\Heepay\Message;

use Omnipay\Common\Message\AbstractRequest;

class HeepayPurchaseRequest extends AbstractRequest
{
    protected $endpoint="https://pay.Heepay.com/Api/CardPaySubmitService.aspx";
    public function getData()
    {
        // TODO: Implement getData() method.
        $this->validate(
            'orderId',
            'amount'
        );
        $data=array(
            'merId'         =>  $this->getPartnerId(),
            'payMoney'      =>  $this->getAmount(),
            'orderId'       =>  $this->getOrderId(),
            'returnUrl'     =>  $this->getNotifyUrl(),
            'merUserName'   =>  $this->getUserName(),
            'merUserMail'   =>  $this->getEmail(),
            'verifyType'    =>  $this->getVerifyType(),
        );
        //过滤数组为空值
        $data = array_filter($data);
        $data['privateField'] = $this->getPrivateField();//privateField是空值也要传递，所在放在外面
//        $data['md5String'] = Helpers::sign($data, $this->getApiKey());
        return $data;
    }
    public function getPartnerId()
    {
        return $this->getParameter('partnerId');
    }
    public function setPartnerId($value)
    {
        return $this->setParameter('partnerId', $value);
    }
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }
    public function getAmount()
    {
        return $this->getParameter('amount');
    }
    public function setAmount($value)
    {
        return $this->setParameter('amount',$value);
    }
    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }
    public function setOrderId($value)
    {
        return $this->setParameter('orderId',$value);
    }
    public function getNotifyUrl()
    {
        return $this->getParameter('notifyUrl');
    }
    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyUrl', $value);
    }
    public function getUserName()
    {
        return $this->getParameter('userName');
    }
    public function setUserName($value)
    {
        return $this->setParameter('userName',$value);
    }
    public function getEmail()
    {
        return $this->getParameter('email');
    }
    public function setEmail($value)
    {
        return $this->setParameter('email',$value);
    }
    public function getPrivateField(){
        return $this->getParameter('privateField');
    }
    public function setPrivateField($value){
        return $this->setParameter('privateField',$value);
    }
    public function getVerifyType(){
        return $this->getParameter('verifyType') ? $this->getParameter('verifyType') : 1;
    }
    public function setVerifyType($value){
        return $this->setParameter('verifyType',$value);
    }
    public function sendData($data)
    {
        $request = $this->httpClient->post($this->getEndpoint(), ["Content-type"=>"text/html; charset=utf-8"], $data);
        $reponse = $request->send();
        return $this->response = new HeepayResponse($this, $reponse->json(),$this->getApiKey());
    }
    public function getEndPoint(){
        return $this->endpoint ? $this->endpoint : null;
    }
}