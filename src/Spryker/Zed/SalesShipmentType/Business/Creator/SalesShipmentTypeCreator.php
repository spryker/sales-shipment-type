<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Creator;

use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\SalesShipmentType\Business\Mapper\SalesShipmentTypeMapperInterface;
use Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface;

class SalesShipmentTypeCreator implements SalesShipmentTypeCreatorInterface
{
    /**
     * @var \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface
     */
    protected SalesShipmentTypeEntityManagerInterface $salesShipmentTypeEntityManager;

    /**
     * @var \Spryker\Zed\SalesShipmentType\Business\Mapper\SalesShipmentTypeMapperInterface
     */
    protected SalesShipmentTypeMapperInterface $salesShipmentTypeMapper;

    public function __construct(
        SalesShipmentTypeEntityManagerInterface $salesShipmentTypeEntityManager,
        SalesShipmentTypeMapperInterface $salesShipmentTypeMapper
    ) {
        $this->salesShipmentTypeEntityManager = $salesShipmentTypeEntityManager;
        $this->salesShipmentTypeMapper = $salesShipmentTypeMapper;
    }

    public function createSalesShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer): SalesShipmentTypeTransfer
    {
        $salesShipmentTypeTransfer = $this->salesShipmentTypeMapper->mapShipmentTypeTransferToSalesShipmentTypeTransfer(
            $shipmentTypeTransfer,
            new SalesShipmentTypeTransfer(),
        );

        return $this->salesShipmentTypeEntityManager->createSalesShipmentType($salesShipmentTypeTransfer);
    }
}
