<?php

namespace Omnipay\Momo\Message;

use Cake\Chronos\Chronos;
use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{
    const TIME_ZONE = 'Asia/Ho_Chi_Minh';
    private $endPointProduction = 'https://payment.momo.vn';
    private $endPointSandbox = 'https://test-payment.momo.vn';

    public function getData()
    {
        $this->validate(
            'partnerCode',
            'accessKey',
            'amount',
            'transactionId', // orderId
            'description', // orderInfo
            'returnUrl',
            'notifyUrl'
        );

        $order = $this->buildOrder();

        $secretKey = $this->getSecretKey();

        $order['requestType'] = 'captureMoMoWallet';

        $rawHash = "partnerCode=". $order['partnerCode'] ."&accessKey=". $order['accessKey'] ."&requestId=". $order['requestId'] ."&amount=". $order['amount'] ."&orderId=". $order['orderId'] ."&orderInfo=". $order['orderInfo'] ."&returnUrl=". $order['returnUrl'] ."&notifyUrl=". $order['notifyUrl'] ."&extraData=". $order['extraData'];

        $order['signature'] = hash_hmac('sha256', $rawHash, $secretKey);

        return [
            'order' => $order
        ];
    }

    public function sendData($data)
    {
        $endpoint = $this->endPointProduction;
        if ($this->getTestMode())
            $endpoint = $this->endPointSandbox;

        $order = $data['order'];

        $body     = json_encode($order);
        $response = $this->httpClient->request('POST', $endpoint . '/gw_payment/transactionProcessor', [], $body)->getBody();
        $result  = json_decode($response, true);

        return $this->response = new PurchaseResponse($this, $result);
    }

    protected function buildOrder()
    {
        //$orderShipDate = Chronos::parse('+1 day', self::TIME_ZONE)->format('d/m/Y');
        //$validityTime = $this->getValidityTime() ? $this->getValidityTime() : new \DateTime('+24 hours');
        //$validityTime = $validityTime->format('YmdHis');

        return [
            'partnerCode' => $this->getPartnerCode(),
            'accessKey' => $this->getAccessKey(),
            'requestId' => (string)time(),
            'amount' => $this->getAmount(),
            'orderId' => $this->getTransactionId(),
            'orderInfo' => $this->getDescription(),
            'returnUrl' => $this->getReturnUrl(),
            'notifyUrl' => $this->getNotifyUrl(),
            'extraData' => ''
        ];
    }
}