<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 5:43 AM
 */

namespace SpotEvents\ServiceModel;
use SpotEvents\ServiceModel\Types\PaymentDto;
use Pi\Response;

class GetPaymentResponse extends Response {
    /**
     * @return mixed
     */
    public function getPaymentDto()
    {
        return $this->payment;
    }

    /**
     * @param mixed $paymentDto
     */
    public function setPaymentDto(PaymentDto $paymentDto)
    {
        $this->payment = $paymentDto;
    }

    protected $payment;
}