<?php

namespace Omnipay\Momo\Message;

use Omnipay\Common\Message\AbstractResponse;

class QueryResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        $data = $this->getData();
        return isset($data['errorCode']) && $data['errorCode'] == 0 ? true : false;
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
        return 'GET';
    }

    public function getRedirectUrl()
    {
        if ($this->isSuccessful()) {
            $data = $this->getData();
            return $data['payUrl'];
        } else {
            return NULL;
        }
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}