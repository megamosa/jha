<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Block\Adminhtml\Order;

use Magento\Backend\Block\Template;
use Magento\Framework\Registry;
use MagoArab\BlockCustomer\Model\Service\PhoneBlockingService;

/**
 * Class BlockedCustomerWarning
 * @package MagoArab\BlockCustomer\Block\Adminhtml\Order
 */
class BlockedCustomerWarning extends Template
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var PhoneBlockingService
     */
    protected $phoneBlockingService;

    /**
     * @param Template\Context $context
     * @param Registry $registry
     * @param PhoneBlockingService $phoneBlockingService
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        PhoneBlockingService $phoneBlockingService,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->phoneBlockingService = $phoneBlockingService;
        parent::__construct($context, $data);
    }

    /**
     * Check if current order is from blocked customer
     *
     * @return bool
     */
    public function isBlockedCustomer()
    {
        $order = $this->registry->registry('current_order');
        if (!$order) {
            return false;
        }

        $billingAddress = $order->getBillingAddress();
        if (!$billingAddress) {
            return false;
        }

        $phoneNumber = $billingAddress->getTelephone();
        if (empty($phoneNumber)) {
            return false;
        }

        return $this->phoneBlockingService->isPhoneBlocked(
            $phoneNumber,
            $order->getCustomerId(),
            $order->getCustomerEmail()
        );
    }

    /**
     * Get blocked customer info
     *
     * @return array
     */
    public function getBlockedCustomerInfo()
    {
        $order = $this->registry->registry('current_order');
        if (!$order) {
            return [];
        }

        $billingAddress = $order->getBillingAddress();
        if (!$billingAddress) {
            return [];
        }

        $phoneNumber = $billingAddress->getTelephone();
        if (empty($phoneNumber)) {
            return [];
        }

        return $this->phoneBlockingService->getBlockedCustomerInfo($phoneNumber);
    }

    /**
     * Get order phone number
     *
     * @return string
     */
    public function getOrderPhoneNumber()
    {
        $order = $this->registry->registry('current_order');
        if (!$order) {
            return '';
        }

        $billingAddress = $order->getBillingAddress();
        if (!$billingAddress) {
            return '';
        }

        return $billingAddress->getTelephone();
    }

    /**
     * Get current order
     *
     * @return \Magento\Sales\Model\Order|null
     */
    public function getOrder()
    {
        return $this->registry->registry('current_order');
    }
}
