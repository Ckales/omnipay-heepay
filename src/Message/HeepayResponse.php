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
    function __construct(RequestInterface $request,$data,$key)
    {
        $this->apikey = $key;
        $data=mb_convert_encoding($data,'UTF-8','GB2312');
        parent::__construct($request,$data);
    }
    public function isSuccessful()
    {
        parse_str($this->data);//将返回结果存储到变量
        if($ret_code == 0){
            return true;
        }else{
            return mb_convert_encoding($ret_msg,'utf-8' );
        }
    }
    public function getError($code){
        switch ($code) {
            case '0':
                $_status = '接收成功';
                break;
            case '-1':
                $_status = '失败';
                break;
            case '-2':
                $_status = '单据受理中';
                break;
            case '1':
                $_status = '传入参数有误';
                break;
            case '2':
                $_status = '代理商ID错误 或 未开通该服务';
                break;
            case '3':
                $_status = 'IP验证错误';
                break;
            case '4':
                $_status = '签名验证错误';
                break;
            case '5':
                $_status = '重复的订单号';
                break;
            case '6':
                $_status = '卡加密错误';
                break;
            case '7':
                $_status = '卡验证失败';
                break;
            case '8':
                $_status = '单据不存在';
                break;
            case '9':
                $_status = '卡号或密码不正确';
                break;
            case '10':
                $_status = '卡中余额不足';
                break;
            case '22':
                $_status = '卡号卡密格式加密错误';
                break;
            case '98':
                $_status = '接口维中';
                break;
            default:
                $_status = '系统错误,未知';
                break;
        }
        return $_status;
    }
}