<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Persistence;

use Generated\Shared\Transfer\SalesShipmentTypeCriteriaTransfer;

interface SalesShipmentTypeRepositoryInterface
{
    /**
     * @param list<string> $salesShipmentTypeKeys
     *
     * @return list<\Generated\Shared\Transfer\SalesShipmentTypeTransfer>
     */
    public function getSalesShipmentTypesByKeys(array $salesShipmentTypeKeys): array;

    /**
     * @param \Generated\Shared\Transfer\SalesShipmentTypeCriteriaTransfer $salesShipmentTypeCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function getSalesShipmentTypeCollection(
        SalesShipmentTypeCriteriaTransfer $salesShipmentTypeCriteriaTransfer
    ): array;
}
