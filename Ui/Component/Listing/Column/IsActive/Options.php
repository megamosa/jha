<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Ui\Component\Listing\Column\IsActive;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Options
 * @package MagoArab\BlockCustomer\Ui\Component\Listing\Column\IsActive
 */
class Options implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Active')],
            ['value' => 0, 'label' => __('Inactive')]
        ];
    }
}
