<?php
/**
 * Copyright © Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Email\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Diepxuan\Email\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SMTP_ACTIVE   = 'system/smtp/active';
    const SMTP_NAME     = 'system/smtp/smtpname';
    const SMTP_HOST     = 'system/smtp/smtphost';
    const SMTP_PORT     = 'system/smtp/smtpport';
    const SMTP_TYPE     = 'system/smtp/smtptype';
    const SMTP_AUTH     = 'system/smtp/authtype';
    const SMTP_USER     = 'system/smtp/username';
    const SMTP_PASS     = 'system/smtp/password';
    const SMTP_OVERRIDE = 'system/smtp/override';

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->scopeConfig->isSetFlag(self::SMTP_ACTIVE, ScopeInterface::SCOPE_STORE);
    }

    /**
     *  system config host
     *
     * @return string
     */
    public function getSmtpName()
    {
        return $this->scopeConfig->getValue(self::SMTP_NAME, ScopeInterface::SCOPE_STORE);
    }

    /**
     *  system config host
     *
     * @return string
     */
    public function getSmtpHost()
    {
        return $this->scopeConfig->getValue(self::SMTP_HOST, ScopeInterface::SCOPE_STORE);
    }

    /**
     *  system config port
     *
     * @return string
     */
    public function getSmtpPort()
    {
        return $this->scopeConfig->getValue(self::SMTP_PORT, ScopeInterface::SCOPE_STORE);
    }

    /**
     *  system config type
     *
     * @return string
     */
    public function getSmtpType()
    {
        return $this->scopeConfig->getValue(self::SMTP_TYPE, ScopeInterface::SCOPE_STORE);
    }

    /**
     *  system config Authentication method
     *
     * @return string
     */
    public function getAuthType()
    {
        return $this->scopeConfig->getValue(self::SMTP_AUTH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get system config username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->scopeConfig->getValue(self::SMTP_USER, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get system config password
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->scopeConfig->getValue(self::SMTP_PASS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get system override email from
     *
     * @return mixed
     */
    public function isOverride()
    {
        return $this->scopeConfig->isSetFlag(self::SMTP_OVERRIDE, ScopeInterface::SCOPE_STORE);
    }
}
