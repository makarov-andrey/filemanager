<?php

use App\File;
use Illuminate\Database\Seeder;
use App\Performance\Monitor;
use App\Console\Helpers\OverloadingRecursivelyDialogs;
use Illuminate\Support\Facades\Validator;
use App\Console\Helpers\Overwrite;

/**
 * @method askAmountRecursively()
 */
class FilesTableFakesSeeder extends Seeder
{
    use OverloadingRecursivelyDialogs;
    use Overwrite;

    /**
     * количество записей, которые будут записаны за один запрос к БД
     *
     * @var int
     */
    protected $tick = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $amount = $this->askAmountRecursively();
        $recorded = 0;

        $monitor = new Monitor($this->CLI());
        $monitor->track();

        $bar = $this->CLI()->getOutput()->createProgressBar($amount);
        $bar->start();

        while ($recorded < $amount) {
            $times = min($amount - $recorded, $this->tick);
            $this->multipleInsertingSeed($times);
            $this->caretUp(3);
            $monitor->track();
            $bar->advance($times);

            $recorded += $times;
        }
        $this->caretDown();
    }

    /**
     * Сохраняет несколько фейковых записей одним запросом к БД
     *
     * @param int $times
     */
    protected function multipleInsertingSeed(int $times)
    {
        File::insert(array_map(function () {
            return factory(File::class)->raw();
        }, range(1, $times)));
    }

    /**
     * Сохраняет несколько фейковых записей,
     * генерируя по запросу к БД на каждую запись
     *
     * @param int $times
     */
    protected function singleInsertingSeed(int $times)
    {
        factory(File::class, $times)->create();
    }

    /**
     * Спрашивает у пользователя и возвращает количество
     * фейковых записей, которых нужно записать
     *
     * @return integer
     */
    public function askAmount()
    {
        $amount = $this->CLI()->ask("How many fake files do you want to store?", 5000000);
        Validator::make(['amount' => $amount], ['amount' => 'integer'])->validate();
        return (int)$amount;
    }

    /**
     * @return \Illuminate\Console\Command
     */
    protected function CLI()
    {
        return $this->command;
    }
}
