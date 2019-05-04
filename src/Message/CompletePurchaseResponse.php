<?php

namespace Omnipay\Momo\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class CompletePurchaseResponse extends AbstractResponse
{
    const STATE_PAYMENT_RECEIVED = 'PAYMENT_RECEIVED';
    const STATE_PAYMENT_PROCESSING = 'PAYMENT_PROCESSING';

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 0;
    const STATUS_CANCEL = -1;

    const RESPONSE_STATUS_SUCCESS = 1;
    const RESPONSE_STATUS_FAIL = 2;
    const RESPONSE_STATUS_CANCEL = 3;
    const RESPONSE_STATUS_PENDING = 4;

    private $responseStatus;
    private $message;

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->handleResponse($data);
    }

    public function isPending()
    {
        return $this->responseStatus == self::RESPONSE_STATUS_PENDING;
    }

    public function isSuccessful()
    {
        return $this->responseStatus == self::RESPONSE_STATUS_SUCCESS;
    }

    public function isCancelled()
    {
        return $this->responseStatus == self::RESPONSE_STATUS_CANCEL;
    }

    public function isSignatureVerified($data) {
        if (strtoupper($data['signature']) != strtoupper($data['computed_checksum']))
            return false;
        return true;
    }

    public function getTransactionId()
    {
        if (!$this->isSuccessful()) {
            return null;
        }

        return isset($this->data['order_no']) ? $this->data['order_no'] : null;
    }

    public function getTransactionReference()
    {
        return null;
    }

    public function getMessage()
    {
        return $this->message;
    }

    private function handleResponse($data)
    {
        if ($this->isSignatureVerified($data) == false) {
            $this->message = 'The signatures do not match';
            $this->responseStatus = self::RESPONSE_STATUS_FAIL;
            return;
        }

        if ($data['state'] == 0) { // errorCode = 0 => SUCCESS
            $this->responseStatus = self::RESPONSE_STATUS_SUCCESS;
            $this->message = 'Partner order has been paid.';
        }
        else {
            $this->responseStatus = self::RESPONSE_STATUS_FAIL;
            $this->message = 'Unspecified issue occurred';
            return;
        }
    }
}