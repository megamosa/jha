<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Block\Adminhtml\BlockedCustomer;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

/**
 * Class Edit
 * @package MagoArab\BlockCustomer\Block\Adminhtml\BlockedCustomer
 */
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        LoggerInterface $logger,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->logger = $logger;
        parent::__construct($context, $data);
        $this->setTemplate('MagoArab_BlockCustomer::edit.phtml');
        $this->logger->info('MagoArab BlockCustomer: Edit Block constructor called');
    }

    /**
     * Initialize blocked customer edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'block_id';
        $this->_blockGroup = 'MagoArab_BlockCustomer';
        $this->_controller = 'adminhtml_blockedCustomer';

        parent::_construct();

        if ($this->_isAllowedAction('MagoArab_BlockCustomer::blocked_customers')) {
            $this->buttonList->update('save', 'label', __('Save Blocked Customer'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('MagoArab_BlockCustomer::blocked_customers')) {
            $this->buttonList->update('delete', 'label', __('Delete Blocked Customer'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded blocked customer
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('magoarab_blocked_customer')->getId()) {
            return __("Edit Blocked Customer '%1'", $this->escapeHtml($this->_coreRegistry->registry('magoarab_blocked_customer')->getPhoneNumber()));
        } else {
            return __('New Blocked Customer');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('blocked_customer_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'blocked_customer_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'blocked_customer_content');
                }
            };
        ";
        
        $this->addChild('form', 'MagoArab\BlockCustomer\Block\Adminhtml\BlockedCustomer\Edit\Form');
        
        return parent::_prepareLayout();
    }
}