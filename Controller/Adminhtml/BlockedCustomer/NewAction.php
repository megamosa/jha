<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use MagoArab\BlockCustomer\Model\BlockedCustomerFactory;
use Psr\Log\LoggerInterface;

/**
 * Class NewAction
 * @package MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer
 */
class NewAction extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'MagoArab_BlockCustomer::blocked_customers';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var BlockedCustomerFactory
     */
    protected $blockedCustomerFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param BlockedCustomerFactory $blockedCustomerFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        BlockedCustomerFactory $blockedCustomerFactory,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->blockedCustomerFactory = $blockedCustomerFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * New action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        // Create new blocked customer model
        $model = $this->blockedCustomerFactory->create();
        
        // Register the model
        $this->registry->register('magoarab_blocked_customer', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagoArab_BlockCustomer::blocked_customers');
        $resultPage->addBreadcrumb(__('Blocked Customers'), __('Blocked Customers'));
        $resultPage->addBreadcrumb(__('New Blocked Customer'), __('New Blocked Customer'));
        $resultPage->getConfig()->getTitle()->prepend(__('New Blocked Customer'));

        return $resultPage;
    }
}
