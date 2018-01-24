<?php
/**
 * Copyright © Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Email\Plugin\Mail;

use Diepxuan\Email\Helper\Data as SmtpHelper;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\Phrase;
use Psr\Log\LoggerInterface;

/**
 * Class Transport
 * @package Diepxuan\Email\Plugin\Mail
 */
class Transport extends \Zend_Mail_Transport_Smtp
{
    /**
     * @var \Magento\Framework\Mail\MessageInterface
     */
    protected $_message;

    /**
     * @var \Diepxuan\Email\Helper\Data
     */
    protected $helper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Transport constructor.
     *
     * @param \Magento\Framework\Mail\MessageInterface $message
     * @param \Diepxuan\Email\Helper\Data              $helper
     * @param \Psr\Log\LoggerInterface                 $logger
     */
    public function __construct(
        MessageInterface $message,
        SmtpHelper $helper,
        LoggerInterface $logger
    ) {
        if (!$message instanceof \Zend_Mail) {
            throw new \InvalidArgumentException('The message should be an instance of \Zend_Mail');
        }
        $this->_message = $message;
        $this->helper   = $helper;
        $this->logger   = $logger;
    }

    /**
     * @param \Magento\Framework\Mail\TransportInterface $subject
     * @param \Closure                                   $proceed
     *
     * @throws \Magento\Framework\Exception\MailException
     */
    public function aroundSendMessage(
        TransportInterface $subject,
        \Closure $proceed
    ) {
        if (!$this->helper->isActive()) {
            $proceed();
        }

        $this->sendSmtpMessage();
    }

    /**
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendSmtpMessage()
    {
        try {
            if ($this->getMessage()->getDate() === null) {
                $this->getMessage()->setDate();
            }

            if ($this->helper->isOverride()) {
                $this->getMessage()->clearFrom();
                $this->getMessage()->setFrom($this->helper->getUsername());
                $this->getMessage()->setReplyTo($this->helper->getUsername());
            }

            $config = [
                'name' => $this->helper->getSmtpName(),
                'port' => $this->helper->getSmtpPort(),
            ];

            if (($auth = strtolower($this->helper->getAuthType())) != 'none') {
                $config['auth']     = $auth;
                $config['username'] = $this->helper->getUsername();
                $config['password'] = $this->helper->getPassword();
            }

            if (($ssl = $this->helper->getSmtpType()) != 'none') {
                $config['ssl'] = $ssl;
            }

            $this->initialize($this->helper->getSmtpHost(), $config);

            $this->send($this->getMessage());

            $this->getLogger()->critical($this->getMessage()->getReplyTo());
            $this->getLogger()->critical(print_r($this->getMessage()->getHeaders(), true));
        } catch (\Exception $e) {
            $this->getLogger()->critical($e->getMessage());

            throw new MailException(
                new Phrase($e->getMessage()),
                $e
            );
        }
    }

    /**
     * @param string $host
     * @param array  $config
     */
    public function initialize($host = '127.0.0.1', array $config = [])
    {
        parent::__construct($host, $config);
    }

    /**
     * Send a mail using this transport
     *
     * @return void
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendMessage()
    {
        try {
            parent::send($this->_message);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\MailException(new \Magento\Framework\Phrase($e->getMessage()), $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
