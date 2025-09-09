<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\UrlInterface;
use MagoArab\BlockCustomer\Model\Service\PhoneBlockingService;
use Psr\Log\LoggerInterface;

/**
 * Class CheckoutProcessObserver
 * @package MagoArab\BlockCustomer\Observer
 */
class CheckoutProcessObserver implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @var PhoneBlockingService
     */
    protected $phoneBlockingService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ManagerInterface $messageManager
     * @param ResponseFactory $responseFactory
     * @param UrlInterface $url
     * @param PhoneBlockingService $phoneBlockingService
     * @param LoggerInterface $logger
     */
    public function __construct(
        ManagerInterface $messageManager,
        ResponseFactory $responseFactory,
        UrlInterface $url,
        PhoneBlockingService $phoneBlockingService,
        LoggerInterface $logger
    ) {
        $this->messageManager = $messageManager;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->phoneBlockingService = $phoneBlockingService;
        $this->logger = $logger;
    }

    /**
     * Check if customer is blocked before processing checkout
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
        if (!$billingAddress || !$billingAddress->getTelephone()) {
            return;
        }

        $phoneNumber = $billingAddress->getTelephone();
        $customerId = $order->getCustomerId();
        $customerEmail = $order->getCustomerEmail();

        // Check if phone number is blocked
        if ($this->phoneBlockingService->isPhoneBlocked($phoneNumber, $customerId, $customerEmail)) {
            $blockedInfo = $this->phoneBlockingService->getBlockedCustomerInfo($phoneNumber);
            
            // Cancel the order silently (no message to customer)
            $order->setState(\Magento\Sales\Model\Order::STATE_CANCELED);
            $order->setStatus(\Magento\Sales\Model\Order::STATE_CANCELED);
            $order->addCommentToStatusHistory(
                'Order canceled: Customer phone number is blocked. Reason: ' . 
                ($blockedInfo['reason'] ?? 'No reason provided') . 
                ' - Customer: ' . ($customerEmail ?: 'Guest') . 
                ' - Phone: ' . $phoneNumber
            );
            
            // Log for admin monitoring
            $this->logger->warning('Blocked customer attempted to place order', [
                'phone_number' => $phoneNumber,
                'customer_id' => $customerId,
                'customer_email' => $customerEmail,
                'order_id' => $order->getId(),
                'block_reason' => $blockedInfo['reason'] ?? 'No reason provided'
            ]);
            
            // Continue with normal order processing (no redirect, no message)
            // The order will be marked as canceled in admin
        }
    }
}
