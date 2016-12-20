<?php
/**
 * Created by PhpStorm.
 * file: HeepayResponse.php
 * project: omnipay-heepay
 * User: 李静
 * Date: 2016/12/20
 * Time: 9:44
 */

namespace Omnipay\Heepay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class HeepayResponse extends AbstractResponse
{
    private $apikey="";
    public static $MESSAGES=array(
        '0'=>'接收成功',
        '-1'=>'失败',
        '-2'=>'单据受理中',
        '1'=>'传入参数有误',
        '2'=>'代理商ID错误 或 未开通该服务',
        '3'=>'IP验证错误',
        '4'=>'签名验证错误',
        '5'=>'重复的订单号',
        '6'=>'卡加密错误',
        '7'=>'卡验证失败',
        '8'=>'单据不存在',
        '9'=>'卡号或密码不正确',
        '10'=>'卡中余额不足',
        '22'=>'卡号卡密格式加密错误',
        '98'=>'接口维护中',
    );
    public function __construct(RequestInterface $request,$data,$key)
    {
        $this->apikey=$key;
        parent::__construct($request, $data);
    }
    public function isSuccessful()
    {
        if ($this->data['resCode']==0){
            $sign=md5($this->data['resCode'].$this->data['orderId'].$this->apikey);
            return $sign==$this->data['md5String'];
        }else{
            return false;
        }
    }
    public function getOrderId(){
        return isset($this->data['orderId']) ? (string)$this->data['orderId'] : null;
    }
    public function getMessage()
    {
        $code=$this->data['resCode'];
        return isset(static::$MESSAGES[$code]) ? static::$MESSAGES[$code] : null;
    }
}