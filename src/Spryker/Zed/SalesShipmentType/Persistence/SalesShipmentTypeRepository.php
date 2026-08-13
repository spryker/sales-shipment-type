<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Persistence;

use Generated\Shared\Transfer\SalesShipmentTypeCriteriaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\SalesShipmentType\Persistence\Map\SpySalesShipmentTypeTableMap;
use Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\SalesShipmentType\Persistence\Propel\Mapper\SalesShipmentTypeMapper;

/**
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypePersistenceFactory getFactory()
 */
class SalesShipmentTypeRepository extends AbstractRepository implements SalesShipmentTypeRepositoryInterface
{
    /**
     * @param list<string> $salesShipmentTypeKeys
     *
     * @return list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer>
     */
    public function getSalesShipmentTypesByKeys(array $salesShipmentTypeKeys): array
    {
        $salesShipmentTypeEntities = $this->getFactory()
            ->createSalesShipmentTypeQuery()
            ->filterByKey_In($salesShipmentTypeKeys)
            ->find();

        if ($salesShipmentTypeEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()
            ->createSalesShipmentTypeMapper()
            ->mapSalesShipmentTypeEntitiesToSalesShipmentTypeTransfers(
                $salesShipmentTypeEntities,
                [],
            );
    }

    /**
     * @module Sales
     * @module ShipmentType
     *
     * @param \Generated\Shared\Transfer\SalesShipmentTypeCriteriaTransfer $salesShipmentTypeCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function getSalesShipmentTypeCollection(
        SalesShipmentTypeCriteriaTransfer $salesShipmentTypeCriteriaTransfer
    ): array {
        $salesShipmentQuery = $this->getFactory()
            ->getSalesShipmentPropelQuery()
            ->joinWithSalesShipmentType(Criteria::INNER_JOIN)
            ->addJoin(SpySalesShipmentTypeTableMap::COL_KEY, SpyShipmentTypeTableMap::COL_KEY, Criteria::LEFT_JOIN)
            ->withColumn(SpyShipmentTypeTableMap::COL_ID_SHIPMENT_TYPE, SalesShipmentTypeMapper::VIRTUAL_COLUMN_ID_SHIPMENT_TYPE)
            ->withColumn(SpyShipmentTypeTableMap::COL_UUID, SalesShipmentTypeMapper::VIRTUAL_COLUMN_UUID);

        $salesShipmentQuery = $this->applySalesShipmentFilters($salesShipmentQuery, $salesShipmentTypeCriteriaTransfer);

        return $this->getFactory()
            ->createSalesShipmentTypeMapper()
            ->mapSalesShipmentEntitiesToItemTransfers(
                $salesShipmentQuery->with('SpySalesOrderItem')->find(),
                [],
            );
    }

    /**
     * @module Sales
     */
    protected function applySalesShipmentFilters(
        SpySalesShipmentQuery $salesShipmentQuery,
        SalesShipmentTypeCriteriaTransfer $salesShipmentTypeCriteriaTransfer
    ): SpySalesShipmentQuery {
        $salesShipmentTypeConditionsTransfer = $salesShipmentTypeCriteriaTransfer->getSalesShipmentTypeConditions();

        if (!$salesShipmentTypeConditionsTransfer) {
            return $salesShipmentQuery;
        }

        if ($salesShipmentTypeConditionsTransfer->getSalesOrderItemIds() !== []) {
            $salesShipmentQuery
                ->useSpySalesOrderItemQuery(null, Criteria::INNER_JOIN)
                    ->filterByIdSalesOrderItem_In($salesShipmentTypeConditionsTransfer->getSalesOrderItemIds())
                ->endUse();
        }

        return $salesShipmentQuery;
    }
}
