<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class CartReorderItemHydrator implements CartReorderItemHydratorInterface
{
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $itemsWithShipmentType = $this->extractItemsWithShipmentType($cartReorderTransfer->getOrderItems());
        if ($itemsWithShipmentType === []) {
            return $cartReorderTransfer;
        }

        $reorderItemsIndexedByIdSalesOrderItem = $this->getItemTransfersIndexedByIdSalesOrderItem($cartReorderTransfer->getReorderItems());
        foreach ($itemsWithShipmentType as $index => $itemTransfer) {
            $reorderItemTransfer = $reorderItemsIndexedByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItemOrFail()] ?? null;
            if (!$reorderItemTransfer) {
                $this->addReorderItem($cartReorderTransfer, $itemTransfer, $index);

                continue;
            }

            $reorderItemTransfer->setShipmentType($itemTransfer->getShipmentTypeOrFail());
        }

        return $cartReorderTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractItemsWithShipmentType(ArrayObject $itemTransfers): array
    {
        $itemsWithShipmentType = [];
        foreach ($itemTransfers as $index => $itemTransfer) {
            if ($itemTransfer->getShipmentType()) {
                $itemsWithShipmentType[$index] = $itemTransfer;
            }
        }

        return $itemsWithShipmentType;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByIdSalesOrderItem(ArrayObject $itemTransfers): array
    {
        $indexedItemTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            $indexedItemTransfers[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $indexedItemTransfers;
    }

    protected function addReorderItem(CartReorderTransfer $cartReorderTransfer, ItemTransfer $itemTransfer, int $index): void
    {
        $reorderItemTransfer = (new ItemTransfer())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail())
            ->setSku($itemTransfer->getSkuOrFail())
            ->setQuantity($itemTransfer->getQuantityOrFail())
            ->setShipmentType($itemTransfer->getShipmentTypeOrFail());

        $cartReorderTransfer->getReorderItems()->offsetSet($index, $reorderItemTransfer);
    }
}
