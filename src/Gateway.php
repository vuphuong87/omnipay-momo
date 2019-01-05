<?php

namespace Omnipay\Momo;


use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'Momo';
    }

    public function getDefaultParameters()
    {
        return [
            'partnerCode' => '',
            'accessKey' => '',
            'secretKey' => ''
        ];
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

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Momo\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Momo\Message\CompletePurchaseRequest', $parameters);
    }

}