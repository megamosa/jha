<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use MagoArab\BlockCustomer\Model\BlockedCustomerFactory;
use MagoArab\BlockCustomer\Api\BlockedCustomerRepositoryInterface;

/**
 * Class Delete
 * @package MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer
 */
class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'MagoArab_BlockCustomer::blocked_customers';

    /**
     * @var BlockedCustomerFactory
     */
    protected $blockedCustomerFactory;

    /**
     * @var BlockedCustomerRepositoryInterface
     */
    protected $blockedCustomerRepository;

    /**
     * @param Context $context
     * @param BlockedCustomerFactory $blockedCustomerFactory
     * @param BlockedCustomerRepositoryInterface $blockedCustomerRepository
     */
    public function __construct(
        Context $context,
        BlockedCustomerFactory $blockedCustomerFactory,
        BlockedCustomerRepositoryInterface $blockedCustomerRepository
    ) {
        $this->blockedCustomerFactory = $blockedCustomerFactory;
        $this->blockedCustomerRepository = $blockedCustomerRepository;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('block_id');

        if ($id) {
            try {
                $model = $this->blockedCustomerFactory->create();
                $model->load($id);
                $this->blockedCustomerRepository->delete($model);
                $this->messageManager->addSuccessMessage(__('You deleted the blocked customer.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['block_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a blocked customer to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
