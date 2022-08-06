<?php

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class DatabaseDependantTestCase extends TestCase
{
    /** @var EntityManager */
    protected $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        require 'bootstrap-test.php';

        $this->entityManager = $entityManager;

        SchemaLoader::load($entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function asKeyValuesString(array $criteria, $separator = ' = ')
    {
        $mappedAttributes = array_map(function ($key, $value) use ($separator) {
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y-m-d');
            }
            return $key . $separator . $value;
        }, array_keys($criteria), $criteria);
        return implode(', ', $mappedAttributes);
    }

    public function assertDatabaseHas(string $entity, array $criteria)
    {
        $result = $this->entityManager->getRepository($entity)->findOneBy($criteria);
        $keyValues = $this->asKeyValuesString($criteria);

        $failureMessage = "A $entity record could not be found with the following attributes: $keyValues";

        $this->assertTrue((bool) $result, $failureMessage);
    }

    public function assertDatabaseNotHas(string $entity, array $criteria)
    {
        $result = $this->entityManager->getRepository($entity)->findOneBy($criteria);
        $keyValues = $this->asKeyValuesString($criteria);

        $failureMessage = "A $entity record WAS FOUND with the following attributes: $keyValues";
        $this->assertFalse((bool) $result, $failureMessage);
    }
}