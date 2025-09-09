<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface BlockedCustomerSearchResultsInterface
 * @package MagoArab\BlockCustomer\Api\Data
 */
interface BlockedCustomerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocked customers list
     *
     * @return BlockedCustomerInterface[]
     */
    public function getItems();

    /**
     * Set blocked customers list
     *
     * @param BlockedCustomerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
