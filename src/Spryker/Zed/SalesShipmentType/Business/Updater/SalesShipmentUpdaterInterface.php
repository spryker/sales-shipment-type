<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business\Updater;

use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesShipmentUpdaterInterface
{
    public function saveSalesShipmentsWithSalesShipmentType(SaveOrderTransfer $saveOrderTransfer): SaveOrderTransfer;
}
