<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package MagoArab\BlockCustomer\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Set default configuration values
        $configData = [
            [
                'scope' => 'default',
                'scope_id' => 0,
                'path' => 'magoarab_blockcustomer/general/enabled',
                'value' => '1'
            ],
            [
                'scope' => 'default',
                'scope_id' => 0,
                'path' => 'magoarab_blockcustomer/general/block_message',
                'value' => 'Your account has been blocked. Please contact customer support for assistance.'
            ],
            [
                'scope' => 'default',
                'scope_id' => 0,
                'path' => 'magoarab_blockcustomer/general/check_backup_phone',
                'value' => '1'
            ],
            [
                'scope' => 'default',
                'scope_id' => 0,
                'path' => 'magoarab_blockcustomer/general/log_blocked_attempts',
                'value' => '1'
            ]
        ];

        foreach ($configData as $data) {
            $setup->getConnection()->insertOnDuplicate(
                $setup->getTable('core_config_data'),
                $data,
                ['value']
            );
        }

        $setup->endSetup();
    }
}
