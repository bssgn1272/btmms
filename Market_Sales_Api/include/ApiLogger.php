<?php

require_once __DIR__ . "/../libs/apache-log4php/Logger.php";


class ApiLogger extends Logger
{

    /**
     * Log object in the class.
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

    public function logInfo($logFile,$tag,$message)
    {
        $this->setConfigurations($logFile,$tag,$message);
        $this->log->info($message);
    }

    public function logError($logFile,$tag,$message)
    {
        $this->setConfigurations($logFile,$tag,$message);
        $this->log->error($message);
    }


    public function logDebug($logFile,$tag,$message)
    {
        $this->setConfigurations($logFile,$tag,$message );
        $this->log->debug($message);
    }

    public function logFatal($logFile,$tag, $message)
    {
        $this->setConfigurations($logFile,$tag,$message);
        $this->log->fatal($message);
    }

    public function sequel($logFile, $message)
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
                                "conversionPattern" => date("Y-M-d H:i:s") . " | SEQUEL | %F | " . "%method | LINE:%L | %message%newline"
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
     */
    private function setConfigurations($logFile,$tag,$message)
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
                                //"conversionPattern" => date("Y-m-d H:i:s") . " | %p | %F | ". "%method | LINE:%L | " . $uniqueID . " |$to| %message%newline"
                                //"conversionPattern" => date("Y-M-d H:i:s") . " | %p | %F %method | LINE:%L | %message%newline"
                                //"conversionPattern" => date("Y-M-d H:i:s") . " | %message%newline"
                                "conversionPattern" => date("Y-M-d H:i:s") . " [" . $tag . "] " .$message. " %newline"
                            )
                            //DATETIME LOG-TYPE  FILE METHOD LINE MESSAGE
                        ),
                        "params" => array(
                            "file" => "$logFile"."-".date('Y-M-d').".log")
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
