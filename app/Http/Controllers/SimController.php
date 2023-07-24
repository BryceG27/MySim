<?php

namespace App\Http\Controllers;

use App\Models\Sim;
use App\Models\Rate;
use App\Models\User;
use App\Classes\MySim;
use App\Models\Credential;
use Illuminate\Http\Request;
use App\Http\Requests\SimRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class SimController extends Controller {

    // Per accedere ai metodi di questo controller, bisogna essere autenticato
    public function __construct() {
        $this->middleware('auth');
    }

    // Restituisci tutte le SIM associate all'utente autenticato
    public function index() {
        $sims = Sim::where('id_user', Auth::user()->id);
        return view('home', compact('Sim'));
    }

    // Associa l'utente alla SIM, chiamata POST
    public function getSim(Request $simData) {
        $sim = Sim::where('iccid', $simData->icc)
                ->where('msisdn', $simData->msisdn)
                ->first();

        if(empty($sim)) {

            $response = 'ko';

        } else if($sim->user_id == null) {

            $response = 'ok';

            if($simData->directAdd) {
                $user_id = Auth::user()->id;

                $sim->update([
                    'user_id' => $user_id
                ]);
            }
            
        } else if($sim->user_id != null) {

            $response = 'in use';

        }
        
        return response()->json($response);
    }

    /**
     *  Chiamata asincrona e API verso CSL, inserimento uno alla volta
     */
    public function getSimCSL($id) {
        $mySim          = new MySim();

        // Credenziali API
        $credentials    = Credential::where('id', 1)->first();

        $sim    = Sim::where('id', $id)->first();
        $icc    = $sim->iccid;
        $msisdn = $sim->msisdn;

        $CSL_CLIENT_ID      = $credentials->client_id;
        $CSL_CLIENT_SECRET  = $credentials->client_secret;
        
        $params = array(
            'CSL_CLIENT_ID'     => $CSL_CLIENT_ID,
            'CSL_CLIENT_SECRET' => $CSL_CLIENT_SECRET,
        );
        
        if($mySim->init($params)) {
            $mySim->get_token();

            $status      = $mySim->get_status_by_icc($icc);
            $info        = $mySim->get_info_by_icc($icc);
            $consumption = $mySim->get_consumption_by_icc($icc);

            if(!$status || !$info || !$consumption)
                return response()->json($mySim->error, 400);

            $sim->update([
                'status' => $status['extension']['extension_status'],
                'data'   => $consumption['consumption']['data'],
                'voice'  => $consumption['consumption']['voice'],
                'sms'    => $consumption['consumption']['sms'],
                'rate'   => $info['extension']['rate']
            ]);

            $this->checkRate($info['extension']['rate']);

            $sim = Sim::where('id', $id)->first();

            return response()->json($sim);
        }

        return response()->json('Connection error');
    }

    /**
     * Chiamata API per l'aggiornamento dei dati della SIM
     */
    public function updateSimCSL($id) {
        $user           = User::where('id', $id)->first();
        $credentials    = Credential::where('id', 1)->first();
        $mySim          = new MySim();

        $user_sims      = $user->sims;

        $icc    = '';
        $msisdn = '';

        foreach ($user_sims as $sim) {
            $icc    = $sim->iccid;
            $msisdn = $sim->msisdn;

            $CSL_CLIENT_ID      = $credentials->client_id;
            $CSL_CLIENT_SECRET  = $credentials->client_secret;
            
            $params = array(
                'CSL_CLIENT_ID'     => $CSL_CLIENT_ID,
                'CSL_CLIENT_SECRET' => $CSL_CLIENT_SECRET,
            );

            
            if($mySim->init($params)) {
                $mySim->get_token();

                $status = $mySim->get_status_by_icc($icc);
                $info = $mySim->get_info_by_icc($icc);
                $consumption = $mySim->get_consumption_by_icc($icc);

                if(!$status || !$info || !$consumption)
                    return response()->json($mySim->error);

                $sim->update([
                    'status'    => $status['extension']['extension_status'],
                    'data'      => $consumption['consumption']['data'],
                    'voice'     => $consumption['consumption']['voice'],
                    'sms'       => $consumption['consumption']['sms'],
                    'rate'      => $info['extension']['rate']
                ]);

                $this->checkRate($info['extension']['rate']);
            } else {
                return response()->json('401');
            }
        }

        return response()->json('201');
    }

    public function getAllSims() {
        $sims = Sim::all();

        foreach ($sims as $sim) {
            if(empty($sim->expires_in))
                $sim->expires_in = 'Not Setted';
            else
                $sim->expires_in = date('d-m-Y', strtotime($sim->expires_in));
        }

        return response()->json($sims);
    }

    /**
     * Chiamata asincrona per la creazione di una nuova SIM nel DB
     * - Classe creata da Mauro
     * - Credential è un modello contenente le credenziali API verso CSL
     * - Metodo previsto per il caricamento tramite CSV
     */
    public function newSim(SimRequest $request, $cicle = false) {

        $mySim          = new MySim();
        $credentials    = Credential::where('id', 1)->first();

        $iccid  = $request->input('iccid');
        $msisdn = $request->input('msisdn');

        // Se nel File CSV è contenuto il "Profilo", carica anche quello
        if($request->input('rate') != null) {
            $rate = $request->input('rate');
            $this->checkRate($rate);
        } else {
            $rate = '';
        }

        // Se in sessione vi è salvato l'access_token, usalo, altrimenti generalo
        $access_token = session('csl_access_token') ?? '';

        $csl_params = array(
            'CSL_CLIENT_ID'     => $credentials->client_id,
            'CSL_CLIENT_SECRET' => $credentials->client_secret,
            'CSL_ACCESS_TOKEN'  => $access_token,
        );

        if($mySim->init($csl_params)) {

            if(empty(session('csl_access_token'))) {
                $mySim->get_token();
                session(['csl_access_token' => $mySim->show_token()]);
            }

            if($mySim->check_icc_msisdn_match($iccid, $msisdn)) {
                $status = $mySim->get_status_by_icc($iccid);
                $consumption = $mySim->get_consumption_by_icc($iccid);

                try {
                    $response = Sim::create([
                        'iccid'  => $iccid,
                        'msisdn' => $msisdn,
                        'status' => $status['extension']['extension_status'],
                        'data'   => $consumption['consumption']['data'],
                        'voice'  => $consumption['consumption']['voice'],
                        'sms'    => $consumption['consumption']['sms'],
                        'msisdn' => $request->input('msisdn'),
                        'rate'   => $rate
                    ]);

                } catch (QueryException $e) {
                    if($cicle) 
                        return array(400, "Sim already imported.");

                    return response()->json('Sim already imported.', 400);
                }
        
                if($cicle)
                    return array(200, $response);

                return response()->json($response);

            } else {
                if($cicle)
                    return array(404, $mySim->error);
                return response()->json($mySim->error, 404);
            }
        } else {
            if ($cicle)
                return array(400, "Can't initialize MySim Class.");

            return response()->json("Can't initialize MySim Class.", 400);
        }
    }

    // Unico metodo per la generazione dei QRCodes
    public function qrCode($icc = null, $msisdn = null) {

        if(!empty($icc) && !empty($msisdn)) {
            return view('qrCode', compact('icc', 'msisdn'));
        } else {
            $sims = Sim::all();

            return view('qrcodes', compact('sims'));
        }
    }

    // Metodo per il parse del file CSV
    public function csv(Request $request) {
        $file = $request->file('csv');
        $csv = [];

        if(($open = fopen($file->path(), 'r')) !== FALSE) {
            $headers = fgetcsv($open, 1000, ';');

            while(($data = fgetcsv($open, 1000, ';')) !== FALSE) {
                $csv[] = $data;
            }

            if($request->getRows == true)
                return response()->json(count($csv));

            if(strlen($csv[0][0]) == 15 && strlen($csv[0][1]) == 20) {
                $msisdn_pos = 0;
                $iccid_pos = 1;
            } else if (strlen($csv[0][1]) == 15 && strlen($csv[0][0]) == 20){
                $msisdn_pos = 1;
                $iccid_pos = 0;
            } else {
                return response()->json('Formato CSV non valido', 400);
            }

            foreach ($csv as $key => $sim) {
                $msisdn = $sim[$msisdn_pos];
                $iccid = $sim[$iccid_pos];

                if(isset($headers[2])) 
                    $rate = $sim[2];
                else
                    $rate = '';
                
                $request = new SimRequest(array(
                    'msisdn' => $msisdn,
                    'iccid' => $iccid,
                    'rate' => $rate
                ));

                $response[$key] = $this->newSim($request, true);
            }
            
            return response()->json($response);
        }
    }

    public function updateName(Request $request) {
        $simID = $request->id;
        $newName = $request->newName;

        if(strlen($newName) > 10) 
            return response()->json('400');

        $sim = Sim::where('id', $simID)->first();

        $sim->name = $newName;
        $sim->save();

        return response()->json(200);
    }

    public function associateSim($user_id, Sim $sim) {
        $mySim          = new MySim();
        $credentials    = Credential::where('id', 1)->first();

        $iccid = $sim->iccid;

        $access_token = session('csl_access_token') ?? '';

        $csl_params = array(
            'CSL_CLIENT_ID'     => $credentials->client_id,
            'CSL_CLIENT_SECRET' => $credentials->client_secret,
            'CSL_ACCESS_TOKEN'  => $access_token,
        );

        if($mySim->init($csl_params)) {
            if(empty($_SESSION['csl_access_token'])) {
                $mySim->get_token();
                session(['csl_access_token' => $mySim->show_token()]);
            }

            $response = $mySim->set_preactivate_by_icc($iccid);

            if($response != false) {
                $sim->expires_in = date('Y-m-d', strtotime('+12 months'));
                $sim->user_id = $user_id;
                $sim->update();

                return true;
            } else {
                return $mySim->error;
            }
        }
    }

    public function checkRate($entering_rate) {
        try {
            Rate::create([
                'rate' => $entering_rate,
                'data' => 0,
                'voice' => 0,
                'sms' => 0
            ]);
        } catch (QueryException $e) {
            
        }   
    }

    public function changeSimStatus(Request $request) {
        $mySim = new MySim();
        $credentials = Credential::where('id', 1)->first();
        $sim = Sim::where('id', $request->id)->first();

        $iccid = $sim->iccid;

        $access_token = session('csl_access_token') ?? '';

        $csl_params = array(
            'CSL_CLIENT_ID'     => $credentials->client_id,
            'CSL_CLIENT_SECRET' => $credentials->client_secret,
            'CSL_ACCESS_TOKEN'  => $access_token,
        );

        if($mySim->init($csl_params)) {
                switch ($request->status) {
                    case '3':
                        $response = $mySim->set_reactivate_by_icc($iccid);
                        
                        break;
                    
                    case '5':
                        break;
                        $response = $mySim->set_suspend_by_icc($iccid);
        
                    case '4':
                        break;
        
                    default:
                        $response = $mySim->set_deactivate_by_icc($iccid);
                        break;
                }
        }

        if(!$response)
            return response()->json($response->error, 500);
        else 
            return response()->json('OK', 200);
    }

    public function getSimsByFilter(Request $filters) {
        $sims = null;
        $sim_result = [];

        if (empty($filters->email)) {
            switch ($filters->assigned) {
                case 0:
                    if (!empty($filters->msisdn)) {
                        $sims = Sim::where(['msisdn' => $filters->msisdn, 'user_id' => null])->get();
                    }
    
                    if (!empty($filters->iccid) && !$sims) {
                        $sims = Sim::where(['iccid' => $filters->iccid, 'user_id' => null])->get();
                    }
    
                    if(!$sims) {
                        $sims = Sim::where('user_id', null)->get();
                    }
    
                    break;
                
                case 1:
                    if (!empty($filters->msisdn)) {
                        $sims = Sim::where('msisdn', $filters->msisdn)->whereNotNull('user_id')->get();
                    }
    
                    if (!empty($filters->iccid) && !$sims) {
                        $sims = Sim::where('iccid', $filters->iccid)->whereNotNull('user_id')->get();
                    }
    
                    if(!$sims) {
                        $sims = Sim::whereNotNull('user_id')->get();
                    }
                    break;
    
                case 2:
                    if (!empty($filters->msisdn)) {
                        $sims = Sim::where('msisdn', $filters->msisdn)->get();
                    }
    
                    if (!empty($filters->iccid) && !$sims) {
                        $sims = Sim::where('iccid', $filters->iccid)->get();
                    }
    
                    if(!$sims) {
                        $sims = Sim::all();
                    }
    
                    break;
    
                default:
                    return response()->json("ERROR!!!", 401);
                    break;
            }
    
            if(!empty($sims)) {
                if(!empty($filters->status)) {
                    foreach($sims as $sim) {
                        foreach($filters->status as $status) {
                            if($sim->status == $status) {
                                array_push($sim_result, $sim);
                                break;
                            }
                        }
                    }
                }
                return response()->json($sims, 200);
            } else {
                return response()->json('Not Found', 404);
            }
        } else {
            $user = User::where('email', $filters->email)->first();

            if($user) {
                $sims = Sim::where('user_id', $user->id)->get();

                if(!empty($sims))
                    return response()->json($sims, 200);
                else
                    return response()->json('Sim not Found', 404);
            } else {
                return response()->json("User not found", 404);
            }
        }
    }
}
