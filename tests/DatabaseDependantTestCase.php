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

    public function assertDatabaseHas(string $tableName, array $criteria)
    {
        // Get SQL placeholders for the attributes
        $sqlParameters = $keys = array_keys($criteria);

        $firstColumn = array_shift($sqlParameters);

        //Create base SQL query
        // SELECT 1 FROM tableName WHERE columnName = :columnName
        $sql = 'SELECT 1 FROM ' . $tableName . ' WHERE ' .  $firstColumn . ' = :' . $firstColumn;

        // if more than one filter needed, loop over remaining attributes and add to query
        foreach ($sqlParameters as $column) {
            $sql .= ' AND ' . $column . ' = :' . $column;
        }

        // Create a stmt
        $connection = $this->entityManager->getConnection();
        $stmt = $connection->prepare($sql);

        // Bind the values
        foreach ($keys as $key) {
            $stmt->bindValue(':' . $key, $criteria[$key]);
        }

        $keyValueString = $this->asKeyValuesString($criteria);

        $failureMessage = "A record could not be found in the $tableName table with the following criteria: $keyValueString";


        // Execute the stmt
        $result = $stmt->executeQuery();

        // Assert the result
        $this->assertTrue((bool) $result->fetchOne(), $failureMessage);
    }

    public function assertDatabaseHasEntity(string $entity, array $criteria)
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