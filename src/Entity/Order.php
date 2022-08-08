<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\OneToMany(targetEntity="Item", mappedBy="order")
     */
    private $items;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="cancelled_at", nullable=true)
     * @var DateTimeInterface
     */
    private ?\DateTimeImmutable $cancelledAt = null;

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
        $this->items = new ArrayCollection();
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCancelledAt(): ?\DateTimeImmutable
    {
        return $this->cancelledAt;
    }

    /**
     * @param \DateTimeImmutable|null $cancelledAt
     */
    public function setCancelledAt(?\DateTimeImmutable $cancelledAt): void
    {
        $this->cancelledAt = $cancelledAt;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }
}