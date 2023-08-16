<?php
/**
 * Created by PhpStorm.
 * file: HeepayPurchaseRequest.php
 * project: omnipay-heepay
 * User: ChingLi
 * Date: 2016/12/20
 * Time: 10:38
 */

namespace Omnipay\Heepay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Heepay\Crypt3Des;
use Request;

class HeepayPurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://pay.Heepay.com/Api/CardPaySubmitService.aspx';   //汇付宝请求地址

    /**
     * 获取数据，进行数据整合
     * @return array
     * @author ChingLi
     */
    public function getData()
    {
        $this->validate(
            'orderId',
            'orderTime',
            'cardNum',
            'cardPwd',
            'amount',
            'cardType'
        );

        $data = [
            'agent_id'      => $this->getPartner(),
            'bill_id'       => $this->getOrderId(),
            'bill_time'     => date('YmdHis', time()),
            'card_type'     => $this->getCardType(),
            'pay_amt'       => $this->getAmount(),
            'qq'            =>  '',
            'email'         =>  '',
            'client_ip'     => Request::getClientIp(),
            'notify_url'    => $this->getNotifyurl(),
            'desc'          => $this->getDesc(),
            'ext_param'     => '',
            'time_stamp'    => date('YmdHis', time()),
        ];

        //过滤数组为空值
        $key=$this->getKey();
        $deskey= $this->getDeskey();
        $carnum=$this->getCardNum();
        $password=$this->getCardPwd();
        $amount=$this->getAmount();

        $rep = new Crypt3Des(); // 初始化一个对象
        $rep ->key = $deskey;
        $card = "$carnum,$password,$amount";
        $card_data = $rep->encrypt($card);//一卡通卡号密码|最多支持3张一卡通,格式为：卡号1,密码1,金额1|卡号2，密码2,金额2|卡号3，密码3，金额3）
        $data['card_data'] = $card_data;

        //获取签名
        $signStr  = '';
        $signStr  .= 'agent_id=' . $this->getPartner();
        $signStr  .= '&bill_id=' . $this->getOrderId();
        $signStr  .= '&bill_time=' . $this->getOrderTime();
        $signStr  .= '&card_type=' . $this->getCardType();
        $signStr  .= '&card_data=' . $card_data;
        $signStr  .= '&pay_amt=' . $this->getAmount();
        $signStr  .=  '&notify_url=' . $this->getNotifyurl();
        $signStr  .= '&time_stamp=' . date('YmdHis', time());
        $signStr  .= '|||' . $key;
        $sign=md5($signStr);
        $data['sign']= $sign;
        //暂时将回调校验值存储session，因为在回调时用$this->getPartner()无法获取到值
        session(['agent_id'=>$this->getPartner()]);
        session(['bill_id'=>$this->getOrderId()]);
        return $data;
    }

    /**
     * 发送请求
     * @param $data
     * @return HeepayResponse
     * @author ChingLi
     */
    public function sendData($data){
        $request = $this->httpClient->post($this->getEndpoint(), ["Content-type"=>"text/html; charset=utf-8"], $data);
        $reponse = $request->send();
        return $this->response = new HeepayResponse($this, $reponse->getBody(true),$this->getKey());
    }

    /**
     * 设置订单号
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setOrderId($value){
        return $this->setParameter('orderId',$value);
    }

    /**
     * 设置订单时间
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setOrderTime($value){
        return $this->setParameter('orderTime',$value);
    }

    /**
     * 设置卡号
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setCardNum($value){
        return $this->setParameter('cardNum',$value);
    }

    /**
     * 设置卡密
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setCardPwd($value){
        return $this->setParameter('cardPwd',$value);
    }

    /**
     * 设置金额
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setAmount($value){
        return $this->setParameter('amount',$value);
    }

    /**
     * 设置卡类型
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setCardType($value){
        return $this->setParameter('cardType',$value);
    }

    /**
     * 设置key
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setKey($value){
        return $this->setParameter('key',$value);
    }

    /**
     * 设置key
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setDesKey($value){
        return $this->setParameter('deskey',$value);
    }

    /**
     * 设置Partner
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setPartner($value){
        return $this->setParameter('partner',$value);
    }

    /**
     * 设置回调url
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setNotifyurl($value){
        return $this->setParameter('notifyurl',$value);
    }

    /**
     * 设置描述
     * @param $value
     * @return mixed
     * @author ChingLi
     */
    public function setDesc($value){
        return $this->setParameter('desc',$value);
    }

    /**
     * 获取订单号
     * @return mixed
     * @author ChingLi
     */
    public function getOrderId(){
        return $this->getParameter('orderId');
    }

    /**
     * 获取订单时间
     * @return mixed
     * @author ChingLi
     */
    public function getOrderTime(){
        return $this->getParameter('orderTime');
    }

    /**
     * 获取卡号
     * @return mixed
     * @author ChingLi
     */
    public function getCardNum(){
        return $this->getParameter('cardNum');
    }

    /**
     * 获取卡密
     * @return mixed
     * @author ChingLi
     */
    public function getCardPwd(){
        return $this->getParameter('cardPwd');
    }

    /**
     * 获取卡类型
     * @return mixed
     * @author ChingLi
     */
    public function getCardType(){
        return $this->getParameter('cardType');
    }

    /**
     * 获取金额
     * @return mixed
     * @author ChingLi
     */
    public function getAmount(){
        return $this->getParameter('amount');
    }

    /**
     * 获取key
     * @return mixed
     * @author ChingLi
     */
    public function getKey(){
        return $this->getParameter('key');
    }

    /**
     * 获取DesKey
     * @return mixed
     * @author ChingLi
     */
    public function getDesKey(){
        return $this->getParameter('deskey');
    }

    /**
     * 获取Partner
     * @return mixed
     * @author ChingLi
     */
    public function getPartner(){
        return $this->getParameter('partner');
    }

    /**
     * 回去回调url
     * @return mixed
     * @author ChingLi
     */
    public function getNotifyurl(){
        return $this->getParameter('notifyurl');
    }

    /**
     * 获取描述
     * @return mixed
     * @author ChingLi
     */
    public function getDesc(){
        return $this->getParameter('desc');
    }

    /**
     * 获取终端
     * @return mixed|string|null
     * @author ChingLi
     */
    public function getEndPoint(){
        return $this->endpoint ?: null;
    }
}