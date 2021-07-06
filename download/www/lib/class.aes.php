<?php

use Xentral\Core\LegacyConfig\ConfigLoader;

/**
 * Wrapper to provide backwards compatibility for custom code.
 *
 * The class that was in this file earlier has been replace by the
 * \phpseclib3\Crypt\AES class. This file now provides only backwards
 * compatibility for code that is still using the old class.
 *
 * To update your custom code, replace this:
 *
 *     $aes = new AES($key);
 *
 * with:
 *
 *     $aes = new \phpseclib3\Crypt\AES();
 *     $aes->setKey($key);
 *
 * @deprecated
 */
class AES extends \phpseclib3\Crypt\AES
{
    /**
     * AES constructor.
     *
     * @param $key
     */
    public function __construct($key)
    {
        parent::__construct('ecb');
        $this->logDeprecationMessage();
        $this->setKey($key);
    }

    /**
     * Add a log entry that notifies about the deprecation of this class.
     */
    private function logDeprecationMessage()
    {
        $basePath = dirname(dirname(__DIR__));

        require_once $basePath . '/xentral_autoloader.php';

        $config = ConfigLoader::load();
        $app = new ApplicationCore($config);

        $backtrace = debug_backtrace();
        $file = $backtrace[1]['file'];
        $line = $backtrace[1]['line'];

        /** @var \Xentral\Components\Logger\Logger $logger */
        $logger = $app->Container->get('Logger');
        $logger->notice('The class AES in www/lib/class.aes.php has been deprecated. ' .
            "Use the \phpseclib3\Crypt\AES class instead. The call site was {$file}, line: {$line}.");
    }
}
