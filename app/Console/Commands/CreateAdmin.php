<?php

namespace App\Console\Commands;

use App\Console\Helpers\OverloadingRecursivelyDialogs;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

/**
 * @method askEmailRecursively
 * @method askNameRecursively
 * @method askSecretPasswordRecursively
 */
class CreateAdmin extends Command
{
    use OverloadingRecursivelyDialogs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tool for creating an administrator in database';

    /**
     * @var User
     */
    protected $user;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->askEmailRecursively();
        $this->user = User::where('email', $email)->first();

        if ($this->user) {
            if ($this->user->admin) {
                $this->line(Lang::get('create_admin_command.already_admin'));
                return;
            }
            if (!$this->confirmUpdateExisting()) {
                $this->handle();
                return;
            }
        } else {
            $this->user = new User();
            $this->user->email = $email;
            $this->user->name = $this->askNameRecursively();
            $this->user->password = $this->askSecretPasswordRecursively();
        }

        $this->user->admin = true;
        $this->user->save();

        $this->line(Lang::get('create_admin_command.success_saving'));
    }

    protected function askEmail()
    {
        $email = $this->ask(Lang::get('create_admin_command.ask_email'));
        Validator::make(['email' => $email], ['email' => 'required|email'])->validate();
        return $email;
    }

    protected function confirmUpdateExisting ()
    {
        $text = Lang::get('create_admin_command.ask_update_existing', ['user' => $this->user->email]);
        return $this->confirm($text, true);
    }

    protected function askName()
    {
        $name = $this->ask(Lang::get('create_admin_command.ask_name'));
        Validator::make(['name' => $name], ['name' => 'max:255'])->validate();
        return $name;
    }

    protected function askSecretPassword()
    {
        $password = $this->secret(Lang::get('create_admin_command.ask_password'));
        Validator::make(['password' => $password], ['password' => 'min:8'])->validate();
        $confirmedPassword = $this->secret(Lang::get('create_admin_command.ask_password_again'));
        return $password;
    }
}
