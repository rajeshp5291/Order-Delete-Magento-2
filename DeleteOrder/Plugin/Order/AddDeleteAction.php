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

use Magento\Ui\Component\MassAction;

/**
 * Class AddDeleteAction
 * @package Mysticwebdesigns\DeleteOrder\Plugin\Order
 */
 
class AddDeleteAction
{
    /**
     * @var _scopeConfig
     * @var _authorization
     * @var XML_PATH_DELETE_ORDER
     */
    protected $_scopeConfig;
    
    protected $_authorization;
    const XML_PATH_DELETE_ORDER = 'delete_order/general/enabled';
    
    /**
     * AddDeleteAction constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
     \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
     \Magento\Framework\AuthorizationInterface $authorization
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_authorization = $authorization;
    }

    /**
     * @param MassAction $object
     * @param $result
     * @return mixed
     */
    public function afterGetChildComponents(MassAction $object, $result)
    {
        if (!isset($result['action_delete'])) {
            return $result;
        }

        if (!$this->_authorization->isAllowed('Magento_Sales::delete') || !$this->getStatus()) {
            unset($result['action_delete']);
        }
        return $result;
    }

    /**
     * @return mixed
     */
	public function getStatus() {
		$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
		return $this->_scopeConfig->getValue(self::XML_PATH_DELETE_ORDER, $storeScope);
	}
}
