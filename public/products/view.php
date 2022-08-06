<?php

use App\Entity\Product;

require_once dirname(__DIR__, 2) . '/bootstrap.php';

    /** @var \Doctrine\ORM\EntityManager $em */
    $em = $entityManager;

    $productId = $_GET['id'];

    /** @var Product $product */
    $product = $em->getRepository(Product::class)->find($productId);

    $title = $product->getName();
    $description = $product->getDescription();
    $price = $product->getPrice();

    include dirname(__DIR__, 1) . '/includes/site-header.php';
?>

<body class="d-flex flex-column h-100">

<!-- Begin page content -->
<main class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5"><?= $product->getName() ?></h1>
        <p class="lead"><?= $product->getDescription() ?></p>
        <p >price: <b style="color: chocolate">$<?= number_format($product->getPrice() / 100, 2) ?></b></p>
        <a href="/products/single-product-checkout.php?id=<?= $product->getId() ?>" class="btn
        btn-outline-warning">BUY IT NOW</a>
    </div>
</main>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <span class="text-muted">Place sticky footer content here.</span>
    </div>
</footer>


<script src="/docs/5.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>
</html>



