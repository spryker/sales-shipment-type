<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesShipmentType\Business\Hydrator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use SprykerTest\Zed\SalesShipmentType\SalesShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesShipmentType
 * @group Business
 * @group Hydrator
 * @group CartReorderItemHydratorTest
 * Add your own group annotations below this line
 */
class CartReorderItemHydratorTest extends Unit
{
    protected const string SHIPMENT_TYPE_KEY = 'in-center-service';

    protected SalesShipmentTypeBusinessTester $tester;

    public function testExpandsExistingReorderItemWithShipmentTypeOfOrderItem(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())->setKey(static::SHIPMENT_TYPE_KEY);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->addOrderItem($this->createItemTransfer(1)->setShipmentType($shipmentTypeTransfer))
            ->addReorderItem($this->createItemTransfer(1));

        // Act
        $cartReorderTransfer = $this->tester->getFactory()->createCartReorderItemHydrator()->hydrate($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());
        $this->assertSame($shipmentTypeTransfer, $cartReorderTransfer->getReorderItems()->getIterator()->current()->getShipmentType());
    }

    public function testAddsNewReorderItemWithShipmentTypeWhenReorderItemIsMissing(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())->setKey(static::SHIPMENT_TYPE_KEY);
        $orderItemTransfer = $this->createItemTransfer(1)->setShipmentType($shipmentTypeTransfer);
        $cartReorderTransfer = (new CartReorderTransfer())->addOrderItem($orderItemTransfer);

        // Act
        $cartReorderTransfer = $this->tester->getFactory()->createCartReorderItemHydrator()->hydrate($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());

        $reorderItemTransfer = $cartReorderTransfer->getReorderItems()->getIterator()->current();
        $this->assertSame($orderItemTransfer->getIdSalesOrderItem(), $reorderItemTransfer->getIdSalesOrderItem());
        $this->assertSame($orderItemTransfer->getSku(), $reorderItemTransfer->getSku());
        $this->assertSame($orderItemTransfer->getQuantity(), $reorderItemTransfer->getQuantity());
        $this->assertSame($shipmentTypeTransfer, $reorderItemTransfer->getShipmentType());
    }

    public function testDoesNothingWhenOrderItemsHaveNoShipmentType(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderTransfer())
            ->addOrderItem($this->createItemTransfer(1))
            ->addReorderItem($this->createItemTransfer(1));

        // Act
        $cartReorderTransfer = $this->tester->getFactory()->createCartReorderItemHydrator()->hydrate($cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getReorderItems());
        $this->assertNull($cartReorderTransfer->getReorderItems()->getIterator()->current()->getShipmentType());
    }

    protected function createItemTransfer(int $idSalesOrderItem): ItemTransfer
    {
        return (new ItemTransfer())
            ->setIdSalesOrderItem($idSalesOrderItem)
            ->setSku(sprintf('sku-%d', $idSalesOrderItem))
            ->setQuantity(1);
    }
}
