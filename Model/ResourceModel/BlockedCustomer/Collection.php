<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer
 */
class Collection extends AbstractCollection
{
    /**
     * ID field name
     *
     * @var string
     */
    protected $_idFieldName = 'block_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'magoarab_blocked_customer_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'blocked_customer_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \MagoArab\BlockCustomer\Model\BlockedCustomer::class,
            \MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer::class
        );
    }

    /**
     * Get SQL for get record count
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(\Magento\Framework\DB\Select::COLUMNS);
        $countSelect->columns('COUNT(*)');
        return $countSelect;
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        return $this;
    }
}
