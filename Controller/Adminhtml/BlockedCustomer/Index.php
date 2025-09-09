<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer
 */
class Index extends Action
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
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagoArab_BlockCustomer::blocked_customers');
        $resultPage->addBreadcrumb(__('Blocked Customers'), __('Blocked Customers'));
        $resultPage->addBreadcrumb(__('Manage Blocked Customers'), __('Manage Blocked Customers'));
        $resultPage->getConfig()->getTitle()->prepend(__('Blocked Customers'));

        return $resultPage;
    }
}
