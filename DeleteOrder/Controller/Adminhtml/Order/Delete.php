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

namespace Mysticwebdesigns\DeleteOrder\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Mysticwebdesigns\DeleteOrder\Helper\Data;

/**
 * Class Delete
 *
 * @package Mysticwebdesigns\DeleteOrder\Controller\Adminhtml\Order
 */

class Delete extends Action
{
    /**
     * @var orderRepository
    */
    protected $orderRepository;
    protected $collectionFactory;
    
    /**
     * @var DeleteOrderHelper
    */
    protected $helperData;
    protected $filter;

    /**
     * Delete constructor.
     * @param Context $context
     */
     
    public function __construct(
		Context $context,
		Filter $filter,
		CollectionFactory $collectionFactory,
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
		Data $helperData
	)
    {
		$this->filter = $filter;
		$this->collectionFactory = $collectionFactory;
		$this->orderRepository = $orderRepository;
		$this->helperData = $helperData;
		parent::__construct($context);
    }
    
    public function execute(){
		
		$params = $this->getRequest()->getParams();
		$paramOrderIds = [];
		
		if (isset($params['excluded']) && $params['excluded'] == "false") {
			$collection = $this->filter->getCollection($this->collectionFactory->create());
            foreach ($collection->getItems() as $order) {
				$paramOrderIds[] = $order->getId();
			}
        } else {
             if (isset($params['selected'])) {
                if (is_array($params['selected'])) {
                    $paramOrderIds = $params['selected'];
                } else {
                    $paramOrderIds = [$params['selected']];
                }
            }
        }
        
		$countDeleteOrder = 0;
        if (count($paramOrderIds) > 0) {
           foreach ($paramOrderIds as $id) {
				$order = $this->orderRepository->get($id);
				$order->delete();
				$this->helperData->deleteRecordById($order->getId());
				$countDeleteOrder++;
			}
			$this->messageManager->addSuccess($countDeleteOrder . __(' order(s) deleted successfully'));
        } else {
            $this->messageManager->addError(__('Unable to delete orders.'));
        }
        $path = "sales/order/index";
		return $this->resultRedirectFactory->create()->setPath($path, []);
	}
}
