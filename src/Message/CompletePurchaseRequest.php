<?php

namespace Omnipay\Momo\Message;


class CompletePurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        return [];
    }

    public function sendData($data)
    {
        return new CompletePurchaseResponse($this, $data);
    }
}