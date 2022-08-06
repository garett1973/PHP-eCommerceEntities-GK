<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $deliveryName;

    /**
     * @ORM\Column(type="text")
     */
    private $deliveryAddress;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDeliveryName()
    {
        return $this->deliveryName;
    }

    /**
     * @param mixed $deliveryName
     */
    public function setDeliveryName($deliveryName): void
    {
        $this->deliveryName = $deliveryName;
    }

    /**
     * @return mixed
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * @param mixed $deliveryAddress
     */
    public function setDeliveryAddress($deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}