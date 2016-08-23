<?php
namespace ComPHPartment\LogWrapper;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\ErrorHandler;

use ComPHPartment\ComPHPartment;

class LogWrapper
{
    const LOG_PATH = '/data/log';

    public static $logFile;
    public static $loggers = [
        'execution' => true,
        'error'     => true,
        'request'   => false,
    ];

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

        ErrorHandler::register(self::$loggers['error']);
    }

    public static function getLogger($loggerName = 'execution')
    {
        if (!key_exists($loggerName, self::$loggers)) {
            throw new \Exception('Invalid logger: ' . $loggerName);
        }

        return self::$loggers[$loggerName];
    }
}
