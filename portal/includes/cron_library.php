<?php

/**
 * PHP library to manage local cronjobs.
 *
 * @author     Derrick Egersdorfer
 * @license    MIT License
 * @copyright  2011 - 2013 Derrick Egersdorfer
 */

namespace Crontab;

class Crontab
{
    /**
     * Variables and there defaults
     */
    private $minute = "*";
    private $hour = "*";
    private $dayOfMonth = "*";
    private $month = "*";
    private $dayOfWeek = "*";

    private $tempFile = "/var/tmp/jobs.txt";
    private $logFile = "/var/www/html/portal/cron_log 2>&1"; // Log nothing by default /dev/null 2>&1

    public function __construct(array $config = array())
    {
        // Get all variables of this class
        $params = array_keys( get_class_vars( get_called_class() ) );

        // Loop and set config values
        foreach( $params as $key ){
            if(array_key_exists($key, $config)){
                if( ! call_user_func(array($this, 'set'.ucFirst(strtolower($key))), $config[$key])){
                    throw new \Exception("Could not set $key to " . $config[$key] . ": " . implode($this->error) );
                }
            }
        }

        // Done
        return true;
    }



    public function setMinute($data)
    {
        $this->minute = $data;
    }

    public function setHour($data)
    {
        $this->hour = $data;
    }

    public function setDayOfMonth($data)
    {
        $this->dayOfMonth = $data;
    }

    public function setMonth($data)
    {
        $this->month = $data;
    }

    public function setDayOfWeek($data)
    {
        $this->dayOfWeek = $data;
    }

    public function setTempFile($data)
    {
        // Check for the file and attempt to create it
        if( ! file_exists($data)){

            if($fh = fopen($data, 'a')){
                fclose($fh);
                $this->tempFile = $data;
                return true;
            } else {
                throw new \Exception("Could not create temp file: $data");
            }
        }

        // Ok
        $this->tempFile = $data;
        return true;
    }

    public function setLogFile($data)
    {
        // Check for the file and attempt to create it
        if( ! file_exists($data)){

            if($fh = fopen($data, 'a')){
                fclose($fh);
                $this->logFile = $data;
                return true;
            } else {
                throw new \Exception("Could not create log file: $data");
            }
        }

        // Ok
        $this->logFile = $data;
        return true;
    }



    /**
     * Helper function to execute so many minutes from now
     */
    public function minuteFromNow($min = 1)
    {
        $time = strtotime("+$min minute");
        $this->setMinute(date('i', $time));
        $this->setHour(date('H', $time));
        $this->setDayOfMonth(date('j', $time));
        $this->setMonth(date('n', $time));
        $this->setDayOfWeek(date('w', $time));
    }




    /**
     * Ads a cronjob command to the system
     * @param array
     */
    public function append($command)
    {
        // Convert to array
        $command = is_string($command) ? array($command) : $command;

        // Build cron command lines
        foreach($command as $k => $v){
            $cmds[] = $this->buildCommandLine($v);
        }

        // Get currently set jobs and append new jobs
        $jobs = $this->buildExistingJobsArray($cmds);

        // Update Jobs
        $this->update = $jobs;
    }

    /**
     * Removes a cronjob command from the system
     * @param commands to remove
     */
    public function remove($command)
    {
        // Convert to array
        $command = is_string($command) ? array($command) : $command;

        // Build cron command lines
        foreach($command as $k => $v){
            $cmds[] = $this->buildCommandLine($v);
        }

        // Get existing jobs
        $jobs = array_flip($this->buildExistingJobsArray());

        // Loop to find and remove jobs
        foreach($cmds as $k => $v){
            if(array_key_exists($v, $jobs)){
                unset($jobs[$v]);
            }
        }

        // Flip back and ready to rewrite the new cronjob file
        $jobs = array_flip($jobs);

        // Update Jobs
        $this->update = $jobs;
    }

    /**
     * Removes a cronjob command from the system by its unique hash
     * @param key hashs to remove
     */
    public function removeByKey($hash)
    {
        // Convert to array
        $hash = is_string($hash) ? array($hash) : $hash;

        // Get existing jobs
        $jobs = $this->buildExistingJobsArray();

        // Loop to find and remove jobs
        foreach($hash as $k => $v){
            if(array_key_exists($v, $jobs)){
                unset($jobs[$v]);
            }
        }

        // Update Jobs
        $this->update = $jobs;
    }

    /**
     * Returns an array of running jobs
     */
    public function getJobs()
    {
        return $this->buildExistingJobsArray();
    }

    /**
     * Removes all cronjobs
     */
    public function clear()
    {
        exec("crontab -r");
    }

    /**
     * Update the systems contab file with
     * @param jobs array
     * @return array of current cron jobs
     */
    public function execute()
    {
        // Write the new cronjob data
        $this->writeTmpCronFile($this->update);

        // Set cron to temp cronfile
        exec("crontab " . $this->tempFile, $output, $status);
        //exec("crontab " . $this->tempFile . " 2>&1 >> log.txt", $output, $status);

        if(count($output) > 0){
            print_r($output);
        }

        // Return list of current jobs
        return $this->update;
    }

    /**
     * Builds a single command line entry for the contab temp file
     * @param string
     * @return string
     */
    private function buildCommandLine($command)
    {
        // Build command line time
        $time = array(
            $this->minute,
            $this->hour,
            $this->dayOfMonth,
            $this->month,
            $this->dayOfWeek
        );
        $line = implode(" ", $time) . " " . trim($command) . " > " . $this->logFile . " 2>&1";

        // Check the command
        if( preg_match("/".$this->buildRegexp()."/", $line) !== 0 ){
            return $line;
        }

        // Exit if no good.
        throw new \Exception("$line is not a valid command");
    }

    /**
     * Builds an array of active cronjob command on the system
     * @param string of a new command
     * @return array
     */
    private function buildExistingJobsArray($append = false)
    {
        // Existing jobs
        exec("crontab -l ", $output, $status);

        // Clean up contab array just incase
        if(count($output) > 0){
            foreach($output as $k => $v){
                $array[md5($v)] = trim($v);
            }
        }

        // Append new jobs
        if($append){

            // Convert to array if need be
            $append = is_string($append) ? array($append) : $append;

            // Append to exisiting array or new array
            foreach($append as $k => $v){
                $array[md5($v)] = trim($v);
            }
        }

        // Done
        return isset($array) ? array_unique($array) : array();
    }

    /**
     * Writes a temp file for the crontab program to read
     * @param $content = new command
     * @return boolean
     */
    private function writeTmpCronFile($content)
    {
        // Convert to array
        $content = is_string($content) ? array($content) : $content;

        // Set string
        $string = null;

        // Cleanup content if array
        if( ! empty($content)){
            foreach($content as $k => $v){
                $string[] = trim($v);
            }
            $string = implode(PHP_EOL, $string).PHP_EOL;
        }

        // Set temp file
        $filename = $this->tempFile;

        // Open the temp file
        if ( ! $handle = fopen($filename, 'w')) {
            throw new \Exception("Cannot open $filename");
            exit;
        }

        // And write in content
        if (fwrite($handle, $string) === FALSE) {
            throw new \Exception("Cannot write to $filename");
            exit;
        }

        // Done
        fclose($handle);
        return true;
    }

    /**
     * Builds a regular expression to check the cronjob
     * Thanks to Jordi Salvat
     */
    private function buildRegexp()
    {
        $numbers= array(
            'min'=>'[0-5]?\d',
            'hour'=>'[01]?\d|2[0-3]',
            'day'=>'0?[1-9]|[12]\d|3[01]',
            'month'=>'[1-9]|1[012]',
            'dow'=>'[0-7]'
        );

        foreach($numbers as $field=>$number) {
            $range= "($number)(-($number)(\/\d+)?)?";
            $field_re[$field]= "\*(\/\d+)?|$range(,$range)*";
        }

        $field_re['month'].='|jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec';
        $field_re['dow'].='|mon|tue|wed|thu|fri|sat|sun';

        $fields_re= '('.join(')\s+(', $field_re).')';

        $replacements= '@reboot|@yearly|@annually|@monthly|@weekly|@daily|@midnight|@hourly';

        return '^\s*('.
                '$'.
                '|#'.
                '|\w+\s*='.
                "|$fields_re\s+\S".
                "|($replacements)\s+\S".
            ')';
    }

    /**
     * Clean up
     */
    public function __destruct()
    {
        if(file_exists($this->tempFile)){
            exec("rm " . $this->tempFile);
        }
    }
}