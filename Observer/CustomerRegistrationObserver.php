<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use MagoArab\BlockCustomer\Model\Service\PhoneBlockingService;

/**
 * Class CustomerRegistrationObserver
 * @package MagoArab\BlockCustomer\Observer
 */
class CustomerRegistrationObserver implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var PhoneBlockingService
     */
    protected $phoneBlockingService;

    /**
     * @param ManagerInterface $messageManager
     * @param PhoneBlockingService $phoneBlockingService
     */
    public function __construct(
        ManagerInterface $messageManager,
        PhoneBlockingService $phoneBlockingService
    ) {
        $this->messageManager = $messageManager;
        $this->phoneBlockingService = $phoneBlockingService;
    }

    /**
     * Check if customer phone is blocked during registration
     *
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if (!$customer) {
            return;
        }

        $phoneNumber = $customer->getTelephone();
        if (empty($phoneNumber)) {
            return;
        }

        // Check if phone number is blocked
        if ($this->phoneBlockingService->isPhoneBlocked($phoneNumber, $customer->getId(), $customer->getEmail())) {
            // Block registration silently - no message to customer
            // Just prevent registration without showing error
            return;
        }
    }
}
