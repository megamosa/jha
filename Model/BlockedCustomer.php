<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Model;

use Magento\Framework\Model\AbstractModel;
use MagoArab\BlockCustomer\Api\Data\BlockedCustomerInterface;

/**
 * Class BlockedCustomer
 * @package MagoArab\BlockCustomer\Model
 */
class BlockedCustomer extends AbstractModel implements BlockedCustomerInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'magoarab_blocked_customer';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'magoarab_blocked_customer';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'magoarab_blocked_customer';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\MagoArab\BlockCustomer\Model\ResourceModel\BlockedCustomer::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        $values['is_active'] = '1';
        return $values;
    }

    /**
     * Get block ID
     *
     * @return int|null
     */
    public function getBlockId()
    {
        return $this->getData(self::BLOCK_ID);
    }

    /**
     * Set block ID
     *
     * @param int $blockId
     * @return $this
     */
    public function setBlockId($blockId)
    {
        return $this->setData(self::BLOCK_ID, $blockId);
    }

    /**
     * Get phone number
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->getData(self::PHONE_NUMBER);
    }

    /**
     * Set phone number
     *
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber($phoneNumber)
    {
        return $this->setData(self::PHONE_NUMBER, $phoneNumber);
    }

    /**
     * Get backup phone number
     *
     * @return string|null
     */
    public function getBackupPhoneNumber()
    {
        return $this->getData(self::BACKUP_PHONE_NUMBER);
    }

    /**
     * Set backup phone number
     *
     * @param string $backupPhoneNumber
     * @return $this
     */
    public function setBackupPhoneNumber($backupPhoneNumber)
    {
        return $this->setData(self::BACKUP_PHONE_NUMBER, $backupPhoneNumber);
    }

    /**
     * Get customer ID
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer ID
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get customer email
     *
     * @return string|null
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * Get reason
     *
     * @return string|null
     */
    public function getReason()
    {
        return $this->getData(self::REASON);
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return $this
     */
    public function setReason($reason)
    {
        return $this->setData(self::REASON, $reason);
    }

    /**
     * Get blocked by
     *
     * @return int|null
     */
    public function getBlockedBy()
    {
        return $this->getData(self::BLOCKED_BY);
    }

    /**
     * Set blocked by
     *
     * @param int $blockedBy
     * @return $this
     */
    public function setBlockedBy($blockedBy)
    {
        return $this->setData(self::BLOCKED_BY, $blockedBy);
    }

    /**
     * Get is active
     *
     * @return int|null
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set is active
     *
     * @param int $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
