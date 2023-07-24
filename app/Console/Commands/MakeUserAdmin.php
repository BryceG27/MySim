<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:makeAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give to a user Admin permissions';

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
        $email = $this->ask("Insert the email for the user you want to be an admin");
        $user = User::where('email', $email)->first();

        if(!$user) {
            $this->error("User not found");
            return;
        }

        $user->UserType = 1;
        $user->Active = 1;
        $user->save();
        $this->info("The user {$user->Name} {$user->Surname} is now an Admin");
    }
}
