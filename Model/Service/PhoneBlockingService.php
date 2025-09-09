<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Model\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Psr\Log\LoggerInterface;

/**
 * Class PhoneBlockingService
 * @package MagoArab\BlockCustomer\Model\Service
 */
class PhoneBlockingService
{
    const XML_PATH_ENABLED = 'magoarab_blockcustomer/general/enabled';
    const XML_PATH_BLOCK_MESSAGE = 'magoarab_blockcustomer/general/block_message';
    const XML_PATH_CHECK_BACKUP_PHONE = 'magoarab_blockcustomer/general/check_backup_phone';
    const XML_PATH_LOG_BLOCKED_ATTEMPTS = 'magoarab_blockcustomer/general/log_blocked_attempts';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ResourceConnection $resourceConnection
     * @param CustomerFactory $customerFactory
     * @param RemoteAddress $remoteAddress
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResourceConnection $resourceConnection,
        CustomerFactory $customerFactory,
        RemoteAddress $remoteAddress,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
        $this->customerFactory = $customerFactory;
        $this->remoteAddress = $remoteAddress;
        $this->logger = $logger;
    }

    /**
     * Check if phone number is blocked
     *
     * @param string $phoneNumber
     * @param int|null $customerId
     * @param string|null $customerEmail
     * @return bool
     */
    public function isPhoneBlocked($phoneNumber, $customerId = null, $customerEmail = null)
    {
        if (!$this->isModuleEnabled()) {
            return false;
        }

        $phoneNumber = $this->normalizePhoneNumber($phoneNumber);
        if (empty($phoneNumber)) {
            return false;
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('magoarab_blocked_customers');

        // Check primary phone number
        $select = $connection->select()
            ->from($tableName, ['block_id', 'reason'])
            ->where('phone_number = ?', $phoneNumber)
            ->where('is_active = ?', 1);

        $blockedRecord = $connection->fetchRow($select);

        if ($blockedRecord) {
            $this->logBlockedAttempt($phoneNumber, $customerId, $customerEmail, 'primary_phone');
            return true;
        }

        // Check backup phone number if enabled
        if ($this->isBackupPhoneCheckEnabled()) {
            $backupSelect = $connection->select()
                ->from($tableName, ['block_id', 'reason'])
                ->where('backup_phone_number = ?', $phoneNumber)
                ->where('is_active = ?', 1);

            $backupBlockedRecord = $connection->fetchRow($backupSelect);

            if ($backupBlockedRecord) {
                $this->logBlockedAttempt($phoneNumber, $customerId, $customerEmail, 'backup_phone');
                return true;
            }
        }

        return false;
    }

    /**
     * Get blocked customer info
     *
     * @param string $phoneNumber
     * @return array|null
     */
    public function getBlockedCustomerInfo($phoneNumber)
    {
        if (!$this->isModuleEnabled()) {
            return null;
        }

        $phoneNumber = $this->normalizePhoneNumber($phoneNumber);
        if (empty($phoneNumber)) {
            return null;
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('magoarab_blocked_customers');

        // Check primary phone number
        $select = $connection->select()
            ->from($tableName, ['*'])
            ->where('phone_number = ?', $phoneNumber)
            ->where('is_active = ?', 1);

        $blockedRecord = $connection->fetchRow($select);

        if ($blockedRecord) {
            return $blockedRecord;
        }

        // Check backup phone number if enabled
        if ($this->isBackupPhoneCheckEnabled()) {
            $backupSelect = $connection->select()
                ->from($tableName, ['*'])
                ->where('backup_phone_number = ?', $phoneNumber)
                ->where('is_active = ?', 1);

            $backupBlockedRecord = $connection->fetchRow($backupSelect);

            if ($backupBlockedRecord) {
                return $backupBlockedRecord;
            }
        }

        return null;
    }

    /**
     * Get block message
     *
     * @return string
     */
    public function getBlockMessage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_BLOCK_MESSAGE,
            ScopeInterface::SCOPE_STORE
        ) ?: 'Your account has been blocked. Please contact customer support for assistance.';
    }

    /**
     * Check if customer has backup phone in Amasty checkout fields
     *
     * @param int $customerId
     * @return string|null
     */
    public function getCustomerBackupPhone($customerId)
    {
        if (!$this->isBackupPhoneCheckEnabled()) {
            return null;
        }

        try {
            $connection = $this->resourceConnection->getConnection();
            
            // First, get the attribute_id for backup phone from amasty_amcheckout_field
            $amastyTable = $this->resourceConnection->getTableName('amasty_amcheckout_field');
            $select = $connection->select()
                ->from($amastyTable, ['attribute_id'])
                ->where('label LIKE ?', '%احتياطي%')
                ->where('enabled = ?', 1)
                ->limit(1);

            $attributeId = $connection->fetchOne($select);

            if (!$attributeId) {
                return null;
            }

            // Get the attribute code from eav_attribute
            $eavTable = $this->resourceConnection->getTableName('eav_attribute');
            $select = $connection->select()
                ->from($eavTable, ['attribute_code'])
                ->where('attribute_id = ?', $attributeId);

            $attributeCode = $connection->fetchOne($select);

            if (!$attributeCode) {
                return null;
            }

            // Get customer attribute value
            $customerTable = $this->resourceConnection->getTableName('customer_entity_' . $attributeCode);
            $select = $connection->select()
                ->from($customerTable, ['value'])
                ->where('entity_id = ?', $customerId);

            $backupPhone = $connection->fetchOne($select);

            return $backupPhone ? $this->normalizePhoneNumber($backupPhone) : null;

        } catch (\Exception $e) {
            $this->logger->error('Error getting customer backup phone: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Normalize phone number
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function normalizePhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return '';
        }

        // Remove all non-numeric characters except +
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);

        // Remove leading zeros
        $phoneNumber = ltrim($phoneNumber, '0');

        // Add country code if missing (assuming Egypt +20)
        if (strpos($phoneNumber, '+') !== 0 && strpos($phoneNumber, '20') !== 0) {
            if (strlen($phoneNumber) == 10) {
                $phoneNumber = '20' . $phoneNumber;
            }
        }

        return $phoneNumber;
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    protected function isModuleEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if backup phone check is enabled
     *
     * @return bool
     */
    protected function isBackupPhoneCheckEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CHECK_BACKUP_PHONE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Log blocked attempt
     *
     * @param string $phoneNumber
     * @param int|null $customerId
     * @param string|null $customerEmail
     * @param string $attemptType
     * @return void
     */
    protected function logBlockedAttempt($phoneNumber, $customerId, $customerEmail, $attemptType)
    {
        if (!$this->scopeConfig->isSetFlag(self::XML_PATH_LOG_BLOCKED_ATTEMPTS, ScopeInterface::SCOPE_STORE)) {
            return;
        }

        try {
            $connection = $this->resourceConnection->getConnection();
            $tableName = $this->resourceConnection->getTableName('magoarab_blocked_attempts_log');

            $data = [
                'phone_number' => $phoneNumber,
                'customer_id' => $customerId,
                'customer_email' => $customerEmail,
                'attempt_type' => $attemptType,
                'ip_address' => $this->remoteAddress->getRemoteAddress(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $connection->insert($tableName, $data);

        } catch (\Exception $e) {
            $this->logger->error('Error logging blocked attempt: ' . $e->getMessage());
        }
    }
}
