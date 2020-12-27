<?php
/**
 * Created by PhpStorm.
 * file: HeepayCompletePurchaseRequest.php
 * project: omnipay-heepay
 * User: ChingLi
 * Date: 2016/12/20
 * Time: 15:43
 */

namespace Omnipay\Heepay\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class HeepayCompletePurchaseRequest extends HeepayPurchaseRequest
{
    /**
     * 异步回调请求
     * @return array
     * @throws InvalidResponseException
     * @author ChingLi
     */
    public function getData()
    {
        //回调数据传值类型以及传输方式未知，暂时以get链接访问代替
        //$options = ['request_params'=> $this->httpRequest->request->all()];  //POST回调数据
        //模拟数据：ret_code=4&agent_id=2017851&bill_id=B1218177936793836&jnet_bill_no=&bill_status=1&card_real_amt=&card_settle_amt=&card_detail_data=&ret_msg=签名验证错误&ext_param=&sign=ee112bf3fbba0ee617c067d5e16d8863
        $request_params=$_REQUEST;  //GET回调数据
        if ($request_params['agent_id'] !== session('agent_id') && $request_params['bill_id'] !== session('bill_id')) {
            throw new InvalidResponseException('Invalid md5String');
        }
        //return $this->httpRequest->request->all();
        return $_REQUEST;
    }

    /**
     * 发送回调的数据
     * @param mixed $data
     * @return HeepayCompletePurchaseResponse
     * @author ChingLi
     */
    public function sendData($data)
    {
        return $this->response = new HeepayCompletePurchaseResponse($this, $data);
    }
}