<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SalesShipmentType\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\SalesShipmentTypeBuilder;
use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentTypeQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class SalesShipmentTypeHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\SalesShipmentTypeTransfer
     */
    public function haveSalesShipmentType(array $seedData = []): SalesShipmentTypeTransfer
    {
        $salesShipmentTypeTransfer = (new SalesShipmentTypeBuilder($seedData))->build();
        $salesShipmentTypeTransfer = $this->saveSalesShipmentTypeEntity($salesShipmentTypeTransfer);

        $this->getDataCleanupHelper()->addCleanup(function () use ($salesShipmentTypeTransfer): void {
            $this->cleanupSalesShipmentType($salesShipmentTypeTransfer);
        });

        return $salesShipmentTypeTransfer;
    }

    protected function saveSalesShipmentTypeEntity(SalesShipmentTypeTransfer $salesShipmentTypeTransfer): SalesShipmentTypeTransfer
    {
        $salesShipmentTypeEntity = $this->getSalesShipmentTypeQuery()
            ->filterByKey($salesShipmentTypeTransfer->getKeyOrFail())
            ->findOneOrCreate();
        $salesShipmentTypeEntity->fromArray($salesShipmentTypeTransfer->toArray());
        $salesShipmentTypeEntity->save();

        return $salesShipmentTypeTransfer->fromArray($salesShipmentTypeEntity->toArray(), true);
    }

    protected function cleanupSalesShipmentType(SalesShipmentTypeTransfer $salesShipmentTypeTransfer): void
    {
        $this->getSalesShipmentTypeQuery()
            ->filterByKey($salesShipmentTypeTransfer->getKeyOrFail())
            ->delete();
    }

    protected function getSalesShipmentTypeQuery(): SpySalesShipmentTypeQuery
    {
        return SpySalesShipmentTypeQuery::create();
    }
}
