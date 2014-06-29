<?php
/**
 * Application profiler for logging request time
 * 
 * @author Chandra Shekhar <shekharsharma705@gmail.com>
 * @package includes
 * @since Jun 29, 2014
 */
final class Profiler {

    const PROFILER_START_TIME = 'starttime';
    const PROFILER_END_TIME   = 'endtime';

    /**
     * Holds the start-end time for all the profiles
     * 
     * @var array
     */
    private static $profilers = array();

    private function __construct() {
    }

    /**
     * Starts tracking activity time for given profile
     * 
     * @param string $name
     */
    public static function startProfiler($name) {
        if (!empty($name)) {
            if (empty(self::$profilers[$name])) {
                self::$profilers[$name] = array(
                    self::PROFILER_START_TIME => microtime(true),
                    self::PROFILER_END_TIME   => null
                );
            }
        } else {
            Logger::getLogger()->LogWarn('Empty profiler name provided!');
        }
    }

    /**
     * Ends the given profiler time
     * 
     * @param string $name
     */
    public static function endProfiler($name) {
        if (!empty($name)) {
            if (!empty(self::$profilers[$name])) {
                self::$profilers[$name][self::PROFILER_END_TIME] = microtime(true);
                self::logActivityTime($name, self::$profilers[$name]);
            }

        } else {
            Logger::getLogger()->LogWarn('Empty profiler name provided!');
        }
    }

    /**
     * Writes total taken by activity into logs
     * 
     * @param string $profile
     * @param array $info
     */
    private static function logActivityTime($profile, array $info) {
        $timeDiff = $info[self::PROFILER_END_TIME] - $info[self::PROFILER_START_TIME];
        Logger::getLogger()->LogInfo('Profiler: ' .
            $timeDiff. ' micro-seconds taken by '. $profile);
    }
}