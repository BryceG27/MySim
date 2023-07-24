<?php

use App\Models\Sim;
use App\Mail\UserMail;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Al passaggio dalla home, recupera informazioni utente, setta la lingua e reindirizza in base all'utente
Route::get('/', function() {
    /* App::setLocale(Auth::user()->Lang); */
    
    if(Auth::user() && Auth::user()->UserType != 2) {
        return redirect(route('userManagement'));
    } else {
        return view('index');
    }

})->middleware('auth')->name('home');

// @deprecated
Route::get('/register/{icc}/{msisdn}/{check?}', function($icc, $msisdn, $check = '') {
    return redirect()->route('register', compact('icc', 'msisdn', 'check'));
})->name('registerSim');

// Route per lo scan del QRCode
Route::get('/sim/scanned/{iccid}/{msisdn}', [PublicController::class, 'scanSim'])->withoutMiddleware('auth')->name('scanSim');

Route::get('/change-password', [PublicController::class, 'changePasswordView'])->name('change-password');

// Sezione riguardante i profili
Route::get('/profile', [PublicController::class, 'profile'])->name('profile');
Route::put('/profile/update', [PublicController::class, 'updateProfile'])->name('updateProfile');

// Rotta POST per inserimento del CSV da chiamata asincrona in Vue
Route::post('/upload/csv', [SimController::class, 'csv'])->name('uploadCSV');

/**
 * --------------- Sezione gestone utenti e SIM ------------------
*/

// View per tutti gli utenti NON admin registrati e rotta parametrica utente
Route::get('/users', [AdminController::class, 'userManagement'])->name('userManagement');
Route::get('/users/edit/{id}', [AdminController::class, 'edit'])->name('editUser');

// Chiamata PUT e DELETE per l'utente
Route::put('/user/update/{user}', [AdminController::class, 'update'])->name('updateUser');
Route::delete('/user/delete/{user}', [AdminController::class, 'delete'])->name('deleteUser');

/**
 * Chiamate asincrone da Vue per:
 * - Recupero di tutti gli utenti
 * - Recupero di un singolo utente
 * - Attivazione/disattivazione utente
 */
Route::get('/users/api', [AdminController::class, 'getUsers']);
Route::get('/users/api/getUser/{id}', [AdminController::class, 'getUser']);
Route::put('/users/api/active/{id}', [AdminController::class, 'changeStatus'])->name('changeStatus');

// Recupero delle viste asincrone per tutte le SIM e per tutti gli Admin. Solo il SuperAdmin potrà accedere alla schermata *adminManagement*
Route::get('/sims', [AdminController::class, 'simManagement'])->name('simManagement');
Route::get('/sims/rates', [AdminController::class, 'ratesManagement'])->name('rates');

Route::get('/sims/rates/api', [AdminController::class, 'getRates']);
Route::post('/sims/rates/api/delete', [AdminController::class, 'deleteRate']);
Route::put('/sims/rates/api/update', [AdminController::class, 'updateRate']);

Route::get('/admins', [AdminController::class, 'adminManagement'])->middleware('superadmin')->name('adminManagement');

Route::get('/admins/api', [AdminController::class, 'getAdmins'])->middleware('superadmin');

// Chiamate asincrone per update dei dati delle sim
Route::get('/api/getSims', [SimController::class, 'getAllSims']);
Route::get('/api/updateSimData/{id}', [SimController::class, 'getSimCSL'])->name('getSimCSL');
Route::get('/api/updateSimDataByUser/{id}', [SimController::class, 'updateSimCSL'])->name('updateSimCSL');

Route::post('/api/getSimsByFilter', [SimController::class, 'getSimsByFilter']);

Route::post('/api/checkSim', [SimController::class, 'getSim'])->withoutMiddleware('auth')->name('getSim');
Route::post('/api/sim/new', [SimController::class, 'newSim'])->middleware('admin')->name('newSim');
Route::post('/api/update/simName', [SimController::class, 'updateName']);
Route::get('/sim/qrcode/{icc?}/{msisdn?}', [SimController::class, 'qrCode'])->middleware('admin');
Route::post('/api/sim/changeSimStatus', [SimController::class, 'changeSimStatus'])->middleware('admin');

Route::get('/credentials', [AdminController::class, 'credentials'])->name('credentials');
Route::put('/credentials/update', [AdminController::class, 'updateCredentials'])->name('updateCredentials');

Route::post('/setLocale/{lang}', function($lang) {
    session()->put('locale', $lang);

    return redirect()->back();
})->name('setLocale');

Route::get('/test', function() {
    $client = new Client();
    $response = $client->request('POST', 'http://customerservice.atik.it/api/login', [
        'header' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ],
        'json' => [
            'email' => 'SuperAdmin',
            'password' => 'Sup3r%4dm1n22!'
        ]
    ]);

    echo $response->getBody();
});