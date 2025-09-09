<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Model\BlockedCustomer;

use MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Psr\Log\LoggerInterface;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var \MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        
        // Debug logging
        $this->logger->info('DataProvider constructed with name: ' . $name);
        $this->logger->info('Primary field: ' . $primaryFieldName);
        $this->logger->info('Request field: ' . $requestFieldName);
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
        
        $this->loadedData = [];
        $items = $this->collection->getItems();
        
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
        }
        
        // Check for persisted data from form submission
        $data = $this->dataPersistor->get('blocked_customer');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('blocked_customer');
        }
        
        // For new forms (no ID in request), return empty structure to show form fields
        if (empty($this->loadedData)) {
            // Return empty data structure that matches the form fields
            $this->loadedData = [
                '' => [
                    'phone_number' => '',
                    'backup_phone_number' => '',
                    'customer_email' => '',
                    'reason' => '',
                    'is_active' => 1
                ]
            ];
        }

        return $this->loadedData;
    }
}