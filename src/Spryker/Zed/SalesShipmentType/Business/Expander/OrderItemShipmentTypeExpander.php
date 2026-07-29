<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Expander;

use Generated\Shared\Transfer\SalesShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\SalesShipmentTypeCriteriaTransfer;
use Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeRepositoryInterface;

class OrderItemShipmentTypeExpander implements OrderItemShipmentTypeExpanderInterface
{
    public function __construct(protected SalesShipmentTypeRepositoryInterface $salesShipmentTypeRepository)
    {
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithShipmentType(array $itemTransfers): array
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($itemTransfers);
        if ($salesOrderItemIds === []) {
            return $itemTransfers;
        }

        $itemWithShipmentTypeTransfers = $this->salesShipmentTypeRepository->getSalesShipmentTypeCollection((new SalesShipmentTypeCriteriaTransfer())->setSalesShipmentTypeConditions(
            (new SalesShipmentTypeConditionsTransfer())->setSalesOrderItemIds($salesOrderItemIds),
        ));

        if ($itemWithShipmentTypeTransfers === []) {
            return $itemTransfers;
        }

        return $this->addShipmentTypesToOrderItems($itemTransfers, $itemWithShipmentTypeTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<int>
     */
    protected function extractSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getIdSalesOrderItem()) {
                continue;
            }

            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemWithShipmentTypeTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function addShipmentTypesToOrderItems(
        array $itemTransfers,
        array $itemWithShipmentTypeTransfers
    ): array {
        $itemWithShipmentTypeTransfersIndexedByIdSalesOrderItem = $this->getItemWithShipmentTypeTransfersIndexedByIdSalesOrderItem(
            $itemWithShipmentTypeTransfers,
        );

        foreach ($itemTransfers as $itemTransfer) {
            $itemWithShipmentTypeTransfer = $itemWithShipmentTypeTransfersIndexedByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItem()] ?? null;
            if (!$itemWithShipmentTypeTransfer) {
                continue;
            }

            $itemTransfer->setShipmentType($itemWithShipmentTypeTransfer->getShipmentType());
        }

        return $itemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemWithShipmentTypeTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemWithShipmentTypeTransfersIndexedByIdSalesOrderItem(array $itemWithShipmentTypeTransfers): array
    {
        $itemWithShipmentTypeTransfersIndexedByIdSalesOrderItem = [];
        foreach ($itemWithShipmentTypeTransfers as $itemWithShipmentTypeTransfer) {
            if (!$itemWithShipmentTypeTransfer->getIdSalesOrderItem()) {
                continue;
            }

            if (isset($itemWithShipmentTypeTransfersIndexedByIdSalesOrderItem[$itemWithShipmentTypeTransfer->getIdSalesOrderItemOrFail()])) {
                continue;
            }

            $itemWithShipmentTypeTransfersIndexedByIdSalesOrderItem[$itemWithShipmentTypeTransfer->getIdSalesOrderItemOrFail()] = $itemWithShipmentTypeTransfer;
        }

        return $itemWithShipmentTypeTransfersIndexedByIdSalesOrderItem;
    }
}
