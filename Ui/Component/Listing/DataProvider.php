<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Ui\Component\Listing;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;

/**
 * Class DataProvider
 * @package MagoArab\BlockCustomer\Ui\Component\Listing
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider implements DataProviderInterface
{
    /**
     * @var \MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer\Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer\CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        $this->loadedData = [];
        foreach ($items as $blockedCustomer) {
            $this->loadedData[$blockedCustomer->getId()] = $blockedCustomer->getData();
        }
        return $this->loadedData;
    }
}
