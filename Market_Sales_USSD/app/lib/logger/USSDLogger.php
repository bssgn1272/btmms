<?php

//declare the namespace
namespace App\Lib\Logger;

//autoload the classes we need
require_once __DIR__ . "/../../../vendor/autoload.php";

//reference class Logger from the global namespace
use \Logger;

class USSDLogger extends Logger
{

    /**
     * Log object in the class.
     *
     * @var string
     */
    private $log;

    /**
     * Class Constructor.
     */
    public function __construct()
    {
        //call the parent class constructor
        parent::__construct(__CLASS__);
        $this->log = Logger::getLogger(__CLASS__);
    }

    /**
     * Function to write info logs.
     *
     * @param string $logFile the log file to write to
     * @param int $MSISDN the phoneNumber making the payment
     * @param string $message the message to log
     * @param string $account receiving the payment
     */
    public function logInfo($logFile, $MSISDN, $message, $account = "")
    {
        $this->setConfigurations($logFile, $MSISDN, $account);
        $this->log->info($message,NULL);
    }

    /**
     * Function to write error logs.
     *
     * @param string $logFile the log file to write to
     * @param int $MSISDN the MSISDN making the payment
     * @param string $message the message to log
     * @param string $account - paybillNumber receiving the payment
     */
    public function logError($logFile, $MSISDN, $message, $account = "")
    {
        $this->setConfigurations($logFile, $MSISDN, $account);
        $this->log->error($message);
    }


    /**
     * Function to write debug logs.
     *
     * @param string $logFile the log file to write to
     * @param int $MSISDN the phoneNumber making the transaction
     * @param string $message the message to log
     * @param string $account - the payBill number
     */
    public function logDeburg($logFile, $MSISDN, $message, $account = "")
    {
        $this->setConfigurations($logFile, $MSISDN, $account);
        $this->log->debug($message);
    }

    /**
     * Function to write fatal logs.
     *
     * @param string $logFile the log file to write to
     * @param int $MSISDN the unique ID for the transaction
     * @param string $message the message to log
     * @param string $account - the payBill Number we log to
     */
    public function logFatal($logFile, $MSISDN, $message, $account = "")
    {
        $this->setConfigurations($logFile, $MSISDN, $account);
        $this->log->fatal($message);
    }

    /**
     * Function to write SQL logs.
     *
     * @param string $logFile the log file to write to
     * @param int $MSISDN the MSISDN for transaction
     * @param string $message the message to log
     * @param string $payBillNumber - the payBill Number paying to
     */
    public function sequel($logFile, $MSISDN, $message, $payBillNumber = "")
    {
        $this->log->configure(
            array(
                "rootLogger" => array(
                    "appenders" => array("default"),
                ),
                "appenders" => array(
                    "default" => array(
                        "class" => "LoggerAppenderFile",
                        "layout" => array(
                            "class" => "LoggerLayoutPattern",
                            "params" => array(
                                "conversionPattern" =>
                                    date("Y-m-d H:i:s") . " | SEQUEL | %F | "
                                    . "%method | LINE:%L | " . $MSISDN
                                    . " | To: " . $payBillNumber
                                    . " | %message%newline"
                            )
                        ),
                        "params" => array(
                            "file" => "$logFile"
                        )
                    )
                ),
            )
        );

        $this->log->debug($message);
    }

    /**
     * Sets the log format and appender file class.
     *
     * @param string $logFile the log file to write to
     * @param int $uniqueID MSISDN
     * @param $to - the payBill number
     */
    private function setConfigurations($logFile, $uniqueID, $to)
    {
        $this->log->configure(
            array(
                "rootLogger" => array(
                    "appenders" => array("default"),
                ),
                "appenders" => array(
                    "default" => array(
                        "class" => "LoggerAppenderFile",
                        "layout" => array(
                            "class" => "LoggerLayoutPattern",
                            "params" => array(
                                "conversionPattern" =>
                                    date("Y-m-d H:i:s") . " | %p | %F | "
                                    . "%method | LINE:%L | " . $uniqueID
                                    . " |$to| %message%newline"
                            )
                        ),
                        "params" => array("file" => "$logFile"."_".date('Ymd').".log")
                    )
                ),
            )
        );
    }

    /**
     * Utility to print an array. Strips out passwords.
     *
     * @param array $arr The array to print
     *
     * @return string The array as a string that can be printed
     */
    public function printArray($arr)
    {
        $str = print_r($arr, true);
        $str_replaced = preg_replace(
            "/.password] => (.+)/", "[password] => **********", $str
        );
        $str1 = str_replace("\n", "", $str_replaced);
        $str2 = str_replace("   ", "", $str1);
        $str3 = str_replace("  ", "", $str2);
        return $str3;
    }

}
