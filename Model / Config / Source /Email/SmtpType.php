<?php
namespace Diepxuan\Email\Model\Config\Source\Email;
class SmtpType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'ssl', 'label' => 'SSL'],
            ['value' => 'tls', 'label' => 'TLS']
        ];
    }
}
