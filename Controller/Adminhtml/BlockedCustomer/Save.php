<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use MagoArab\BlockCustomer\Model\BlockedCustomerFactory;
use MagoArab\BlockCustomer\Api\BlockedCustomerRepositoryInterface;

/**
 * Class Save
 * @package MagoArab\BlockCustomer\Controller\Adminhtml\BlockedCustomer
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'MagoArab_BlockCustomer::blocked_customers';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

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
     * @param DataPersistorInterface $dataPersistor
     * @param BlockedCustomerFactory $blockedCustomerFactory
     * @param BlockedCustomerRepositoryInterface $blockedCustomerRepository
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        BlockedCustomerFactory $blockedCustomerFactory,
        BlockedCustomerRepositoryInterface $blockedCustomerRepository
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->blockedCustomerFactory = $blockedCustomerFactory;
        $this->blockedCustomerRepository = $blockedCustomerRepository;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('block_id');

            $model = $this->blockedCustomerFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This blocked customer no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            // Set blocked by to current admin user
            $data['blocked_by'] = $this->_auth->getUser()->getId();

            $model->setData($data);

            try {
                $this->blockedCustomerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the blocked customer.'));
                $this->dataPersistor->clear('blocked_customer');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['block_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the blocked customer.'));
            }

            $this->dataPersistor->set('blocked_customer', $data);
            return $resultRedirect->setPath('*/*/edit', ['block_id' => $this->getRequest()->getParam('block_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
