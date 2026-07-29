<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesShipmentType\SalesShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeBusinessFactory getBusinessFactory()
 */
class ShipmentTypeReorderItemHydratorPlugin extends AbstractPlugin implements CartReorderItemHydratorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.quantity` to be set.
     * - Requires `CartReorderTransfer.reorderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.orderItems` that have `ItemTransfer.shipmentType` set.
     * - Expands `CartReorderTransfer.reorderItems` with shipment type if item with provided `idSalesOrderItem` already exists.
     * - Adds new item with shipment type, sku, quantity and ID sales order item properties set to `CartReorderTransfer.reorderItems` otherwise.
     * - Returns `CartReorderTransfer` with shipment type set to reorder items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrate(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        return $this->getBusinessFactory()
            ->createCartReorderItemHydrator()
            ->hydrate($cartReorderTransfer);
    }
}
