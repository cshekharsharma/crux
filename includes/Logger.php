<?php


/**
 * Short-Hand Logger class for various level based logging
 *
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 * 
 */
class Logger
{
    const DEBUG 	= 1;	// Most Verbose
    const INFO 		= 2;	// ...
    const WARN 		= 3;	// ...
    const ERROR 	= 4;	// ...
    const FATAL 	= 5;	// Least Verbose
    const OFF 		= 6;	// Nothing at all.

    const LOG_OPEN 		= 1;
    const OPEN_FAILED 	= 2;
    const LOG_CLOSED 	= 3;

    /* Public members: Not so much of an example of encapsulation, but that's okay. */
    public $Log_Status 	= Logger::LOG_CLOSED;
    public $DateFormat	= "Y-m-d G:i:s";
    public $MessageQueue;

    private $log_file;
    private $priority = Logger::INFO;

    private $file_handle;

    private static $instance;

    public static function getLogger() {
        if (!self::$instance instanceof Logger) {
            $filepath = Configuration::get('LOGGER_FILE');
            $priority = Configuration::get('LOGGER_LEVEL');
            self::$instance = new Logger($filepath, $priority);
        }
        return self::$instance;
    }

    private function __construct( $filepath , $priority ) {
        if ( $priority == Logger::OFF ) return;

        $this->log_file = $filepath;
        $this->MessageQueue = array();
        $this->priority = $priority;

        if ( file_exists( $this->log_file ) )
        {
            if ( !is_writable($this->log_file) )
            {
                $this->Log_Status = Logger::OPEN_FAILED;
                $this->MessageQueue[] = "The file exists, but could not be opened for writing. Check that appropriate permissions have been set.";
                return;
            }
        }

        if ( $this->file_handle = fopen( $this->log_file , "a" ) )
        {
            $this->Log_Status = Logger::LOG_OPEN;
            $this->MessageQueue[] = "The log file was opened successfully.";
        }
        else
        {
            $this->Log_Status = Logger::OPEN_FAILED;
            $this->MessageQueue[] = "The file could not be opened. Check permissions.";
        }

        return;
    }

    public function __destruct()
    {
        if ( $this->file_handle )
            fclose( $this->file_handle );
    }

    public function LogInfo($line)
    {
        $this->Log( $line , Logger::INFO );
    }

    public function LogDebug($line)
    {
        $this->Log( $line , Logger::DEBUG );
    }

    public function LogWarn($line)
    {
        $this->Log( $line , Logger::WARN );
    }

    public function LogError($line)
    {
        $this->Log( $line , Logger::ERROR );
    }

    public function LogFatal($line)
    {
        $this->Log( $line , Logger::FATAL );
    }

    public function Log($line, $priority)
    {
        if ( $this->priority <= $priority )
        {
            $status = $this->getTimeLine( $priority );
            $this->WriteFreeFormLine ( "$status $line \n" );
        }
    }

    public function WriteFreeFormLine( $line )
    {
        if ( $this->Log_Status == Logger::LOG_OPEN && $this->priority != Logger::OFF )
        {
            if (fwrite( $this->file_handle , $line ) === false) {
                $this->MessageQueue[] = "The file could not be written to. Check that appropriate permissions have been set.";
            }
        }
    }

    private function getTimeLine( $level )
    {
        $time = date( $this->DateFormat );

        switch( $level )
        {
            case Logger::INFO:
                return "$time - INFO  -->";
            case Logger::WARN:
                return "$time - WARN  -->";
            case Logger::DEBUG:
                return "$time - DEBUG -->";
            case Logger::ERROR:
                return "$time - ERROR -->";
            case Logger::FATAL:
                return "$time - FATAL -->";
            default:
                return "$time - LOG   -->";
        }
    }

}


?>