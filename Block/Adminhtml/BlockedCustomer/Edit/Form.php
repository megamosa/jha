<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Block\Adminhtml\BlockedCustomer\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Form
 * @package MagoArab\BlockCustomer\Block\Adminhtml\BlockedCustomer\Edit
 */
class Form extends Generic
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->logger = $logger;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blocked_customer_form');
        $this->setTitle(__('Blocked Customer Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('magoarab_blocked_customer');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/save', ['block_id' => $model->getId()]),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );

        $form->setHtmlIdPrefix('blocked_customer_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model && $model->getId()) {
            $fieldset->addField('block_id', 'hidden', ['name' => 'block_id']);
        }

        $fieldset->addField(
            'phone_number',
            'text',
            [
                'name' => 'phone_number',
                'label' => __('Phone Number'),
                'title' => __('Phone Number'),
                'required' => true,
                'class' => 'validate-phone-number'
            ]
        );

        $fieldset->addField(
            'backup_phone_number',
            'text',
            [
                'name' => 'backup_phone_number',
                'label' => __('Backup Phone Number'),
                'title' => __('Backup Phone Number'),
                'class' => 'validate-phone-number'
            ]
        );

        $fieldset->addField(
            'customer_email',
            'text',
            [
                'name' => 'customer_email',
                'label' => __('Customer Email'),
                'title' => __('Customer Email'),
                'class' => 'validate-email'
            ]
        );

        $fieldset->addField(
            'reason',
            'textarea',
            [
                'name' => 'reason',
                'label' => __('Block Reason'),
                'title' => __('Block Reason'),
                'required' => true,
                'style' => 'height: 5em;'
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'name' => 'is_active',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'options' => ['1' => __('Active'), '0' => __('Inactive')]
            ]
        );
        if ($model) {
            $form->setValues($model->getData());
        }
        
        $form->setUseContainer(true);
        $this->setForm($form);
        $this->setTemplate('MagoArab_BlockCustomer::form.phtml');

        return parent::_prepareForm();
    }
}