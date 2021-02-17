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


namespace Mysticwebdesigns\DeleteOrder\Plugin\Order;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\ObjectManagerInterface;
/**
 * Class AddDeleteButton
 * @package Mysticwebdesigns\DeleteOrder\Plugin\Order
 */
class AddDeleteButton
{
    /**
     * @var _objectManager
     * @var _backendUrl
     * @var _scopeConfig
     * @var _authorization
     * @var XML_PATH_DELETE_ORDERS
     */
     
    protected $_objectManager;
    protected $_backendUrl;
    protected $_scopeConfig;
    protected $_authorization;
    const XML_PATH_DELETE_ORDERS = 'delete_order/general/enabled';
    
    /**
     * AddDeleteButton constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        UrlInterface $backendUrl,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\AuthorizationInterface $authorization
    ) {
        $this->_objectManager = $objectManager;
        $this->_backendUrl = $backendUrl;
        $this->_scopeConfig = $scopeConfig;
        $this->_authorization = $authorization;
    }

    /**
     * @param View $object
     * @return null
     */
    
    public function beforeSetLayout( \Magento\Sales\Block\Adminhtml\Order\View $object )
    {
        if($this->getStatus() && $this->_authorization->isAllowed('Magento_Sales::delete')) {
			$actionURL = $this->_backendUrl->getUrl('deleteorders/order/delete/', ['selected' =>$object->getOrderId()]);
			$message = __('Are you sure you want to delete this order?');
			
			$object->addButton(
				'delete_order',
				[
					'label' => __('Delete'),
					'onclick' => "confirmSetLocation('{$message}', '{$actionURL}')",
					'class' => 'delete_order'
				]
			);
			return null;
       }
    }
    
    /**
     * @return mixed
     */
     public function getStatus() {
		$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
		return $this->_scopeConfig->getValue(self::XML_PATH_DELETE_ORDERS, $storeScope);
     }
}
