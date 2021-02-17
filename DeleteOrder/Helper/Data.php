<?php
/**
 * Mysticwebdesigns
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mysticwebdesigns.com 
 * that is bundled with this package in the file Mysticwebdesigns-License.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mysticwebdesigns.com/Mysticwebdesigns-License.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mysticwebdesigns
 * @package     Mysticwebdesigns_DeleteOrder
 * @copyright   Copyright (c) Mysticwebdesigns ( https://www.mysticwebdesigns.com/ )
 * @license     https://www.mysticwebdesigns.com/Mysticwebdesigns-License.txt
 */

namespace Mysticwebdesigns\DeleteOrder\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\ResourceModel\Order;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\ResourceModel\OrderFactory;

/**
 * Class Data
 * @package Mysticwebdesigns\DeleteOrder\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var OrderFactory
     */
    private $orderResourceFactory;

    protected $orderCollectionFactory;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param OrderFactory $orderResourceFactory
     */
    
    public function __construct(
        Context $context,
        CollectionFactory $orderCollectionFactory,
        OrderFactory $orderResourceFactory
    ) {
        $this->orderResourceFactory   = $orderResourceFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;

        parent::__construct($context);
    }

    /**
     * @param $orderId
     */
    public function deleteRecordById($orderId)
    {
        /* @var Order $resource */
        $resourceConnection   = $this->orderResourceFactory->create();
        $connection = $resourceConnection->getConnection();

        /* Delete Invoice */
        $connection->delete($resourceConnection->getTable('sales_invoice_grid'),$connection->quoteInto('order_id = ?', $orderId));

        /** Delete Shipment */
        $connection->delete($resourceConnection->getTable('sales_shipment_grid'),$connection->quoteInto('order_id = ?', $orderId));

        /** Delete Creditmemo */
        $connection->delete($resourceConnection->getTable('sales_creditmemo_grid'),$connection->quoteInto('order_id = ?', $orderId));
    }
}
