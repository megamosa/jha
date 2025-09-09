<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use MagoArab\BlockCustomer\Model\Service\PhoneBlockingService;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class OrderViewObserver
 * @package MagoArab\BlockCustomer\Observer
 */
class OrderViewObserver implements ObserverInterface
{
    /**
     * @var PhoneBlockingService
     */
    protected $phoneBlockingService;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @param PhoneBlockingService $phoneBlockingService
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        PhoneBlockingService $phoneBlockingService,
        ManagerInterface $messageManager
    ) {
        $this->phoneBlockingService = $phoneBlockingService;
        $this->messageManager = $messageManager;
    }

    /**
     * Add warning message for blocked customers in order view
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
            $blockedInfo = $this->phoneBlockingService->getBlockedCustomerInfo($phoneNumber);
            $reason = $blockedInfo['reason'] ?? 'No reason provided';
            
            $message = __(
                '⚠️ WARNING: This customer is BLOCKED! Phone: %1, Reason: %2. DO NOT PROCESS THIS ORDER!',
                $phoneNumber,
                $reason
            );
            
            $this->messageManager->addError($message);
        }
    }
}
