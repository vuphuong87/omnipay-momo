<?php

namespace Omnipay\Momo\Message;


use Cake\Chronos\Chronos;
use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{
    const RULE_DES_MIN_LENGTH = 50;

    const TIME_ZONE = 'Asia/Ho_Chi_Minh';

    public function getData()
    {
        $this->validate(
            'partnerCode',
            'accessKey',
            'requestId',
            'amount',
            'orderId',
            'orderInfo',
            'returnUrl',
            'notifyUrl'
        );

        $order = $this->buildOrder();

        $secretKey = $this->getSecretKey();

        $order['requestType'] = 'captureMoMoWallet';
        $order['signature'] = hash_hmac('sha256', http_build_query($order), $secretKey);

        return [
            'order' => $order
        ];
    }

    public function sendData($data)
    {
        return new PurchaseResponse($this, $data);
    }

    protected function buildOrder()
    {
        //$orderShipDate = Chronos::parse('+1 day', self::TIME_ZONE)->format('d/m/Y');
        //$validityTime = $this->getValidityTime() ? $this->getValidityTime() : new \DateTime('+24 hours');
        //$validityTime = $validityTime->format('YmdHis');

        return [
            'partnerCode' => $this->getPartnerCode(),
            'accessKey' => $this->getAccessKey(),
            'requestId' => time(),
            'amount' => $this->getAmountInteger(),
            'orderId' => $this->getTransactionId(),
            'orderInfo' => $this->getDescription(),
            'returnUrl' => $this->getReturnUrl(),
            'notifyUrl' => $this->getNotifyUrl()
        ];
    }
}