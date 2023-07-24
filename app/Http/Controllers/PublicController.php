<?php

namespace App\Http\Controllers;

use App\Models\Sim;
use App\Classes\MySim;
use App\Models\Credential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function profile() {
        
        $user = User::where('id', Auth::user()->id)->first();

        return view('profile', compact('user'));
    }

    /**
     * ? Da ripristinare gestione degli aggiornamenti dei campi Company
     */
    public function updateProfile(Request $request) {
        $user = Auth::user();
        $user->update([
            'Name' => $request->input('Name'),
            'Surname' => $request->input('Surname'),
            'Mobile' => $request->input('Mobile'),
            'Phone' => $request->input('Phone'),
            'Address' => $request->input('Address'),
            'City' => $request->input('City'),
            'County' => $request->input('County'),
            'Country' => $request->input('Country'),
            'Lang' => $request->input('Lang'),
            'CompanyName' => $request->input('CompanyName'),
            'CompanyPhone' => $request->input('CompanyPhone'),
            'CompanyAddress' => $request->input('CompanyAddress'),
            'CompanyCity' => $request->input('CompanyCity'),
            'CompanyCounty' => $request->input('CompanyCounty'),
            'CompanyCountry' => $request->input('CompanyCountry'),
            'CompanyVAT' => $request->input('CompanyVAT'),
        ]);

        return redirect()->back()->with('message', 'Informazioni profilo aggiornate');
    }

    public function changePasswordView() {
        return view('auth.change-password');
    }

    /**
     * Metodo per associazione di una nuova SIM già salvata su DB
     */
    public function scanSim($iccid, $msisdn) {
        // Se la SIM non è stata associata a nessuno, inserisci
        $sim = Sim::where('iccid', $iccid)->where('msisdn', $msisdn)->first();
        $SimController = new SimController();
        
        if(empty($sim->user_id)) {

            // Se si ha riscontro della SIM, associala
            if($sim) {
                if(!Auth::user()) {
                    return view('auth.scanSim', compact('sim'));
                } else {
                    if($sim->status != 1)
                        $SimController->associateSim(Auth::user()->id, $sim);
                    
                    return redirect('/')->with('message', 'Sim aggiunta correttamente.');
                }
            } else {
                $message = 'Sim not found.';
                
                if(!Auth::user())
                    return view('auth.scanSim', compact('sim', 'message'));
                else
                    return redirect('/')->with('message', $message);
            }
        } else {
            return redirect('/login')->with('warning', 'La SIM è già stata associata');
        }
    }
}
