<?php

namespace App\Tests;

use App\Entity\Order;

class OrdersTests extends DatabaseDependantTestCase
{
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
            'deliveryName' => $deliveryName,
            'deliveryAddress' => $deliveryAddress,
        ]);
    }
}