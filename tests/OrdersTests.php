<?php

namespace App\Tests;

use App\Entity\Order;

class OrdersTests extends DatabaseDependantTestCase
{

    private string $deliveryName = 'Delivery Name';
    private string $deliveryAddress = 'Delivery Address';

    protected function setUp(): void
    {
        parent::setUp();

        $order = new Order();
        $order->setDeliveryName($this->deliveryName);
        $order->setDeliveryAddress($this->deliveryAddress);

        // Act
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /**
     * @test
     */
    public function an_order_can_be_created()
    {
        // Arrange
        $deliveryName = 'Delivery Name';
        $deliveryAddress = 'Delivery Address';

        $order = new Order();
        $order->setDeliveryName($deliveryName);
        $order->setDeliveryAddress($deliveryAddress);

        // Act
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Assert
        $this->assertDatabaseHas(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
            'cancelledAt' => null,
        ]);
    }

    /**
     * @test
     */
    public function an_order_can_be_updated()
    {
        // Setup
        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);

        $newDeliveryName = 'New Delivery Name';
        $newDeliveryAddress = 'New Delivery Address';

        $order->setDeliveryName($newDeliveryName);
        $order->setDeliveryAddress($newDeliveryAddress);

        $this->entityManager->flush();
        $this->entityManager->clear();

        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $newDeliveryName,
            'deliveryAddress' => $newDeliveryAddress,
        ]);
        // Assert
        $this->assertDatabaseHas(Order::class, [
            'deliveryName' => $newDeliveryName,
            'deliveryAddress' => $newDeliveryAddress,
        ]);

        // Assert opposite
        $this->assertDatabaseNotHas(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);
    }
    
    /**
     * @test
     */
    public function an_order_can_be_cancelled()
    {
        // Setup
        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);

        $cancelledAt = new \DateTimeImmutable();

        // Act
        $order->setCancelledAt($cancelledAt);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Assert
        $this->assertDatabaseHas(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
            'cancelledAt' => $cancelledAt
        ]);
    }
}