<?php
/**
 * Copyright © MagoArab. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagoArab\BlockCustomer\Api\Data;

/**
 * Interface BlockedCustomerInterface
 * @package MagoArab\BlockCustomer\Api\Data
 */
interface BlockedCustomerInterface
{
    const BLOCK_ID = 'block_id';
    const PHONE_NUMBER = 'phone_number';
    const BACKUP_PHONE_NUMBER = 'backup_phone_number';
    const CUSTOMER_ID = 'customer_id';
    const CUSTOMER_EMAIL = 'customer_email';
    const REASON = 'reason';
    const BLOCKED_BY = 'blocked_by';
    const IS_ACTIVE = 'is_active';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get block ID
     *
     * @return int|null
     */
    public function getBlockId();

    /**
     * Set block ID
     *
     * @param int $blockId
     * @return $this
     */
    public function setBlockId($blockId);

    /**
     * Get phone number
     *
     * @return string|null
     */
    public function getPhoneNumber();

    /**
     * Set phone number
     *
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber($phoneNumber);

    /**
     * Get backup phone number
     *
     * @return string|null
     */
    public function getBackupPhoneNumber();

    /**
     * Set backup phone number
     *
     * @param string $backupPhoneNumber
     * @return $this
     */
    public function setBackupPhoneNumber($backupPhoneNumber);

    /**
     * Get customer ID
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer ID
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * Get customer email
     *
     * @return string|null
     */
    public function getCustomerEmail();

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail);

    /**
     * Get reason
     *
     * @return string|null
     */
    public function getReason();

    /**
     * Set reason
     *
     * @param string $reason
     * @return $this
     */
    public function setReason($reason);

    /**
     * Get blocked by
     *
     * @return int|null
     */
    public function getBlockedBy();

    /**
     * Set blocked by
     *
     * @param int $blockedBy
     * @return $this
     */
    public function setBlockedBy($blockedBy);

    /**
     * Get is active
     *
     * @return int|null
     */
    public function getIsActive();

    /**
     * Set is active
     *
     * @param int $isActive
     * @return $this
     */
    public function setIsActive($isActive);

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}
