<?php

use Illuminate\Database\Seeder;
use App\Performance\Monitor;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $monitor = new Monitor($this->command);
        $monitor->track();

        $a = factory(\App\File::class, 5000)->create();

        $monitor->track();
    }
}
