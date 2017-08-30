<?php

namespace App\Console\Helpers;


trait Overwrite
{
    /**
     * Перемещает каретку в консоли на $rows строк вверх
     *
     * @param int $rows
     */
    function caretUp(int $rows = 1)
    {
        // Move the cursor to the beginning of the line
        $this->CLI()->getOutput()->write("\x0D");

        // Erase the line
        $this->CLI()->getOutput()->write("\x1B[2K");

        // Erase previous lines
        if ($rows) {
            $this->CLI()->getOutput()->write(str_repeat("\x1B[1A\x1B[2K", $rows));
        }
    }

    /**
     * Перемещает каретку в консоли на $rows строк вниз
     *
     * @param int $rows
     */
    function caretDown(int $rows = 1)
    {
        while ($rows > 0) {
            $this->CLI()->line('');
            $rows--;
        }
    }

    /**
     * @return \Illuminate\Console\Command
     */
    abstract function CLI();
}