<?php

namespace App\Console\Commands;

use App\Models\Sim;
use App\Models\User;
use App\Classes\MySim;
use App\Models\Credential;
use Illuminate\Console\Command;

class VerifyEndingDateSim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sim:VerifyEndingDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Verify ending date of every sim. Suspend sim if necessary";

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
        $MySim = new MySim();
        $credentials = Credential::where('id', 1)->first();
        $CSL_CLIENT_ID      = $credentials->client_id;
        $CSL_CLIENT_SECRET  = $credentials->client_secret;
        
        $params = array(
            'CSL_CLIENT_ID'     => $CSL_CLIENT_ID,
            'CSL_CLIENT_SECRET' => $CSL_CLIENT_SECRET,
        );

        $this->info("Starting process...");

        $sims = Sim::all();
        $sims_counter = count($sims);
        $today = date('Y-m-d');
        
        $this->info("Sims found: $sims_counter\n");

        foreach ($sims as $sim) {
            if(empty($sim->expires_in))
                continue;

            if($today == $sim->expires_in || $today > $sim->expires_in) {
                $user = User::where('id', $sim->user_id)->first();

                if($MySim->init($params)) {
                    $response = $MySim->set_suspend_by_icc($sim->iccid);
    
                    if(!$response) {
                        $this->info("ERROR on suspending SIM #{$sim->id}");
                    } else {
                        $this->info("SIM #{$sim->id} from User {$user->Name} {$user->Surname} - {$user->email} suspended.");
                    }
                }
            }
        }

        $this->info("\nEnding process");
    }
}
