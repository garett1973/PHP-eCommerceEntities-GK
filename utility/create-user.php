<?php

use App\Entity\User;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

require_once "vendor/autoload.php";
require_once "bootstrap.php";

[$filename, $username, $password] = $argv;

$user = new User();

$user->setUsername($username);
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$user->setPassword($hashedPassword);

/** EntityManager $em */
$em = $entityManager;

try {
    $em->persist($user);
} catch (ORMException $e) {
    echo $e->getMessage();
}
try {
    $em->flush();
} catch (OptimisticLockException $e) {
} catch (ORMException $e) {
    echo $e->getMessage();
}

echo 'Created user with id ' . $user->getId() . PHP_EOL;


