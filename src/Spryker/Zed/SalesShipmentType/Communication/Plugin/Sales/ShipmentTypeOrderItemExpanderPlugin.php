<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesShipmentType\SalesShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeBusinessFactory getBusinessFactory()
 */
class ShipmentTypeOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `ItemTransfer.idSalesOrderItem` transfer property to be set.
     * - Expands order items with shipment type.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getBusinessFactory()
            ->createOrderItemShipmentTypeExpander()
            ->expandOrderItemsWithShipmentType($itemTransfers);
    }
}
