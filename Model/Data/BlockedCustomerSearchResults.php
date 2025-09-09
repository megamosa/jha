<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Model\Data;

use MagoArab\BlockCustomer\Api\Data\BlockedCustomerSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Class BlockedCustomerSearchResults
 * @package MagoArab\BlockCustomer\Model\Data
 */
class BlockedCustomerSearchResults extends SearchResults implements BlockedCustomerSearchResultsInterface
{
    /**
     * Get blocked customers list
     *
     * @return \MagoArab\BlockCustomer\Api\Data\BlockedCustomerInterface[]
     */
    public function getItems()
    {
        return $this->_get(self::KEY_ITEMS) === null ? [] : $this->_get(self::KEY_ITEMS);
    }

    /**
     * Set blocked customers list
     *
     * @param \MagoArab\BlockCustomer\Api\Data\BlockedCustomerInterface[] $items
     * @return $this
     */
    public function setItems(array $items)
    {
        return $this->setData(self::KEY_ITEMS, $items);
    }
}
