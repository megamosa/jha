<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Block\Adminhtml\BlockedCustomer\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use MagoArab\BlockCustomer\Api\BlockedCustomerRepositoryInterface;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var BlockedCustomerRepositoryInterface
     */
    protected $blockedCustomerRepository;

    /**
     * @param Context $context
     * @param BlockedCustomerRepositoryInterface $blockedCustomerRepository
     */
    public function __construct(
        Context $context,
        BlockedCustomerRepositoryInterface $blockedCustomerRepository
    ) {
        $this->context = $context;
        $this->blockedCustomerRepository = $blockedCustomerRepository;
    }

    /**
     * Return model ID
     *
     * @return int|null
     */
    public function getModelId()
    {
        try {
            $blockId = $this->context->getRequest()->getParam('block_id');
            if ($blockId) {
                return $this->blockedCustomerRepository->getById($blockId)->getId();
            }
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}