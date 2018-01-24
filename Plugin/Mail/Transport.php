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
class Transport extends \Magento\Framework\Mail\Transport
{

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
     * @param null                                     $parameters
     */
    public function __construct(
        MessageInterface $message,
        SmtpHelper $helper,
        LoggerInterface $logger,
        $parameters = null
    ) {
        parent::__construct($message, $parameters);

        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Mail\TransportInterface $subject
     * @param \Closure                                   $proceed
     *
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Zend_Mail_Exception
     */
    public function aroundSendMessage(
        TransportInterface $subject,
        \Closure $proceed
    ) {
        if (!$this->helper->isActive()) {
            $proceed();
        }

//        $this->getLogger()->critical(print_r(get_class_methods($this->getMessage()), true));
//        $this->getLogger()->critical(print_r(get_class_methods($subject->getMessage()), true));
//        $this->getLogger()->critical($this->getMessage()->getBodyText());
//        $this->getLogger()->critical($this->getMessage()->getFrom());

//        $message = $subject->getMessage();
        $this->sendSmtpMessage();

//        $this->getLogger()->critical(print_r($this->getMessage()->getHeaders(), true));

//        $this->getLogger()->critical(print_r($this->getMessage()->getBody(), true));

        return;
    }

    /**
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Zend_Mail_Exception
     */
    public function sendSmtpMessage()
    {
        if ($this->getMessage() instanceof \Zend_mail) {
            if ($this->getMessage()->getDate() === null) {
                $this->getMessage()->setDate();
            }
        }

        try {

            $this->getMessage()->clearFrom();
            $this->getMessage()->setFrom($this->helper->getUsername());

            $config = [
                'name' => $this->helper->getSmtpHost(),
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

            $result = $this->send($this->getMessage());

//            $this->getLogger()->critical($result);
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
        if (isset($config['name'])) {
            $this->_name = $config['name'];
        }
        if (isset($config['port'])) {
            $this->_port = $config['port'];
        }
        if (isset($config['auth'])) {
            $this->_auth = $config['auth'];
        }

        $this->_host   = $host;
        $this->_config = $config;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
