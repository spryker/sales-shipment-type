<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Persistence;

use Generated\Shared\Transfer\SalesShipmentTypeTransfer;

interface SalesShipmentTypeEntityManagerInterface
{
    public function createSalesShipmentType(SalesShipmentTypeTransfer $salesShipmentTypeTransfer): SalesShipmentTypeTransfer;

    public function updateSalesShipmentWithSalesShipmentType(int $idSalesShipment, int $idSalesShipmentType): void;
}
