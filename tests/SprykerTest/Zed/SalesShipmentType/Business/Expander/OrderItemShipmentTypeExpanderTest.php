<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesShipmentType\Business\Expander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesShipmentTypeTransfer;
use SprykerTest\Zed\SalesShipmentType\SalesShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesShipmentType
 * @group Business
 * @group Expander
 * @group OrderItemShipmentTypeExpanderTest
 * Add your own group annotations below this line
 */
class OrderItemShipmentTypeExpanderTest extends Unit
{
    protected const string TEST_STATE_MACHINE_PROCESS_NAME = 'Test01';

    protected SalesShipmentTypeBusinessTester $tester;

    public function testExpandsOrderItemsWithShipmentTypeAssignedToSalesShipment(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $salesShipmentTypeTransfer = $this->tester->haveSalesShipmentType([
            SalesShipmentTypeTransfer::KEY => $shipmentTypeTransfer->getKeyOrFail(),
            SalesShipmentTypeTransfer::NAME => $shipmentTypeTransfer->getNameOrFail(),
        ]);
        $quoteTransfer = $this->tester->createQuoteTransfer([
            $this->tester->haveShipmentType(),
            $this->tester->haveShipmentType(),
        ]);
        $saveOrderTransfer = $this->tester->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, static::TEST_STATE_MACHINE_PROCESS_NAME);

        $shipmentTransfer = $this->tester->haveShipment($saveOrderTransfer->getIdSalesOrderOrFail());
        $this->tester->assignSalesShipmentTypeToSalesShipment(
            $shipmentTransfer->getIdSalesShipmentOrFail(),
            $salesShipmentTypeTransfer->getIdSalesShipmentTypeOrFail(),
        );

        [$itemTransferWithShipmentType, $itemTransferWithoutShipmentType] = $saveOrderTransfer->getOrderItems()->getArrayCopy();
        $this->tester->assignSalesOrderItemToSalesShipment(
            $itemTransferWithShipmentType->getIdSalesOrderItemOrFail(),
            $shipmentTransfer->getIdSalesShipmentOrFail(),
        );

        // Act
        $itemTransfers = $this->tester->getFactory()->createOrderItemShipmentTypeExpander()->expandOrderItemsWithShipmentType([
            (new ItemTransfer())->setIdSalesOrderItem($itemTransferWithShipmentType->getIdSalesOrderItemOrFail()),
            (new ItemTransfer())->setIdSalesOrderItem($itemTransferWithoutShipmentType->getIdSalesOrderItemOrFail()),
        ]);

        // Assert
        $this->assertNotNull($itemTransfers[0]->getShipmentType());
        $this->assertSame($salesShipmentTypeTransfer->getKeyOrFail(), $itemTransfers[0]->getShipmentTypeOrFail()->getKey());
        $this->assertSame($salesShipmentTypeTransfer->getNameOrFail(), $itemTransfers[0]->getShipmentTypeOrFail()->getName());
        $this->assertSame($shipmentTypeTransfer->getIdShipmentTypeOrFail(), $itemTransfers[0]->getShipmentTypeOrFail()->getIdShipmentType());
        $this->assertSame($shipmentTypeTransfer->getUuidOrFail(), $itemTransfers[0]->getShipmentTypeOrFail()->getUuid());
        $this->assertNull($itemTransfers[1]->getShipmentType());
    }

    public function testReturnsOrderItemsUnchangedWhenIdSalesOrderItemIsNotProvided(): void
    {
        // Arrange
        $itemTransfer = new ItemTransfer();

        // Act
        $itemTransfers = $this->tester->getFactory()
            ->createOrderItemShipmentTypeExpander()
            ->expandOrderItemsWithShipmentType([$itemTransfer]);

        // Assert
        $this->assertSame($itemTransfer, $itemTransfers[0]);
        $this->assertNull($itemTransfers[0]->getShipmentType());
    }
}
