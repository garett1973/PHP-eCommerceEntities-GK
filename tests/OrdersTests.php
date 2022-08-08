<?php

namespace App\Tests;

use App\Entity\Item;
use App\Entity\Order;
use App\Entity\Product;

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
        $this->assertDatabaseHasEntity(Order::class, [
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
        $this->assertDatabaseHasEntity(Order::class, [
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
        $this->assertDatabaseHasEntity(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
            'cancelledAt' => $cancelledAt
        ]);
    }

    // item tests
    /**
     * @test
     */
    public function an_item_can_be_added_to_an_order()
    {
        // Setup
        // Need a product for the order


        $name = 'Product 1 Name';
        $description = 'Product 1 Description';
        $price = 10099;

        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        // Need an order for the item

        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);

        // Act
        // Create an item using refs to the order and product

        $item = new Item();
        $item->setOrder($order);
        $item->setProduct($product);
        $item->setPrice($product->getPrice());


        $this->entityManager->persist($item);
        $this->entityManager->flush();

        // Assert
        // The item is in the order

        $this->assertDatabaseHas('items', [
            'order_id' => $order->getId(),
            'product_id' => $product->getId(),
            'price' => $product->getPrice(),
        ]);

        // Check that we can retrieve the item from the order, e.g. $order->getItems()
        $this->assertCount(1, $order->getItems());
    }

    /**
     * @test
     */
    public function multiple_items_can_be_added_to_an_order()
    {
        // Setup

        $multiple = 3;

        // Need a product for the order
        $name = 'Product 1 Name';
        $description = 'Product 1 Description';
        $price = 10099;

        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        // Need an order for the item

        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);

        // Act
        // Create an item using refs to the order and product

        for ($i = 1; $i <= $multiple; $i++) {
            $item = new Item();
            $item->setOrder($order);
            $item->setProduct($product);
            $item->setPrice($product->getPrice());


            $this->entityManager->persist($item);
        }

        $this->entityManager->flush();

        // Assert
        // The item is in the order

        $this->assertDatabaseHasEntity(Item::class, [
            'price' => $product->getPrice(),
        ]);

        // Check that we can retrieve the item from the order, e.g. $order->getItems()
        $this->assertCount($multiple, $order->getItems());
    }
}