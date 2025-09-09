<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package MagoArab\BlockCustomer\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'magoarab_blocked_customers'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magoarab_blocked_customers')
        )->addColumn(
            'block_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Block ID'
        )->addColumn(
            'phone_number',
            Table::TYPE_TEXT,
            20,
            ['nullable' => false],
            'Phone Number'
        )->addColumn(
            'backup_phone_number',
            Table::TYPE_TEXT,
            20,
            ['nullable' => true],
            'Backup Phone Number'
        )->addColumn(
            'customer_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Customer ID'
        )->addColumn(
            'customer_email',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Customer Email'
        )->addColumn(
            'reason',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => true],
            'Block Reason'
        )->addColumn(
            'blocked_by',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Blocked By Admin User ID'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '1'],
            'Is Active'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created At'
        )->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Updated At'
        )->addIndex(
            $installer->getIdxName('magoarab_blocked_customers', ['phone_number']),
            ['phone_number']
        )->addIndex(
            $installer->getIdxName('magoarab_blocked_customers', ['backup_phone_number']),
            ['backup_phone_number']
        )->addIndex(
            $installer->getIdxName('magoarab_blocked_customers', ['customer_id']),
            ['customer_id']
        )->addIndex(
            $installer->getIdxName('magoarab_blocked_customers', ['is_active']),
            ['is_active']
        )->setComment(
            'MagoArab Blocked Customers Table'
        );

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'magoarab_blocked_attempts_log'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magoarab_blocked_attempts_log')
        )->addColumn(
            'log_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Log ID'
        )->addColumn(
            'phone_number',
            Table::TYPE_TEXT,
            20,
            ['nullable' => false],
            'Phone Number'
        )->addColumn(
            'customer_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true],
            'Customer ID'
        )->addColumn(
            'customer_email',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Customer Email'
        )->addColumn(
            'attempt_type',
            Table::TYPE_TEXT,
            50,
            ['nullable' => false],
            'Attempt Type (checkout, registration, etc.)'
        )->addColumn(
            'ip_address',
            Table::TYPE_TEXT,
            45,
            ['nullable' => true],
            'IP Address'
        )->addColumn(
            'user_agent',
            Table::TYPE_TEXT,
            500,
            ['nullable' => true],
            'User Agent'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created At'
        )->addIndex(
            $installer->getIdxName('magoarab_blocked_attempts_log', ['phone_number']),
            ['phone_number']
        )->addIndex(
            $installer->getIdxName('magoarab_blocked_attempts_log', ['customer_id']),
            ['customer_id']
        )->addIndex(
            $installer->getIdxName('magoarab_blocked_attempts_log', ['created_at']),
            ['created_at']
        )->setComment(
            'MagoArab Blocked Attempts Log Table'
        );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
