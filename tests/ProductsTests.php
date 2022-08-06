<?php

namespace App\Tests;

use App\Entity\Product;

class ProductsTests extends DatabaseDependantTestCase
{

    /**
     * @test
     */
    public function product_can_be_created()
    {
        // SETUP
        $name = 'Product 1';
        $description = 'This is a product 1 description';
        $price = 10049;

        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);

        // EXECUTE
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        // ASSERT
        $this->assertDatabaseHas(Product::class, [
            'name' => $name,
            'description' => $description,
            'price' => $price,
        ]);

        // ASSERT OPPOSITE
        $this->assertdatabaseNotHas(Product::class, [
            'name' => $name,
            'description' => "This is a different product 1 description",
            'price' => $price,
        ]);
    }
}
