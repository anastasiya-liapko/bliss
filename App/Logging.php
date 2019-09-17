<?php

namespace App;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class Logging.
 *
 * @package App
 */
class Logging
{
    /**
     * Creates Logging Handler Stack.
     *
     * @param string $log_name The log name.
     * @param string $file_name The file name.
     * @param string $template The template.
     *
     * @return HandlerStack $stack HandlerStack.
     * @throws \Exception
     */
    public static function createLoggingHandlerStack(
        string $log_name,
        string $file_name,
        string $template = "\n>>>>>>>>\n{request}\n<<<<<<<<\n{response}\n--------\n{error}\n"
    ): HandlerStack {
        $logger    = new Logger($log_name);
        $formatter = new LineFormatter(null, null, true, true);
        $handler   = new StreamHandler(SiteInfo::getDocumentRoot() . '/logs/' . $file_name);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        $stack = HandlerStack::create();
        $stack->push(Middleware::log($logger, new MessageFormatter($template)));

        return $stack;
    }
}
