<?php
/**
 * Copyright © Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Email\Model\Config\Source\Email;

use \Magento\Framework\Option\ArrayInterface;

/**
 * Class Type
 * @package Diepxuan\Email\Model\Config\Source\Email
 */
class Smtptype implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'none', 'label' => __('None')],
            ['value' => 'ssl', 'label' => 'SSL (Gmail / Google Apps)'],
            ['value' => 'tls', 'label' => 'TLS (Gmail / Google Apps)'],
        ];
    }
}
