<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Plugin\Checkout\Block;

use Magento\Checkout\Block\Onepage;
use MagoArab\BlockCustomer\Model\Service\PhoneBlockingService;
use Magento\Customer\Model\Session;

/**
 * Class OnepagePlugin
 * @package MagoArab\BlockCustomer\Plugin\Checkout\Block
 */
class OnepagePlugin
{
    /**
     * @var PhoneBlockingService
     */
    protected $phoneBlockingService;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @param PhoneBlockingService $phoneBlockingService
     * @param Session $customerSession
     */
    public function __construct(
        PhoneBlockingService $phoneBlockingService,
        Session $customerSession
    ) {
        $this->phoneBlockingService = $phoneBlockingService;
        $this->customerSession = $customerSession;
    }

    /**
     * Add blocked customer warning to checkout
     *
     * @param Onepage $subject
     * @param string $result
     * @return string
     */
    public function afterToHtml(Onepage $subject, $result)
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $result;
        }

        $customer = $this->customerSession->getCustomer();
        $phoneNumber = $customer->getTelephone();

        if (empty($phoneNumber)) {
            return $result;
        }

        // Check if customer is blocked
        if ($this->phoneBlockingService->isPhoneBlocked($phoneNumber, $customer->getId(), $customer->getEmail())) {
            $blockMessage = $this->phoneBlockingService->getBlockMessage();
            $warningHtml = '<div class="message message-error error" style="margin: 20px 0;">
                <div data-ui-id="message-error">' . $blockMessage . '</div>
            </div>';
            
            // Insert warning at the beginning of the checkout content
            $result = $warningHtml . $result;
        }

        return $result;
    }
}
