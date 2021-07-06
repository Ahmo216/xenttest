<?php

namespace Xentral\Modules\Spryker;

use Xentral\Components\Database\Database;
use Xentral\Modules\Spryker\Exception\EmptyValueException;

final class SprykerRepository
{
    /** @var string */
    private $orderReferenceAssociationTableName = 'spryker_online_number';

    /** @var Database */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAssociatedOrderNumber(string $orderReference, string $shipmentReference): ?string
    {
        $this->validateOrderReferenceNumber($orderReference);
        $this->validateShipmentReferenceNumber($shipmentReference);

        $statement = $this->database
            ->select()
            ->cols(['order_number'])
            ->from($this->orderReferenceAssociationTableName)
            ->where('order_reference = :order_reference')
            ->where('order_shipment = :order_shipment')
            ->bindValue('order_reference', $orderReference)
            ->bindValue('order_shipment', $shipmentReference)
            ->limit(1);

        $incrementalNumber = $this->database->fetchValue($statement->getStatement(), $statement->getBindValues());

        if ($incrementalNumber === false) {
            return null;
        }

        return $this->getPreparedOrderNumber($orderReference, (int)$incrementalNumber);
    }

    private function getPreparedOrderNumber(string $orderReference, int $suffix): string
    {
        return $orderReference . ($suffix > 0 ? '-' . $suffix : '');
    }

    public function createAssociatedOrderNumber(string $orderReference, string $shipmentReference): ?string
    {
        $this->validateOrderReferenceNumber($orderReference);
        $this->validateShipmentReferenceNumber($shipmentReference);

        $currentlyFreeSuffix = $this->getNextOrderNumberSuffix($orderReference);

        $statement = $this->database
            ->insert()
            ->into($this->orderReferenceAssociationTableName)
            ->cols(['order_reference', 'order_shipment', 'order_number'])
            ->getStatement();

        $values = [
            'order_reference' => $orderReference,
            'order_shipment'  => $shipmentReference,
            'order_number'    => $currentlyFreeSuffix,
        ];
        $this->database->perform($statement, $values);

        return $this->getPreparedOrderNumber($orderReference, (int)$currentlyFreeSuffix);
    }

    private function getNextOrderNumberSuffix(string $orderReference): string
    {
        $statement = $this->database
            ->select()
            ->cols(['MAX(order_number)'])
            ->from($this->orderReferenceAssociationTableName)
            ->where('order_reference = :order_reference')
            ->bindValue('order_reference', $orderReference)
            ->limit(1);
        $currentSuffix = $this->database->fetchValue($statement->getStatement(), $statement->getBindValues());

        return $currentSuffix === null ? 0 : $currentSuffix + 1;
    }

    private function validateOrderReferenceNumber(string $referenceNumber): void
    {
        if (empty($referenceNumber)) {
            throw new EmptyValueException('Value for Order reference number must not be empty.');
        }
    }

    private function validateShipmentReferenceNumber(string $referenceNumber): void
    {
        if (empty($referenceNumber)) {
            throw new EmptyValueException('Value for Order reference number must not be empty.');
        }
    }
}
