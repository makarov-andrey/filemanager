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
class FilesTableSeeder extends Seeder
{
    use OverloadingRecursivelyDialogs;
    use Overwrite;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $monitor = new Monitor($this->CLI());

        $amount = $this->askAmountRecursively();
        $tick = 1000;
        $recorded = 0;

        $monitor->track();

        $bar = $this->CLI()->getOutput()->createProgressBar($amount);
        $bar->start();

        while ($recorded < $amount) {
            $times = min($amount - $recorded, $tick);
            factory(File::class, $times)->create();
            $recorded += $times;

            $this->caretUp(3);
            $monitor->track();
            $bar->advance($times);
        }
        $this->caretDown();
    }

    /**
     * Спрашивает у пользователя и возвращает количество
     * фейковых записей, которых нужно записать
     *
     * @return integer
     */
    public function askAmount ()
    {
        $amount = $this->CLI()->ask("How many fakes do you wanna record?", 5000000);
        Validator::make(['amount' => $amount], ['amount' => 'integer'])->validate();
        return (int) $amount;
    }

    /**
     * @return \Illuminate\Console\Command
     */
    protected function CLI()
    {
        return $this->command;
    }
}
