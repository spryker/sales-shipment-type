<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType;
use Propel\Runtime\Collection\Collection;

class SalesShipmentTypeMapper
{
    public const string VIRTUAL_COLUMN_ID_SHIPMENT_TYPE = 'id_shipment_type';

    public const string VIRTUAL_COLUMN_UUID = 'shipment_type_uuid';

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType> $salesShipmentTypeEntities
     * @param list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer> $salesShipmentTypeTransfers
     *
     * @return list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer>
     */
    public function mapSalesShipmentTypeEntitiesToSalesShipmentTypeTransfers(
        Collection $salesShipmentTypeEntities,
        array $salesShipmentTypeTransfers
    ): array {
        foreach ($salesShipmentTypeEntities as $salesShipmentTypeEntity) {
            $salesShipmentTypeTransfers[] = $this->mapSalesShipmentTypeEntityToSalesShipmentTypeTransfer(
                $salesShipmentTypeEntity,
                new SalesShipmentTypeTransfer(),
            );
        }

        return $salesShipmentTypeTransfers;
    }

    public function mapSalesShipmentTypeTransferToSalesShipmentTypeEntity(
        SalesShipmentTypeTransfer $salesShipmentTypeTransfer,
        SpySalesShipmentType $salesShipmentTypeEntity
    ): SpySalesShipmentType {
        $salesShipmentTypeEntity->fromArray($salesShipmentTypeTransfer->modifiedToArray());

        return $salesShipmentTypeEntity;
    }

    public function mapSalesShipmentTypeEntityToSalesShipmentTypeTransfer(
        SpySalesShipmentType $salesShipmentTypeEntity,
        SalesShipmentTypeTransfer $salesShipmentTypeTransfer
    ): SalesShipmentTypeTransfer {
        return $salesShipmentTypeTransfer->fromArray($salesShipmentTypeEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\Sales\Persistence\SpySalesShipment> $salesShipmentEntities
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function mapSalesShipmentEntitiesToItemTransfers(
        Collection $salesShipmentEntities,
        array $itemTransfers
    ): array {
        foreach ($salesShipmentEntities as $salesShipmentEntity) {
            if (!$salesShipmentEntity->getSalesShipmentType()) {
                continue;
            }

            foreach ($salesShipmentEntity->getSpySalesOrderItems() as $salesOrderItemEntity) {
                $idSalesOrderItem = (int)$salesOrderItemEntity->getIdSalesOrderItem();
                $itemTransfers[$idSalesOrderItem] = $this->mapSalesShipmentEntityToItemTransfer(
                    $salesShipmentEntity,
                    (new ItemTransfer())->setIdSalesOrderItem($idSalesOrderItem),
                );
            }
        }

        return $itemTransfers;
    }

    public function mapSalesShipmentEntityToItemTransfer(
        SpySalesShipment $salesShipmentEntity,
        ItemTransfer $itemTransfer
    ): ItemTransfer {
        $salesShipmentTypeEntity = $salesShipmentEntity->getSalesShipmentType();
        if (!$salesShipmentTypeEntity) {
            return $itemTransfer;
        }

        // spy_sales_shipment_type snapshots only key and name, so uuid can never come from the
        // entity itself — it is read from the joined live spy_shipment_type. Consumers such as
        // ShipmentGroupFilter call getUuidOrFail(), so leaving it unset turns any reorder of an
        // order that carried a shipment type into a 500.
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())->fromArray($salesShipmentTypeEntity->toArray(), true);

        $idShipmentType = $salesShipmentEntity->getVirtualColumn(static::VIRTUAL_COLUMN_ID_SHIPMENT_TYPE);
        if ($idShipmentType !== null) {
            $shipmentTypeTransfer->setIdShipmentType((int)$idShipmentType);
        }

        $uuid = $salesShipmentEntity->getVirtualColumn(static::VIRTUAL_COLUMN_UUID);
        if ($uuid !== null) {
            $shipmentTypeTransfer->setUuid($uuid);
        }

        return $itemTransfer->setShipmentType($shipmentTypeTransfer);
    }
}
