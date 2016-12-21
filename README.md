# omnipay-heepay
汇付宝使用接口

# 安装

```php
composer require hinet/omnipay-heepay
```

# 使用

```php
$gateway = Omnipay::create('Heepay');
$gateway->setPartner(config('payment.heepay.partner'));
$gateway->setKey(config('payment.heepay.KEY'));
$gateway->setDesKey(config('payment.heepay.DESKEY'));
$gateway->setNotifyurl(config('payment.heepay.notify'));
$order=array(
    'orderId'=>'B1218177936793836',         //订单id
    'orderTime'=>date('YmdHis', time()),    //提交时间
    'cardNum'=>'1111111111111111',          //卡号
    'cardPwd'=>'1111111111111111',          //卡密
    'amount'=>50,                           //重置金额
    'cardType'=>10                          //卡类型 
);
$request  = $gateway->purchase($order);
$response = $request->send();
return $response->isSuccessful();
```

# 异步通知回调

```php
$gateway = Omnipay::create('Heepay');
$options = ['request_params'=> $_REQUEST,];
$response = $gateway->completePurchase($options)->send();
if ($response->isSuccessful() && $response->getTransactionReference()) {
    //支付成功后获取订单id
    exit($response->getOrderId());
} else {
    //支付失败通知.
    exit('支付失败');
}
```
