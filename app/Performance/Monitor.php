<?php

namespace App\Performance;

use Illuminate\Console\Command;
use DateTime;
use function PHPSTORM_META\type;

class Monitor
{
    /**
     * Результат последнего трекинга памяти
     *
     * @var integer
     */
    protected $lastMemoryUsage;

    /**
     * Результат последнего трекинга времени
     *
     * @var float
     */
    protected $lastMicroTime;

    /**
     * Состояние памяти на момент создания экземпляра монитора
     *
     * @var integer
     */
    protected $startMemoryUsage;

    /**
     * Время на момент создания экземпляра монитора
     *
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
        $this->startMemoryUsage = $this->getMemoryUsage();
        $this->startMicroTime = $this->getMicroTime();
    }

    /**
     * Трекинг использования памяти, разницы использования памяти и времени
     */
    public function track()
    {
        $this->trackMemory();
        $this->trackTime();
        $this->output("--------------------------------------------");
        $this->resetIntermediateValues();
    }

    /**
     * Трекинг памяти. Выводит текущее состояние памяти,
     * разницу с предыдущим треком и с началом мониторинга
     */
    public function trackMemory()
    {
        $memoryUsage = $this->getMemoryUsage();
        $string = "Memory usage: " . $this->prettifyMemory($memoryUsage);
        if (!is_null($this->lastMemoryUsage)) {
            $differenceFromLast = $memoryUsage - $this->lastMemoryUsage;
            $string .= " (" . $this->getMemoryDifferenceString($differenceFromLast) . " from the last tracking, ";

            $differenceFromBeginning = $memoryUsage - $this->startMemoryUsage;
            $string .= $this->getMemoryDifferenceString($differenceFromBeginning) . " from the beginning)";
        }
        $this->output($string);
    }

    /**
     * Трекинг времени. Выводит текущее время, сколько
     * прошло с предыдущего трека и с начала мониторинга
     */
    public function trackTime()
    {
        $microTime = $this->getMicroTime();
        $string = "Time: " . $this->prettifyTime($microTime);
        if (!is_null($this->lastMicroTime)) {
            $differenceFromLast = $microTime - $this->lastMicroTime;
            $string .= " (" . $this->getTimeDifferenceString($differenceFromLast) . " from the last tracking, ";

            $differenceFromBeginning = $microTime - $this->startMicroTime;
            $string .= $this->getTimeDifferenceString($differenceFromBeginning) . " from the beginning)";
        }
        $this->output($string);
    }

    /**
     * Возвращает строку с показателем разницы памяти
     * $difference == 0 - "equal with value"
     * $difference > 0  - "+10MB"
     * $difference < 0  - "-10MB"
     *
     * @param $difference
     * @return string
     */
    public function getMemoryDifferenceString($difference)
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

    /**
     * Возвращает строку с показателем разницы времени
     * например: "+0.0019 sec."
     *
     * @param $difference
     * @return string
     */
    public function getTimeDifferenceString($difference)
    {
        return "+" . $this->prettifyTimeInterval($difference);
    }

    /**
     * Переопределяет промежуточные значения времени и памяти
     * для будущего сравнения с ними
     */
    public function resetIntermediateValues()
    {
        $this->lastMicroTime = $this->getMicroTime();
        $this->lastMemoryUsage = $this->getMemoryUsage();
    }

    /**
     * Возвращает строку из числа, переведенного
     * в мегабайты и добавляет в конец строки MB
     *
     * @param int $bytes
     * @return string
     */
    public function prettifyMemory(int $bytes)
    {
        return round($bytes / 1024 / 1024, 2) . " MB";
    }

    /**
     * Возвращает строку времени по шаблону H:i:s.u,
     * сгенерированную по таймстампу
     *
     * @param float $microTime
     * @return false|string
     */
    public function prettifyTime(float $microTime)
    {
        $datetime = DateTime::createFromFormat('U.u', $microTime);
        return $datetime->format("H:i:s.u");
    }

    /**
     * Возвращает строку вида "0.0047 sec."
     *
     * @param float $microTime
     * @return string
     */
    public function prettifyTimeInterval(float $microTime)
    {
        return round($microTime, 4) . " sec.";
    }

    /**
     * Выводит строку в консоль
     *
     * @param string $string
     */
    public function output (string $string)
    {
        $this->command->line($string);
    }

    /**
     * Получить текущее состояние памяти
     *
     * @return int
     */
    public function getMemoryUsage()
    {
        return memory_get_usage(true);
    }

    /**
     * Получить текущий таймстамп с микросекундами
     *
     * @return float
     */
    public function getMicroTime()
    {
        return microtime(true);
    }


}