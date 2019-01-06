<?php

namespace Omnipay\Momo\Message;


class CompletePurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        return [
            'order_no' => $this->httpRequest->request->get('transId'),
            'state' => $this->httpRequest->request->get('errorCode'),
            'signature' => $this->httpRequest->request->get('signature'),
            'computed_checksum' => $this->computedSignature($this->httpRequest->request->all()),
            'method' => 'post',
            'payment_method' => $this->httpRequest->request->get('payType')
        ];
    }

    public function sendData($data)
    {
        return new CompletePurchaseResponse($this, $data);
    }

    private function computedSignature($data)
    {
        $rawHash = "partnerCode=" . $this->getPartnerCode() . "&accessKey=" . $this->getAccessKey() . "&requestId=" . $data['requestId'] . "&amount=" . $data['amount'] . "&orderId=" . $data['orderId'] . "&orderInfo=" . $data['orderInfo'] . "&orderType=" . $data['orderType'] . "&transId=" . $data['transId'] . "&message=" . $data['message'] . "&localMessage=" . $data['localMessage'] . "&responseTime=" . $data['responseTime'] . "&errorCode=" . $data['errorCode'] . "&payType=" . $data['payType'] . "&extraData=" . $data['extraData'];
        return hash_hmac('sha256', $rawHash, $this->getSecretKey());
    }
}