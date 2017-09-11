<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Console\Helpers\OverloadingRecursivelyDialogs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;

/**
 * @method askEmailRecursively
 * @method askNameRecursively
 * @method askSecretPasswordRecursively
 */
class AdminSeeder extends Seeder
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
    public function run()
    {
        $this->CLI()->line('Create an administrator account so you can manage the app');
        $this->handle();
    }

    public function handle () {
        $email = $this->askEmailRecursively();
        $this->user = User::where('email', $email)->first();

        if ($this->user) {
            if ($this->user->admin) {
                $this->CLI()->line(Lang::get('admin_seeder.already_admin'));
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
            $this->user->password = bcrypt($this->askSecretPasswordRecursively());
        }

        $this->user->admin = true;
        $this->user->save();

        $this->CLI()->line(Lang::get('admin_seeder.success_saving'));
    }

    protected function askEmail()
    {
        $email = $this->CLI()->ask(Lang::get('admin_seeder.ask_email'));
        Validator::make(['email' => $email], ['email' => 'required|email|max:255'])->validate();
        return $email;
    }

    protected function confirmUpdateExisting ()
    {
        $text = Lang::get('admin_seeder.ask_update_existing', ['user' => $this->user->email]);
        return $this->CLI()->confirm($text, true);
    }

    protected function askName()
    {
        $name = $this->CLI()->ask(Lang::get('admin_seeder.ask_name'));
        Validator::make(['name' => $name], ['name' => 'max:255'])->validate();
        return $name;
    }

    protected function askSecretPassword()
    {
        $password = $this->CLI()->secret(Lang::get('admin_seeder.ask_password'));
        Validator::make(['password' => $password], ['password' => 'required|min:6'])->validate();

        $passwordConfirmation = $this->CLI()->secret(Lang::get('admin_seeder.ask_password_again'));
        Validator::make(
            ['password' => $password, 'password_confirmation' => $passwordConfirmation],
            ['password' => 'confirmed']
        )->validate();

        return $password;
    }

    protected function CLI ()
    {
        return $this->command;
    }
}
