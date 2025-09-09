<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Api;

use MagoArab\BlockCustomer\Api\Data\BlockedCustomerInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface BlockedCustomerRepositoryInterface
 * @package MagoArab\BlockCustomer\Api
 */
interface BlockedCustomerRepositoryInterface
{
    /**
     * Save blocked customer
     *
     * @param BlockedCustomerInterface $blockedCustomer
     * @return BlockedCustomerInterface
     * @throws LocalizedException
     */
    public function save(BlockedCustomerInterface $blockedCustomer);

    /**
     * Retrieve blocked customer
     *
     * @param int $blockId
     * @return BlockedCustomerInterface
     * @throws LocalizedException
     */
    public function getById($blockId);

    /**
     * Retrieve blocked customers matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete blocked customer
     *
     * @param BlockedCustomerInterface $blockedCustomer
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(BlockedCustomerInterface $blockedCustomer);

    /**
     * Delete blocked customer by ID
     *
     * @param int $blockId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($blockId);
}
