<?php
/**
 * LogWrapper - A wrapper for Monolog to be used by ComPHPartment.
 *
 * @package ComPHPartment\LogWrapper
 * @author Er Galvão Abbott <galvao@php.net>
 * @see https://github.com/galvao/comphpartment
 * @version 0.1.0-alpha
 * @license BSD
 */

namespace ComPHPartment\LogWrapper;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\ErrorHandler;

use ComPHPartment\ComPHPartment;

class LogWrapper
{
    /** Log base path */
    const LOG_PATH = '/data/log';

    /** @var string $logFile File to be used to log occurrences */
    public static $logFile;

    /** @var array $loggers What ComPHPartment should consider to be valid loggers */
    public static $loggers = [
        'execution' => true,
        'error'     => true,
        'request'   => false,
    ];

    /**
     * setLoggers - Sets (instantiates) the desired loggers
     * @since 0.1.0-alpha
     */
    public static function setLoggers()
    {
        $tsFormat = date('H:i:s');
        $output = "%datetime% [ %level_name% ]: %message% %context% %extra%" . PHP_EOL;

        $formatter = new LineFormatter($output, $tsFormat);

        foreach (self::$loggers as $loggerIndex => $loggerDesired) {
            // Having true as it's value means that the loger is desired, but it's not set yet.
            if ($loggerDesired === true) {
                self::$logFile = date('Y-m-d') . '.log';
                $absLogPath = ComPHPartment::getBasePath() . self::LOG_PATH . '/' . $loggerIndex . '/' . self::$logFile;

                $stream = new StreamHandler($absLogPath);
                $stream->setFormatter($formatter);

                self::$loggers[$loggerIndex] = new Logger($loggerIndex);
                self::$loggers[$loggerIndex]->pushHandler($stream);
            }
        }

        /** @todo Check if there's a need to register the ErrorHandler every time */
        ErrorHandler::register(self::$loggers['error']);
    }

    /**
     * getLogger - Gets a specific logger
     * @param string $loggerName The name (index) of the desired logger
     * @throws \Exception If the desired logger is invalid according to the loggers array
     * @return \Monolog\Logger The desired Monolog instance
     * @since 0.1.0-alpha
     */

    public static function getLogger($loggerName = 'execution')
    {
        if (!key_exists($loggerName, self::$loggers)) {
            throw new \Exception('Invalid logger: ' . $loggerName);
        }

        return self::$loggers[$loggerName];
    }
}
