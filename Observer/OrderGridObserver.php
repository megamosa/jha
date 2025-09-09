<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use MagoArab\BlockCustomer\Model\Service\PhoneBlockingService;

/**
 * Class OrderGridObserver
 * @package MagoArab\BlockCustomer\Observer
 */
class OrderGridObserver implements ObserverInterface
{
    /**
     * @var PhoneBlockingService
     */
    protected $phoneBlockingService;

    /**
     * @param PhoneBlockingService $phoneBlockingService
     */
    public function __construct(
        PhoneBlockingService $phoneBlockingService
    ) {
        $this->phoneBlockingService = $phoneBlockingService;
    }

    /**
     * Add blocked customer indicator to order grid
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order) {
            return;
        }

        $billingAddress = $order->getBillingAddress();
        if (!$billingAddress) {
            return;
        }

        $phoneNumber = $billingAddress->getTelephone();
        if (empty($phoneNumber)) {
            return;
        }

        // Check if phone number is blocked
        if ($this->phoneBlockingService->isPhoneBlocked($phoneNumber, $order->getCustomerId(), $order->getCustomerEmail())) {
            // Add custom attribute to mark order as blocked
            $order->setData('is_blocked_customer', true);
            $order->setData('blocked_customer_reason', $this->phoneBlockingService->getBlockedCustomerInfo($phoneNumber)['reason'] ?? 'No reason provided');
        }
    }
}
