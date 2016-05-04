<?php
/**
 * Created by PhpStorm.
 * User: gui
 * Date: 6/9/15
 * Time: 4:21 AM
 */

namespace SpotEvents\ServiceModel;


class SubscriptionDto {
    /**
     * @return mixed
     */
    public function getPaymentRef()
    {
        return $this->paymentRef;
    }

    /**
     * @param mixed $paymentRef
     */
    public function setPaymentRef($paymentRef)
    {
        $this->paymentRef = $paymentRef;
    }

    /**
     * @return mixed
     */
    public function getPaymentEnt()
    {
        return $this->paymentEnt;
    }

    /**
     * @param mixed $paymentEnt
     */
    public function setPaymentEnt($paymentEnt)
    {
        $this->paymentEnt = $paymentEnt;
    }

    /**
     * @return mixed
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * @param mixed $paymentAmount
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
    }

    /**
     * @return mixed
     */
    public function getPaymentSeed()
    {
        return $this->paymentSeed;
    }

    /**
     * @param mixed $paymentSeed
     */
    public function setPaymentSeed($paymentSeed)
    {
        $this->paymentSeed = $paymentSeed;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param mixed $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @return mixed
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param mixed $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }


    protected $id;

    protected $position;

    protected $limit;

    protected $entityId;

    protected $entityName;

    protected $paymentRef;

    protected $paymentEnt;

    protected $paymentAmount;

    protected $paymentSeed;
}