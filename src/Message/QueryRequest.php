<?php

namespace Omnipay\Momo\Message;

use Cake\Chronos\Chronos;
use Omnipay\Common\Exception\InvalidRequestException;

class QueryRequest extends AbstractRequest
{
    const TIME_ZONE = 'Asia/Ho_Chi_Minh';
    private $endPointProduction = 'https://payment.momo.vn';
    private $endPointSandbox = 'https://test-payment.momo.vn';

    public function getData()
    {
        $this->validate(
            'partnerCode',
            'accessKey',
            'transactionId' // orderId
        );

        $order = $this->buildOrder();

        $secretKey = $this->getSecretKey();

        $order['requestType'] = 'transactionStatus';

        $rawHash = "partnerCode=". $order['partnerCode'] ."&accessKey=". $order['accessKey'] ."&requestId=". $order['requestId'] ."&orderId=". $order['orderId'] ."&requestType=" . $order['requestType'];

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

        return $this->response = new QueryResponse($this, $result);
    }

    protected function buildOrder()
    {
        return [
            'partnerCode' => $this->getPartnerCode(),
            'accessKey' => $this->getAccessKey(),
            'requestId' => (string)time(),
            'orderId' => $this->getTransactionId()
        ];
    }
}