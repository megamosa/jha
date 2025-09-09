<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use MagoArab\BlockCustomer\Model\BlockedCustomerFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer
 */
class Edit extends Action
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
     * @var BlockedCustomerFactory
     */
    protected $blockedCustomerFactory;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param BlockedCustomerFactory $blockedCustomerFactory
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        BlockedCustomerFactory $blockedCustomerFactory,
        Registry $coreRegistry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->blockedCustomerFactory = $blockedCustomerFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('block_id');
        $model = $this->blockedCustomerFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This blocked customer no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('magoarab_blocked_customer', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagoArab_BlockCustomer::blocked_customers');
        $resultPage->addBreadcrumb(__('Blocked Customers'), __('Blocked Customers'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Blocked Customer') : __('New Blocked Customer'),
            $id ? __('Edit Blocked Customer') : __('New Blocked Customer')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Blocked Customers'));
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ? $model->getPhoneNumber() : __('New Blocked Customer')
        );

        return $resultPage;
    }
}
