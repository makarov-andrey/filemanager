<?php

use Illuminate\Database\Seeder;
use App\RowsCount;
use App\File;

class FilesRowsCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filesRowsCounter = new RowsCount;
        $filesRowsCounter->table_name = (new File)->getTable();
        $filesRowsCounter->up();
    }
}
