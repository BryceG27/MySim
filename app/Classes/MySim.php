<?php 

namespace App\Classes;
/**
 * la classe contiene i principali metodi per gestire i lparco SIM di CSL, tramite le loro API
 * tutta la documentazione � su Teams > Sviluppo > File > General > MYSIM.BIZ
 * La documentazione Swagger � a questo URL: https://api.platform.csl-group.es/api/swagger#/
 * nella documentazione su Teams � presente anche un file Collection di Postman (ATIK - CSL API.postman_collection.json)
 * 
 * @author m.gigli
 *
 */
class MySim {
    
    private $CSL_BASE_URL       = 'https://api.platform.csl-group.es';
    private $CSL_CLIENT_ID      = '';
    private $CSL_CLIENT_SECRET  = '';
    private $CSL_ACCESS_TOKEN   = '';
    private $CSL_LANG           = 'en'; // usiamo en/it anche se la piattaforma CSL accetta anche altre lingue
    
    // gestione degli errori
    public $error = array();
    private $Errors = [
        "0000" => "Missing init params",
        "0001" => "Missing CSL_CLIENT_ID param",
        "0002" => "Missing CSL_CLIENT_SECRET param",
        "0003" => "Token has not been generated",
        "0004" => "NO CURL response",
        "0005" => "Missing ICC param",
        "0006" => "Missing Access Token",
        "0007" => "Error obtaining information for ICC",
        "0008" => "Missing MSISDN param",
        "0009" => "Invalid ICC/MSISDN match",
        "0010" => "No data returned from call",
        "0011" => "FROM DATE must be a valid date in YYYYMM format",
        "0012" => "TO DATE must be a valid date in YYYYMM format",
        "0013" => "FROM DATE out of range",
        "0014" => "TO DATE out of range",
        "0015" => "SIM can not preactivated",
        "0016" => "Missing RATE param",
        "0017" => "SIM can not activated",
        "0018" => "SIM can not suspended",
        "0019" => "SIM can not reactivated",
    ];
    
    
    
    public function __construct() {
        //
    }
    
    /**
     * inizializzazione della classe
     * verifica la presenza dei campi obbligatori in $params
     * i campi obbligatori sono: 
     * * CSL_CLIENT_ID
     * * CSL_CLIENT_SECRET
     * @param array $params
     * @return true/false
     */
    public function init( array $params = array() ) {
        
        $valid = true;
        
        if( empty( $params ) ) {
            $this->error[] = $this->set_error("0000");
            return false; 
        }
        
        if( !array_key_exists( 'CSL_CLIENT_ID', $params ) ) {
            $this->error[] = $this->set_error("0001");
            $valid = false;
        }
        if( !array_key_exists( 'CSL_CLIENT_SECRET', $params ) ) {
            $this->error[] = $this->set_error("0002");
            $valid = false;
        }
        
        if( $valid ) {
            $this->CSL_CLIENT_ID        = $params["CSL_CLIENT_ID"];
            $this->CSL_CLIENT_SECRET    = $params["CSL_CLIENT_SECRET"];

            // se passato come parametro, usa un token gi� esistente
            $this->CSL_ACCESS_TOKEN    = '';
            if( array_key_exists( 'CSL_ACCESS_TOKEN', $params ) && trim( $params["CSL_ACCESS_TOKEN"] ) != '' ) {
                $this->CSL_ACCESS_TOKEN = $params["CSL_ACCESS_TOKEN"];
            }
            
        }
        
        return $valid;
        
    }
    
    
    
    /**
     * acquisisce un nuovo token dalle API CSL 
     * @return boolean|string|boolean
     */
    public function get_token() {
        
        // body con credenziali di accesso
        $body = [
            "client_id"     => $this->CSL_CLIENT_ID, 
            "client_secret" => $this->CSL_CLIENT_SECRET, 
            "grant_type"    => "client_credentials",
        ];
        
        // header
        $header = array(
            'Content-Type: application/json'
        );
        
        // chiamata curl
        $response = $this->curl_call( '/api/oauth', 'POST', json_encode( $body ), $header );
        
        // response false
        if( !$response ) {
            return $response;
        }
        
        // verifica se response + json converte json in array
        if( $this->isJson( $response ) ) {
            $response = json_decode( $response, true );
        }
        
        // se risposta contiene errore, passa errore
        if( array_key_exists( "error", $response ) ) {
            if( trim( $response["error"] ) != "" ) {
                $this->error[] = $this->set_error("CURL", $response["error"] );
                return false;
            }
        }
        
        // se token valido, salva token e torna true
        if( array_key_exists( "access_token", $response ) ) {
            if( trim( $response["access_token"] ) != "" ) {
                $this->CSL_ACCESS_TOKEN = $response["access_token"];
                return true;
            }
        }
        
        // token non generato
        $this->error[] = $this->set_error("0003");
        return false;        
        
    }
    
    
    
    /**
     * torna il token attivo al richiedente
     * @return string token
     */
    public function show_token() {
        return $this->CSL_ACCESS_TOKEN;
    }
    
    
    
    
    /////////////////////////////////////////////////////////////////////////
    // METODI GET
    /////////////////////////////////////////////////////////////////////////
    
    
    /**
     * acquisisce lo status base di una SIM usando il codice ICC
     * @param unknown $CSL_ICC_ID
     * @return boolean|boolean|string
     */
    public function get_status_by_icc( $CSL_ICC_ID = null ) {
        return $this->csl_api_get_by_icc( $CSL_ICC_ID, 'status' );
    }
    
    
    /**
     * acquisisce le info aggiuntive di una SIM usando il codice ICC
     * @param unknown $CSL_ICC_ID
     * @return boolean|unknown
     */
    public function get_info_by_icc( $CSL_ICC_ID = null ) {
        return $this->csl_api_get_by_icc( $CSL_ICC_ID, 'getinfo' );
    }
    
    
    /**
     * acquisisce lo stato attuale di consumo di una SIM usando il codice ICC
     * @param unknown $CSL_ICC_ID
     * @return boolean|unknown
     */
    public function get_consumption_by_icc( $CSL_ICC_ID = null ) {
        return $this->csl_api_get_by_icc( $CSL_ICC_ID, 'consumption' );
    }
    
    
    /**
     * acquisisce lo stato di consumo per un periodo anno/mese passato come parametri
     * @param unknown $CSL_ICC_ID
     * @param unknown $CSL_FROM_DT data di inizio periodo nel formato YYYYMM
     * @param unknown $CSL_TO_DT data di fine periodo nel formato YYYYMM
     * @return boolean|boolean|unknown
     */
    public function get_historic_consumption_by_icc( $CSL_ICC_ID = null, $CSL_FROM_DT = null, $CSL_TO_DT = null ) {
        
        $valid = true;
        
        $this->error = [];
        
        // controlli sulla data FROM
        if( trim( $CSL_FROM_DT ) == '' || !is_numeric( $CSL_FROM_DT ) || strlen( $CSL_FROM_DT ) != 6 ) {
            $this->error[] = $this->set_error("0011");
            $valid = false;
        } else {
            if( $CSL_FROM_DT < 202001 || $CSL_FROM_DT > 209912 ) {
                $this->error[] = $this->set_error("0013");
                $valid = false;
            }
        }
        
        // controlli sulla data TO
        if( trim( $CSL_TO_DT ) == '' || !is_numeric( $CSL_TO_DT ) || strlen( $CSL_TO_DT ) != 6 ) {
            $this->error[] = $this->set_error("0012");
            $valid = false;
        } else {
            if( $CSL_TO_DT < 202001 || $CSL_TO_DT > 209912 ) {
                $this->error[] = $this->set_error("0014");
                $valid = false;
            }
        }
        
        // se necessario inverte FROM e TO
        if( !$valid ) {
            return false;
        } else {
            if( $CSL_FROM_DT > $CSL_TO_DT ) {
                $temp = $CSL_TO_DT;
                $CSL_TO_DT = $CSL_FROM_DT;
                $CSL_FROM_DT = $temp;
            }
        }
        
        //
        $dt_params = [
            "CSL_FROM_DT"   => $CSL_FROM_DT,
            "CSL_TO_DT"     => $CSL_TO_DT,
        ];        
        return $this->csl_api_get_by_icc( $CSL_ICC_ID, 'historicconsumption', $dt_params );
        
    }
    
    
    /**
     * chiamata API base per i metodi GET
     * @param unknown $CSL_ICC_ID
     * @param unknown $function
     * @param unknown $DT_PARAMS
     * @return boolean|boolean|string|unknown
     */
    private function csl_api_get_by_icc( $CSL_ICC_ID = null, $function = null, $DT_PARAMS = null ) {
        
        $this->error = [];
        
        // verifica ICC obbligatorio
        if( trim( $CSL_ICC_ID ) == '' ) {
            $this->error[] = $this->set_error("0005");
            return false;
        }
        
        // verifica access token
        if( trim( $this->CSL_ACCESS_TOKEN ) == '' ) {
            $this->error[] = $this->set_error("0006");
            return false;
        }
        
        // se necessario imposta la query per gestione range di date FROM/TO
        $dt_url_params = '';
        if( !empty( $DT_PARAMS ) && $function == 'historicconsumption' ) {
            $dt_url_params = '/from_dt/'. $DT_PARAMS["CSL_FROM_DT"] .'/to_dt/'. $DT_PARAMS["CSL_TO_DT"];
        }
        
        // body
        $body = [];
        
        // header
        $header = array(
            'Authorization: Bearer '. $this->CSL_ACCESS_TOKEN,
            'Content-Type: application/json'
        );
        
        // chiamata curl
        $response = $this->curl_call( '/api/rest/extension/'. $function .'/icc/'. $CSL_ICC_ID . $dt_url_params .'?_lang='. $this->CSL_LANG, 'GET', json_encode( $body ), $header );
        
        // response false
        if( !$response ) {
            return $response;
        }
        
        // gestione errore anomalo da parte di CSL con risposta stringa = "Array"
        if( $response == "Array" ) {
            $this->error[] = $this->set_error("0007");
            return false;
        }
        
        // verifica se response + json converte json in array
        if( $this->isJson( $response ) ) {
            $response = json_decode( $response, true );
        }
        
        // verifica la presenza del nodo "data"
        if( array_key_exists( "data", $response ) ) {
            return $response["data"];
        } else {
            $this->error[] = $this->set_error("0010");
            return false;
        }
        
    }
    

    /////////////////////////////////////////////////////////////////////////
    // METODI SET
    /////////////////////////////////////////////////////////////////////////
    
    
    
    /**
        Imposta lo stato di preattivazione della SIM tramite l'ID ICC.
        @param string|null      $CSL_ICC_ID      L'ID ICC della SIM (opzionale).
        @return mixed                            Il risultato dell'operazione di impostazione del CSL API.
    */
    public function set_preactivate_by_icc( $CSL_ICC_ID = null ) {
        return $this->csl_api_set_by_icc( $CSL_ICC_ID, 'preactivate' );
    }
    
    /**
        Imposta l'attivazione della SIM tramite l'ID ICC.
        @param string|null      $CSL_ICC_ID     L'ID ICC della SIM (opzionale).
        @param string|null      $CSL_RATE       La tariffa della SIM (opzionale).
        @return mixed                           Il risultato dell'operazione di impostazione del CSL API.
    */
    public function set_activate_by_icc( $CSL_ICC_ID = null, $CSL_RATE = null ) {
        return $this->csl_api_set_by_icc( $CSL_ICC_ID, 'activate', $CSL_RATE  );
    }
    
    /**
        Imposta la sospensione della SIM tramite l'ID ICC.
        @param string|null      $CSL_ICC_ID     L'ID ICC della SIM (opzionale).
        @return mixed                           Il risultato dell'operazione di impostazione del CSL API.
    */
    public function set_suspend_by_icc( $CSL_ICC_ID = null ) {
        return $this->csl_api_set_by_icc( $CSL_ICC_ID, 'suspend' );
    }
    
    /**
        Imposta la riattivazione della SIM tramite l'ID ICC.
        @param string|null      $CSL_ICC_ID     L'ID ICC della SIM (opzionale).
        @return mixed                           Il risultato dell'operazione di impostazione del CSL API.
    */
    public function set_reactivate_by_icc( $CSL_ICC_ID = null ) {
        return $this->csl_api_set_by_icc( $CSL_ICC_ID, 'reactivate' );
    }
    
    
    
    
    /**
        esegue la chiamata CURL in modalit� SET (PUT)
        @param string|null      $CSL_ICC_ID     L'ID ICC della SIM (opzionale).
        @param string|null      $function       La funzione API da chiamare (opzionale).
        @param array|null       $PARAMS         I parametri per la chiamata (opzionale).
        @return mixed                           Il risultato dell'operazione di chiamata API 
    */
    private function csl_api_set_by_icc( $CSL_ICC_ID = null, $function = null, $PARAMS = null ) {
        
        $this->error = [];
        
        // verifica ICC obbligatorio
        if( trim( $CSL_ICC_ID ) == '' ) {
            $this->error[] = $this->set_error("0005");
            return false;
        }
        
        // verifica access token
        if( trim( $this->CSL_ACCESS_TOKEN ) == '' ) {
            $this->error[] = $this->set_error("0006");
            return false;
        }

        
        // body
        $body = [];
        
        // se necessario imposta il body per gestione di parametri in base alla chiamata
        switch( $function ) {
            case 'activate':
                if( empty( $PARAMS ) ) {
                    $this->error[] = $this->set_error("0016");
                    return false;
                } else {
                    $body = array(
                        "newrate" => $PARAMS
                    );
                }
                break;
        }
        
        // header
        $header = array(
            'Authorization: Bearer '. $this->CSL_ACCESS_TOKEN,
            'Content-Type: application/json',
            'accept: application/json'
        );
        
        
        // chiamata curl
        $response = $this->curl_call( '/api/rest/extension/'. $function .'/icc/'. $CSL_ICC_ID .'?_lang='. $this->CSL_LANG, 'PUT', json_encode( $body ), $header );
        
        
        // response false
        if( !$response ) {
            return $response;
        }
        
        // gestione errore anomalo da parte di CSL con risposta stringa = "Array"
        if( $response == "Array" ) {
            $this->error[] = $this->set_error("0007");
            return false;
        }
        
        // verifica se response + json converte json in array
        if( $this->isJson( $response ) ) {
            $response = json_decode( $response, true );
        }
        
        // verifica la presenza del nodo "data"
        if( array_key_exists( "data", $response ) ) {
            return $response["data"];
        } else {
            
            switch( $function ) {
                case 'preactivate':
                    $this->error[] = $this->set_error("0015");
                    break;
                case 'activate':
                    $this->error[] = $this->set_error("0017");
                    break;
                case 'suspend':
                    $this->error[] = $this->set_error("0018");
                    break;
                case 'reactivate':
                    $this->error[] = $this->set_error("0019");
                    break;
            }
            return false;
        }
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    ////////////////////////////////////////////////////////////////////////////////////////
    // METODI CUSTOM PER LA GESTIONE DELLE SIM
    ////////////////////////////////////////////////////////////////////////////////////////
    
    
    /**
     * verifica la corrispondenza tra ICC e MSISDN
     * @param unknown $CSL_ICC_ID
     * @param unknown $CSL_MSISDN
     * @return boolean
     */
    public function check_icc_msisdn_match( $CSL_ICC_ID = null, $CSL_MSISDN = null ) {
        
        $this->error = [];
        
        if( trim( $CSL_MSISDN ) == '' ) {
            $this->error[] = $this->set_error("0008");
            return false;
        }
        
        // acquisisco i dati della SIM partendo dal ICC
        if( !$response = $this->csl_api_get_by_icc( $CSL_ICC_ID, 'getinfo' ) ) {
            return false;
        }
        
        // verifico la presenza del campo "MSISDN"
        $extension_msisdn = '';
        if( array_key_exists( "extension", $response ) ) {
            if( array_key_exists( "msisdn", $response["extension"] ) ) {
                $extension_msisdn = trim( $response["extension"]["msisdn"] );
            }
        }
        
        // verifico la corrispondenza e torno esito
        if( trim($CSL_MSISDN) == $extension_msisdn ) {
            return true;
        } else {
            $this->error[] = $this->set_error("0009");
            return false;
        }
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    ///////////////////////////////////////////////////////////////////////////
    // METODI E FUNZIONI BASE
    ///////////////////////////////////////////////////////////////////////////
    
    
    /**
     * chiamata CURL per tutte le API
     * @param string $endpoint endpoint della chiamata 
     * @param string $method metodo della chiamata (POST/GET)
     * @param string $body body JSON della chiamata
     * @param array $header header della chiamata
     * @return boolean|string JSON|string
     */
    private function curl_call( $endpoint = null, $method = null, $body = null, $header = null ) {        
        
        $this->error = [];
        
        try{
            $curl = curl_init();
            
            curl_setopt_array(
                $curl, 
                array(
                    CURLOPT_URL             => $this->CSL_BASE_URL . $endpoint,
                    CURLOPT_RETURNTRANSFER  => true,
                    CURLOPT_ENCODING        => '',
                    CURLOPT_MAXREDIRS       => 10,
                    CURLOPT_TIMEOUT         => 0,
                    CURLOPT_FOLLOWLOCATION  => true,
                    CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST   => $method,
                    CURLOPT_POSTFIELDS      => $body,
                    CURLOPT_HTTPHEADER      => $header,
            ));
            $response = curl_exec($curl);
            
            // verifica eventuale errore HTTP
            if( curl_errno( $curl )) {
                $this->error[] = $this->set_error( "CURL", curl_error($curl) );
                curl_close($curl);
                return false;
            }
            
            curl_close($curl);
            
            // verifica risposta ottenuta
            if( empty( $response ) ) {
                $this->error[] = $this->set_error("0004");
                return false;
            } /*elseif( !$this->is_json( $response ) ) {
                $this->Errors = $response;
                return false;
            } */else {
                return $response;
            }
        } catch( Exception $e ) {
            $this->error[] = $this->set_error( "CURL",  $e->getMessage() );
            return false;
        }
        
        
    }
    
    
    /**
     * verifica se una stringa � in formato JSON valido oppure no
     * @param unknown $string
     * @return boolean
     */
    public function isJson( $string ) {
        json_decode( $string );
        return json_last_error() === JSON_ERROR_NONE;
    }
    
    
    
    
    /**
     * imposta codice e messaggio di errore in base ai parametri passati
     * @param unknown $error_code
     * @param string $error_message
     * @return unknown[]|string[]
     */
    private function set_error( $error_code, $error_message = '' ) {
        
        if( trim( $error_message ) == '' ) {
            return [
                "code" => $error_code,
                "error"=> $this->Errors[$error_code]
            ];
        } else {
            return [
                "code" => $error_code,
                "error"=> $error_message
            ];
        }
        
    }
    
    
    
    
} //////////////////////////

