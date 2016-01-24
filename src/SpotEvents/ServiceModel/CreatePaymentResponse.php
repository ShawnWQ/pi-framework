<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 4:40 AM
 */

namespace SpotEvents\ServiceModel;

use Pi\Response;

class CreatePaymentResponse extends Response{
    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->paymentDto;
    }

    /**
     * @param mixed $paymentDto
     */
    public function setPayment($paymentDto)
    {
        $this->paymentDto = $paymentDto;
    }

    protected $paymentDto;
}