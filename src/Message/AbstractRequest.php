<?php

namespace Omnipay\Momo\Message;

use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public function getCurrency()
    {
        // only works for VND
        return 'VND';
    }

    public function getPartnerCode()
    {
        return $this->getParameter('partnerCode');
    }

    public function setPartnerCode($partnerCode)
    {
        return $this->setParameter('partnerCode', $partnerCode);
    }

    public function getAccessKey()
    {
        return $this->getParameter('accessKey');
    }

    public function setAccessKey($accessKey)
    {
        return $this->setParameter('accessKey', $accessKey);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($secretKey)
    {
        return $this->setParameter('secretKey', $secretKey);
    }

    public function setValidityTime($validityTime)
    {
        return $this->setParameter('validityTime', $validityTime);
    }

    public function getValidityTime()
    {
        return $this->getParameter('validityTime');
    }
}