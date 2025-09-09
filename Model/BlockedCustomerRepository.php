<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Model;

use MagoArab\BlockCustomer\Api\BlockedCustomerRepositoryInterface;
use MagoArab\BlockCustomer\Api\Data\BlockedCustomerInterface;
use MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer as ResourceBlockedCustomer;
use MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer\CollectionFactory as BlockedCustomerCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class BlockedCustomerRepository
 * @package MagoArab\BlockCustomer\Model
 */
class BlockedCustomerRepository implements BlockedCustomerRepositoryInterface
{
    /**
     * @var ResourceBlockedCustomer
     */
    protected $resource;

    /**
     * @var BlockedCustomerFactory
     */
    protected $blockedCustomerFactory;

    /**
     * @var BlockedCustomerCollectionFactory
     */
    protected $blockedCustomerCollectionFactory;

    /**
     * @param ResourceBlockedCustomer $resource
     * @param BlockedCustomerFactory $blockedCustomerFactory
     * @param BlockedCustomerCollectionFactory $blockedCustomerCollectionFactory
     */
    public function __construct(
        ResourceBlockedCustomer $resource,
        BlockedCustomerFactory $blockedCustomerFactory,
        BlockedCustomerCollectionFactory $blockedCustomerCollectionFactory
    ) {
        $this->resource = $resource;
        $this->blockedCustomerFactory = $blockedCustomerFactory;
        $this->blockedCustomerCollectionFactory = $blockedCustomerCollectionFactory;
    }

    /**
     * Save blocked customer
     *
     * @param BlockedCustomerInterface $blockedCustomer
     * @return BlockedCustomerInterface
     * @throws CouldNotSaveException
     */
    public function save(BlockedCustomerInterface $blockedCustomer)
    {
        try {
            $this->resource->save($blockedCustomer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the blocked customer: %1',
                $exception->getMessage()
            ));
        }
        return $blockedCustomer;
    }

    /**
     * Load blocked customer data by given blocked customer identity
     *
     * @param string $blockId
     * @return BlockedCustomerInterface
     * @throws NoSuchEntityException
     */
    public function getById($blockId)
    {
        $blockedCustomer = $this->blockedCustomerFactory->create();
        $this->resource->load($blockedCustomer, $blockId);
        if (!$blockedCustomer->getId()) {
            throw new NoSuchEntityException(__('Blocked customer with id "%1" does not exist.', $blockId));
        }
        return $blockedCustomer;
    }

    /**
     * Load blocked customer data collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \MagoArab\BlockCustomer\Api\Data\BlockedCustomerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $collection = $this->blockedCustomerCollectionFactory->create();

        $searchResults = new \MagoArab\BlockCustomer\Model\Data\BlockedCustomerSearchResults();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete blocked customer
     *
     * @param BlockedCustomerInterface $blockedCustomer
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(BlockedCustomerInterface $blockedCustomer)
    {
        try {
            $this->resource->delete($blockedCustomer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the blocked customer: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete blocked customer by ID
     *
     * @param string $blockId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById($blockId)
    {
        return $this->delete($this->getById($blockId));
    }
}