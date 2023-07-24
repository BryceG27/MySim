<?php

namespace App\Http\Controllers;

use App\Models\Sim;
use App\Models\Rate;
use App\Models\User;
use App\Models\Credential;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;

class AdminController extends Controller
{
    // I metodi di questo controller sono accessibili solo dagli Admin
    public function __construct() {
        $this->middleware('admin');
    }

    public function userManagement() {
        //$users = User::where('UserType' , '2')->get();

        return view('admin.userManagement');
    }

    // Recupera tutti gli utenti non Admin e le SIM associate
    public function getUsers() {
        $users = User::where('UserType' , '2')->get();
        $sims = array();

        foreach ($users as $user) {
            if($user->sims) {
                foreach ($user->sims as $i => $sim) {
                    $sims[$user->id][$i] = $sim;
                }
            }
        }

        $response = array(
            'users' => $users,
            'sims' => $sims
        );

        return response()->json($response);
    }

    // Chiamata per recupero parametrico dell'utente
    public function getUser($id) {
        $user = User::where('id', $id)->first();

        return response()->json($user);
    }
    
    // Recupera tutte le SIM salvate in DB
    public function simManagement() {
        $sims = Sim::all();

        return view('admin.simManagement', compact('sims'));
    }

    // Recupera tutti gli Admin, metodo accessibile solo da SuperAdmin
    public function adminManagement() {
        $admins = User::where('UserType', 1)->get();

        return view('admin.adminManagement', compact('admins'));
    }

    // Cambia lo stato attivo/disattivo sull'utente/admin
    public function changeStatus($id) {
        $user = User::where('id', $id)->first();

        return response()->json($user->update(['Active' => !$user->Active]));
    }

    // Ritorna la vista per cambiare le informazioni sull'utente
    public function edit($id) {
        $user = User::where('id', $id)->first();
        $sims = $user->sims;

        return view('admin.edit', compact('user', 'sims'));
    }

    // Update delle informazioni dell'utente
    public function update(User $user, UpdateUserRequest $request) {
        
        $user->update([
            "Name" => $request->input('Name'),
            "Surname" => $request->input('Surname'),
            "email" => $request->input('email'),
            "Phone" => $request->input('Phone'),
            "Address" => $request->input('Address'),
            "City" => $request->input('City'),
            "County" => $request->input('County'),
            "Country" => $request->input('Country'),
            "Notes" => $request->input('Notes'),
            "CompanyName" => $request->input('CompanyName'),
            "CompanyPhone" => $request->input('CompanyPhone'),
            "CompanyAddress" => $request->input('CompanyAddress'),
            "CompanyCity" => $request->input('CompanyCity'),
            "CompanyCounty" => $request->input('CompanyCounty'),
            "CompanyCountry" => $request->input('CompanyCountry'),
            "CompanyVAT" => $request->input('CompanyVAT'),
        ]);

        return redirect()->back()->with('message', 'Customer datas updated');
    }

    // Recupera le credenziali API per CSL 
    public function credentials() {
        $CSL = Credential::get()->first();

        return view('admin.credentials', compact('CSL'));
    }

    // Aggiorna le credenziali API per CSL
    public function updateCredentials(Request $request) {
        $CSL = Credential::get()->first();

        $CSL->update([
            'client_id' => $request->input('client_id'), 
            'client_secret' => $request->input('client_secret')
        ]);

        $CSL->save();

        return redirect()->back()->with('message', 'Credenziali aggiornate con successo');
    }

    // Chiamata asincrona con Vue per il recupero di tutti gli Admin
    public function getAdmins() {
        $users = User::where('UserType' , '1')->get();

        return response()->json($users);
    }

    public function ratesManagement() {
        return view('admin.ratesManagement');
    }

    public function getRates() {
        $rates = Rate::all();

        return response()->json($rates);
    }

    public function deleteRate(Request $request) {
        $rate = Rate::where('id', $request->id)->first();

        if(empty($rate))
            return response()->json('Rate not found', 404);

        if($rate->delete()) {
            return response()->json("Rate deleted", 200);
        }
    }

    public function updateRate(Request $request) {
        $rate = Rate::where('id', $request->id)->first();

        $rate->voice = $request->voice;
        $rate->data = $request->data;
        $rate->sms = $request->sms;
        $rate->alias = $request->alias;
        $rate->notes = $request->note;

        $rate->save(); 
    }
}
