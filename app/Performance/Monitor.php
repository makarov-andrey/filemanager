<?php

namespace App\Performance;

use \Illuminate\Console\Command;

class Monitor
{
    /**
     * @var integer
     */
    protected $lastMemoryUsage;

    /**
     * @var float
     */
    protected $lastMicroTime;

    /**
     * @var integer
     */
    protected $startMemoryUsage;

    /**
     * @var float
     */
    protected $startMicroTime;

    /**
     * The console command instance.
     *
     * @var Command
     */
    protected $command;

    function __construct(Command $command)
    {
        $this->command = $command;
        $this->startMemoryUsage = memory_get_usage(true);
        $this->startMicroTime = microtime(true);
    }

    public function track()
    {
        $this->trackMemory();
        $this->trackTime();
        $this->command->comment("--------------------------------------------");
        $this->resetIntermediateValues();
    }

    public function trackMemory()
    {
        $memoryUsage = memory_get_usage(true);
        $string = "Memory usage: " . $this->prettifyMemory($memoryUsage);
        if (!is_null($this->lastMemoryUsage)) {
            $differenceFromLast = $memoryUsage - $this->lastMemoryUsage;
            $string .= " (" . $this->getMemoryDifferenceString($differenceFromLast) . " from last tracking, ";

            $differenceFromBeginning = $memoryUsage - $this->startMemoryUsage;
            $string .= $this->getMemoryDifferenceString($differenceFromBeginning) . " from the beginning)";
        }
        $this->command->comment($string);
    }

    public function trackTime()
    {
        $microTime = microtime(true);
        $string = "Time: " . $this->prettifyTime($microTime);
        if (!is_null($this->lastMicroTime)) {
            $differenceFromLast = $microTime - $this->lastMicroTime;
            $string .= " (" . $this->getTimeDifferenceString($differenceFromLast) . " from last tracking, ";

            $differenceFromBeginning = $microTime - $this->startMicroTime;
            $string .= $this->getTimeDifferenceString($differenceFromBeginning) . " from the beginning)";
        }
        $this->command->comment($string);
    }

    function getMemoryDifferenceString($difference)
    {
        $string = "";
        switch ($difference <=> 0) {
            case 0:
                $string .= "equal with value";
                break;
            case 1:
                $string .= "+" . $this->prettifyMemory($difference);
                break;
            case -1:
                $string .= "-" . $this->prettifyMemory($difference);
                break;
        }
        return $string;
    }

    function getTimeDifferenceString($difference)
    {
        return "+" . $this->prettifyTimeInterval($difference);
    }

    public function resetIntermediateValues()
    {
        $this->lastMicroTime = microtime(true);
        $this->lastMemoryUsage = memory_get_usage(true);
    }

    public function prettifyMemory(int $bytes)
    {
        return round($bytes / 1024 / 1024, 2) . " MB";
    }

    public function prettifyTime(float $microTime)
    {
        return date("H:i:s.u", $microTime);
    }

    public function prettifyTimeInterval(float $microTime)
    {
        return round($microTime, 4) . " sec.";
    }
}