<?php

namespace Core;

use App\Config;
use App\SiteInfo;
use App\Telegram;
use ErrorException;
use Exception;

/**
 * Class Error.
 *
 * @package Core
 */
class Error
{
    /**
     * Error handler.
     *
     * Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int $level Error level.
     * @param string $message Error message.
     * @param string $file Filename the error was raised in.
     * @param int $line Line number in the file.
     *
     * @return void
     * @throws ErrorException
     */
    public static function errorHandler(int $level, string $message, string $file, int $line): void
    {
        if (error_reporting() !== 0) {  // To keep the @ operator working.
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler.
     *
     * @param Exception|Error $exception The exception.
     * @param \GuzzleHttp\HandlerStack $handler_telegram_bot (optional)
     * @param mixed $is_local_server (optional)
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws Exception
     */
    public static function exceptionHandler(
        $exception,
        $handler_telegram_bot = null,
        $is_local_server = null
    ): void {
        $code = $exception->getCode();

        $is_local_server = $is_local_server ?? Config::isLocalServer();

        if (! preg_match('/^4\d{2}$/', $code)) {
            $code = 500;
        }

        if ($code === 500) {
            static::logException($exception, $code);
        }

        if ($is_local_server) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } else {
            if ($code === 500) {
                static::sendException($exception, $handler_telegram_bot);
            }

            View::renderTemplate("$code.twig");
        }
    }

    /**
     * Logs the exception.
     *
     * @param Exception|Error $exception
     * @param int $code
     *
     * @return void
     */
    public static function logException($exception, int $code): void
    {
        http_response_code($code);

        $log_file = SiteInfo::getDocumentRoot() . '/logs/' . date('Y-m-d') . '.log';

        ini_set('error_log', $log_file);

        $message = 'Fatal error' . PHP_EOL;
        $message .= 'Uncaught exception: "' . get_class($exception) . '" ';
        $message .= 'with message "' . $exception->getMessage() . '"' . PHP_EOL;
        $message .= 'Stack trace: ' . $exception->getTraceAsString() . PHP_EOL;
        $message .= 'Thrown in "' . $exception->getFile() . '" on line ' . $exception->getLine() . PHP_EOL;

        error_log($message);
    }

    /**
     * Sends the exception.
     *
     * @param Exception|Error $exception
     * @param mixed $handler_telegram_bot (optional)
     *
     * @return void
     * @throws Exception
     */
    public static function sendException($exception, $handler_telegram_bot = null): void
    {
        $message = 'Fatal error' . PHP_EOL;
        $message .= 'Uncaught exception: "' . get_class($exception) . '" ';
        $message .= 'with message "' . $exception->getMessage() . '"' . PHP_EOL;
        $message .= 'Stack trace: ' . $exception->getTraceAsString() . PHP_EOL;
        $message .= 'Thrown in "' . $exception->getFile() . '" on line ' . $exception->getLine();

        $telegram = new Telegram(
            Config::TELEGRAM_DEV_TOKEN,
            Config::TELEGRAM_DEV_CHAT_ID,
            $handler_telegram_bot
        );

        $telegram->sendMessage($message);
    }
}
