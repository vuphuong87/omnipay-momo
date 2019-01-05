<?php

namespace Omnipay\Momo\Message;


use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    private $endPointProduction = 'https://payment.momo.vn';
    private $endPointSandbox = 'https://test-payment.momo.vn';

    public function isSuccessful()
    {
        return false;
    }

    public function isPending()
    {
        return true;
    }

    public function isRedirect()
    {
        return true;
    }

    public function isTransparentRedirect()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectUrl()
    {
        if ($this->request->getTestMode()) {
            return $this->endPointSandbox;
        }

        return $this->endPointProduction;
    }

    public function getRedirectData()
    {
        return $this->data;
    }

}