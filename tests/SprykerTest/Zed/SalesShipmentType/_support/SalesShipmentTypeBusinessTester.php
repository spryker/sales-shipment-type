<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesShipmentType;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentType;
use Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentTypeQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesShipmentTypeBusinessTester extends Actor
{
    use _generated\SalesShipmentTypeBusinessTesterActions;

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(array $shipmentTypeTransfers): QuoteTransfer
    {
        $quoteBuilder = (new QuoteBuilder())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->withStore();

        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $quoteBuilder->withItem(
                (new ItemBuilder())->withShipmentType($shipmentTypeTransfer->toArray()),
            );
        }

        return $quoteBuilder->build();
    }

    public function findSalesShipmentTypeEntity(string $shipmentTypeKey, string $shipmentTypeName): ?SpySalesShipmentType
    {
        return $this->getSalesShipmentTypeQuery()
            ->filterByKey($shipmentTypeKey)
            ->filterByName($shipmentTypeName)
            ->findOne();
    }

    public function findSalesShipmentEntity(int $idSalesShipment): ?SpySalesShipment
    {
        return $this->getSalesShipmentQuery()
            ->filterByIdSalesShipment($idSalesShipment)
            ->findOne();
    }

    public function assignSalesOrderItemToSalesShipment(int $idSalesOrderItem, int $idSalesShipment): void
    {
        $this->getSalesOrderItemQuery()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne()
            ->setFkSalesShipment($idSalesShipment)
            ->save();
    }

    public function assignSalesShipmentTypeToSalesShipment(int $idSalesShipment, int $idSalesShipmentType): void
    {
        $this->getSalesShipmentQuery()
            ->filterByIdSalesShipment($idSalesShipment)
            ->findOne()
            ->setFkSalesShipmentType($idSalesShipmentType)
            ->save();
    }

    protected function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    protected function getSalesShipmentTypeQuery(): SpySalesShipmentTypeQuery
    {
        return SpySalesShipmentTypeQuery::create();
    }

    protected function getSalesShipmentQuery(): SpySalesShipmentQuery
    {
        return SpySalesShipmentQuery::create();
    }
}
