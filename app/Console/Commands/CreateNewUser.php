<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateNewUser extends Command
{   
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:createUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user from bash';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $email = $this->ask("Insert an email for the new user");

        $isUser = User::where('email', $email)->first();

        if($isUser) {
            $this->error("This email is already in use.");
            return;
        }

        $name = $this->ask("Insert a name for your new user");
        $surname = $this->ask("Insert a surname for your new user");

        $this->info("By default, the password for your new user will be 'Password01!'");

        $input = array(
            'Name' => $name,
            'Surname' => $surname,
            'email' => $email,
            'Lang' => 'it',
            'password' => 'Password01!'
        );
        
        User::create([
            'Name' => $input['Name'],
            'Surname' => $input['Surname'],
            'email' => $input['email'],
            'Lang' => $input['Lang'],
            'password' => Hash::make($input['password']),
            'active' => 1
        ]);
    }
}
