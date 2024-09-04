<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\storage\FileStorage;

if (!defined('WHMCS')) {
    die('This file cannot be accessed directly');
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';


/**
 * @return array
 */
function aksaradatassl_MetaData()
{
    return [
        'DisplayName' => 'Aksaradata SSL',
        'APIVersion' => '1.0', // Use API Version 1.0
        'RequiresServer' => false,
    ];
}

/**
 * @return array
 */
function aksaradatassl_ConfigOptions($params)
{
    $aksara = new Akasara;
    $request = $aksara->request("https://api6.irsfa.id/api/pricing?type=ssl","GET", '',null);
    $res = $request->data;
    
    $products = [];
    foreach ($res as $product) {
        $products[$product->product_id] = $product->product_name;
    }

    return [
        'Grant Type' => [
            'Type' => 'text',
            'Size' => '30',
            'Dafault' => 'client_credentials',
        ],
        'SSL Product' => [
            'Type' => 'dropdown',
            'Options' => $products,
        ],
        'Client ID' => [
            'Type' => 'text',
            'Size' => '30',
            'Dafault' => '',
        ],
        'Secret Key' => [
            'Type' => 'text',
            'Size' => '30',
            'Dafault' => '',
        ],
        'Aksaradata API URL' => [
            'Type' => 'text',
            'Size' => '60',
            'Default' => ''
        ],
        'SSL Panel URL' => [
            'Type' => 'text',
            'Size' => '60',
            'Default' => ''
        ],
        'Default language' => [
            'Type' => 'dropdown',
            'Options' => ['en_GB', 'ru_RU', 'es_ES', 'nl_NL'],
        ],
    ];
}

/**
 * @param array $params
 *
 * @return string
 */
function aksaradatassl_CreateAccount($params)
{
    try {
        // dummy create
        
    } catch (\Exception $e) {
        return $e->getMessage();
    }
    return 'success';
}


function aksaradatassl_process_ssl($params) {

	try {
        logModuleCall(
            'aksaradatassl',
            'aksaradatassl_CreateAccount',
            $params,
            '',
            'Attempt to create new order'
        );
        
        $aksara = new Akasara;
        return $aksara->create($params);
    } catch (\Exception $e) {
        return $e->getMessage();
    }

}


/**
 * @param $params
 *
 * @return string
 */
function aksaradatassl_Renew($params)
{
    $aksara = new Akasara;
    return $aksara->renew($params);
}

/**
 * @param $params
 *
 * @return string
 */
function aksaradatassl_Cancel($params)
{
    $aksara = new Akasara;
    return $aksara->cancel($params);
}

/**
 * @param array $params
 *
 * @return array|string
 */
function aksaradatassl_ClientArea($params)
{
        
    if ( $params['status'] == 'Pending'){
        return ['tabOverviewReplacementTemplate' => 'templates/clientarea-default'];
    }

    //Define Aksara
    $aksara = new Akasara;
    
    $fullMessage = null;
    $order = null;
    $updatedData = [];
    $hostname =$params['domain'];
    $errormsg = false;
    
    if(isset($_POST['createSSL'])){
        
        $storage = new FileStorage(__DIR__ . "/api.bucket");
        $rate    = new Rate(1, Rate::MINUTE);  // Change the rate to 3 requests per minute
        $bucket  = new TokenBucket(1, $rate, $storage);
        $bucket->bootstrap(1);
        
        if (!$bucket->consume(1, $seconds)) {
            echo "Try again in :" . floor($seconds) . " second. Go back <a href='/clientarea.php'>here</a>";
            exit();
        }
        
        if(isset($_POST['hostname'])){
            $hostname = $_POST['hostname'];
            $params['model']->serviceProperties->save(['Domain' => $hostname]);
        }else{
            $hostname =$params['domain'];
        }
        
        if(isset($_POST['csr'])){
            $csr = $_POST['csr'];
            $params['model']->serviceProperties->save(['CSR' => $csr]);
        }
        
        if(isset($_POST['contact'])){
            $contact_id = $_POST['contact'];
            $params['model']->serviceProperties->save(['contact_id' => $contact_id]);
        }
        
        if(isset($_POST['approval_email'])){
            $approval_email = $_POST['approval_email'];
            $params['model']->serviceProperties->save(['Approval Email' => $approval_email]);
        }else{
            $approval_email = $params['customfields']['Approval Email'];
        }
        
        if(isset($_POST['validation_method'])){
            $validation_method = $_POST['validation_method'];
            $domain_validation_method = [
                "hostName" => $hostname,
                "method" => $validation_method
            ];
            $params['model']->serviceProperties->save(['Domain Validation Method' => json_encode($domain_validation_method)]);
        }
        
        
    }
    
    if(isset($_POST['createContact'])){
        if(isset($_POST['company_name'])){
            $company_name = $_POST['company_name'];
        }
        
        if(isset($_POST['first_name'])){
            $first_name = $_POST['first_name'];
        }
        
        if(isset($_POST['last_name'])){
            $last_name = $_POST['last_name'];
        }
        
        if(isset($_POST['gender'])){
            $gender = $_POST['gender'];
        }
        
        if(isset($_POST['telephone_number'])){
            $telephone_number = $_POST['telephone_number'];
        }
        
        if(isset($_POST['street'])){
            $street = $_POST['street'];
        }
        
        if(isset($_POST['number'])){
            $number = $_POST['number'];
        }
        
        if(isset($_POST['country'])){
            $country = $_POST['country'];
        }
        
        if(isset($_POST['language'])){
            $language = $_POST['language'];
        }
        
        if(isset($_POST['state'])){
            $state = $_POST['state'];
        }
        
        if(isset($_POST['city'])){
            $city = $_POST['city'];
        }
        
        if(isset($_POST['zip_code'])){
            $zip_code = $_POST['zip_code'];
        }
        
        if(isset($_POST['email'])){
            $email = $_POST['email'];
        }
    }
    
    if(isset($_POST['reissue_ssl'])){
        if(isset($_POST['hostname'])){
            $hostname = $_POST['hostname'];
            $params['model']->serviceProperties->save(['Domain' => $hostname]);
        }else{
            $hostname =$params['domain'];
        }
        
        if(isset($_POST['csr'])){
            $csrReissue = $_POST['csr'];
            $params['model']->serviceProperties->save(['CSR' => $csr]);
        }
        
        if(isset($_POST['contact'])){
            $contact_id = $_POST['contact'];
            $params['model']->serviceProperties->save(['contact_id' => $contact_id]);
        }
        
        if(isset($_POST['approval_email'])){
            $approval_email = $_POST['approval_email'];
            $params['model']->serviceProperties->save(['Approval Email' => $approval_email]);
        }else{
            $approval_email = $params['customfields']['Approval Email'];
        }
        
        if(isset($_POST['validation_method'])){
            $validation_method = $_POST['validation_method'];
            $domain_validation_method = [
                "hostName" => $hostname,
                "method" => $validation_method
            ];
            $params['model']->serviceProperties->save(['Domain Validation Method' => json_encode($domain_validation_method)]);
        }
    }
    
    if(isset($_POST['renew_ssl'])){
        if(isset($_POST['hostname'])){
            $hostname = $_POST['hostname'];
            $params['model']->serviceProperties->save(['Domain' => $hostname]);
        }else{
            $hostname =$params['domain'];
        }
        
        if(isset($_POST['csr'])){
            $csrRenew = $_POST['csr'];
            $params['model']->serviceProperties->save(['CSR' => $csr]);
        }
        
        if(isset($_POST['contact'])){
            $contact_id = $_POST['contact'];
            $params['model']->serviceProperties->save(['contact_id' => $contact_id]);
        }
        
        if(isset($_POST['approval_email'])){
            $approval_email = $_POST['approval_email'];
            $params['model']->serviceProperties->save(['Approval Email' => $approval_email]);
        }else{
            $approval_email = $params['customfields']['Approval Email'];
        }
        
        if(isset($_POST['validation_method'])){
            $validation_method = $_POST['validation_method'];
            $domain_validation_method = [
                "hostName" => $hostname,
                "method" => $validation_method
            ];
            $params['model']->serviceProperties->save(['Domain Validation Method' => json_encode($domain_validation_method)]);
        }
    }
    
    if(isset($_POST['verifyDns'])){
        $domainVerify = $params['domain'];
        $resultVerify = dns_get_record('naracash.id', DNS_CAA);
        $caa = null;
        
        foreach($resultVerify as $obj) {
            if ($obj['value'] =='certum.pl' && $obj['flag'] == 0 && ($obj['tag'] == "issue" || $obj['tag'] == "issuewild")) {
                $caa = $obj['value'];
                break;
            }
        }
        
        if (boolval($caa)){
            $params['model']->serviceProperties->save(['is_verified' => $caa]);
        }else{
            $errormsg = 'DNS gagal terverifikasi. Perubahan DNS biasanya memerlukan waktu untuk terpasang sempurna. Jika sistem kami tidak dapat langsung berhasil memverifikasi, silakan menunggu 1-2 Hari kemudian mencoba Verifikasi kembali';
        }
    }
    
    $url = $params['configoption5'];
    //akun reseller
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => $params['configoption3'],
        "client_secret" => $params['configoption4'],
        "scope" => "",
    ];
    
    //get token from rest irsfa
    $auth = $aksara->authentication($url, $oauth2);
    
    if($company_name && $first_name && $last_name && $gender && $number
    && $telephone_number && $street && $city && $country && $email
    && $language && $state && $zip_code){

        $param_contact = [
            "company_name" => $company_name,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "gender" => $gender,
            "number" => $number,
            "telephone_number" => $telephone_number,
            "street" => $street,
            "city" => $city,
            "country" => $country,
            "email" => $email,
            "locale" => 'id_ID',
            "state" => $state,
            "zip_code" => $zip_code,
        ];
        $resultContact = $aksara->request($url."/api/rest/v3/domain/contact/create","POST",$auth,$param_contact);
        $params['model']->serviceProperties->save(['contact_id' => $resultContact->data->no_contact]);
    }
    
    if($resultContact->data->no_contact){
        $contact_domain = $resultContact->data->no_contact;
    }else{
        $contact_domain = $params['model']->serviceProperties->get('contact_id');
    }
    
    
    if($csr && $validation_method){
        $encoded_json_string = json_encode($domain_validation_method);
        $decoded_json_string = html_entity_decode($encoded_json_string);
        $decoded_json_array = json_decode($decoded_json_string, true);
        $period = $params['model']['billingcycle'];
            
        if($period === "Annually"){
            $periodInt = 1;
        }else if($period === "Biennially"){
            $periodInt = 2;
        }else if($period === "Triennially"){
            $periodInt = 3;
        }

        
        // create Contact
        $param_contact = [
            "company_name" => $params['clientsdetails']['companyname'],
            "first_name" => $params['clientsdetails']['firstname'],
            "last_name" => !empty($params['clientsdetails']['lastname']) ? $params['clientsdetails']['lastname'] : "no_last",
            "gender" => 'M',
            "number" => 13,
            "telephone_number" => str_replace('.', '', $params['clientsdetails']["telephoneNumber"]),
            "street" => $params['clientsdetails']["address1"],
            "city" => $params['clientsdetails']["city"],
            "country" => $params['clientsdetails']["country"],
            "email" => $params['clientsdetails']["email"],
            "locale" => 'id_ID',
            "state" => $params['clientsdetails']["state"],
            "zip_code" => $params['clientsdetails']["postcode"],
        ];
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = $timestamp . ' - ' . "Param create contact" . json_encode($param_contact) . "\n";
        error_log($logMessage, 3, __DIR__ . "/module-errors.log");
        
        $resultContact = $aksara->request($url."/api/rest/v3/domain/contact/create","POST",$auth,$param_contact);
        
        $logMessage = $timestamp . ' - ' . "Result create contact" . json_encode($resultContact) . "\n";
        error_log($logMessage, 3, __DIR__ . "/module-errors.log");
        
        
        if ($resultContact && $resultContact->code == 200){
            $params['model']->serviceProperties->save(['contact_id' => $resultContact->data->no_contact]);
            $cleanedPhoneNumber = str_replace(['+', '.'], '', $params['clientsdetails']['phonenumberformatted']);
            // create SSL
            $param_ssl = [
                "product_id" => $params['configoption2'],
                "hostname" => [$hostname],
                "csr" => $csr,
                "contact" => $resultContact->data->no_contact,
                "approval_email" => $validation_method == 'dns' ? $params['clientsdetails']['email'] : $approval_email,
                "period" => $periodInt,
                "domain_validation_method" => [$decoded_json_array],
                "email_client" => $params['clientsdetails']['email'],
                "phonenumber_client" => $cleanedPhoneNumber,
            ];
            
            $logMessage = $timestamp . ' - ' . "Param create SSL" . json_encode($param_ssl) . "\n";
            error_log($logMessage, 3, __DIR__ . "/module-errors.log");
            
            $result = $aksara->request($url."/api/rest/v3/ssl/create","POST",$auth,$param_ssl);
            
            $logMessage = $timestamp . ' - ' . "Result create SSL" . json_encode($result) . "\n";
            error_log($logMessage, 3, __DIR__ . "/module-errors.log");
            
            $params['model']->serviceProperties->save(['ssl_irsfa_id' => $result->data->id, 'Registry Id' => $result->data->registry_id]);
            if ($result->message){
                $errormsg = $result->message;
            } 
        } else {
            $errormsg = $resultContact->message;
        }
        
    }
    
    if($csrReissue && $approval_email){
        $encoded_json_string = json_encode($domain_validation_method);
        $decoded_json_string = html_entity_decode($encoded_json_string);
        $decoded_json_array = json_decode($decoded_json_string, true);
        $param_reissue = [
            "id" => $params['model']->serviceProperties->get('ssl_irsfa_id'),
            "hostname" => [$hostname],
            "csr" => $csrReissue,
            "contact" => $contact_id,
            "approval_email" => $validation_method == 'dns' ? $params['clientsdetails']['email'] : $approval_email,
            "domain_validation_method" => [$decoded_json_array],
            "email_client" => $params['clientsdetails']['email'],
            "phonenumber_client" => $cleanedPhoneNumber,
        ];
        
        $resultReissue = $aksara->request($url."/api/rest/v3/ssl/reissue","POST",$auth,$param_reissue);
    }
    
    if($csrRenew && $approval_email){
        $encoded_json_string = json_encode($domain_validation_method);
        $decoded_json_string = html_entity_decode($encoded_json_string);
        $decoded_json_array = json_decode($decoded_json_string, true);
        $param_reissue = [
            "product_id" => $params['configoption2'],
            "id" => $params['model']->serviceProperties->get('ssl_irsfa_id'),
            "hostname" => [$hostname],
            "csr" => $csrReissue,
            "contact" => $contact_id,
            "approval_email" => $validation_method == 'dns' ? $params['clientsdetails']['email'] : $approval_email,
            "domain_validation_method" => [$decoded_json_array],
            "email_client" => $params['clientsdetails']['email'],
            "phonenumber_client" => $cleanedPhoneNumber,
        ];
        
        $resultReissue = $aksara->request($url."/api/rest/v3/ssl/reissue","POST",$auth,$param_reissue);
    }
    
    //data post
    if($result->data->id){
        $dataGetDetail = [ "id" => $result->data->id];
        $dataGetUrl = [ "registry_id" => $result->data->registry_id, "id" => $result->data->id];
        $disable = false;
    }else{
        $dataGetDetail = ["id" => $params['model']->serviceProperties->get('ssl_irsfa_id')];
        $dataGetUrl = ["registry_id" => $params['model']->serviceProperties->get('Registry Id'), "id" => $params['model']->serviceProperties->get('ssl_irsfa_id')];
        $disable = false;
    }
    $service = Capsule::table('tblhosting')->where('id', $params['serviceid'])->first();
    $product = Capsule::table('tblproducts')->where('id', $service->packageid)->first();
    $dataGetApproval = [ "product_id" => $product->configoption2, "domain" => $hostname];
    
    //url rest irsfa
    $urlRequest = $url . "/api/rest/v3/ssl/detail";
    $urlRequestLink = $url . "/api/rest/v3/ssl/generate/otp";
    $urlRequestApproval = $url . "/api/rest/v3/ssl/approver/detail";
    $urlRequestContact = $url . "/api/rest/v3/domain/contact/list";
    
    //response rest irsfa
    $request = $aksara->request($urlRequest,"GET", $auth , $dataGetDetail);
    $requestUrl = $aksara->request($urlRequestLink,"POST", $auth , $dataGetUrl);
    $requestApproval = $aksara->request($urlRequestApproval,"POST", $auth , $dataGetApproval);
    $requestContact = $aksara->request($urlRequestContact,"GET", $auth , NULL);
    $dataContactArray = json_decode(json_encode($requestContact->data->data), true);
    

    // Read the JSON file content into a string
    $jsonData = file_get_contents( __DIR__ . '/language.json');
    $dataArrayLanguage = json_decode($jsonData, true);  
    
    
    if($request->data->status_registry === NULL){
        
        if($params['customfields']['is_verified'] !== "" || boolval($caa)){
            $return_template = array(
                'tabOverviewReplacementTemplate' => 'templates/clientarea-not-created',
                'vars' => array( 
                    'status_irsfa' => $request->data->status_registry,
                    'hostname' => $params['domain'],
                    'approval_email' => $params['customfields']['Approval Email'],
                    'id' => $params['serviceid'],
                    'dataApproval' => $requestApproval->data,
                    'dataContact' => $dataContactArray,
                    'myContact' => $contact_domain,
                    'company' => $params['clientsdetails']['companyname'],
                    'email' => $params['clientsdetails']['email'],
                    'error_message' => $errormsg
                ),
            );
        }else{
            $return_template = array(
                'tabOverviewReplacementTemplate' => 'templates/clientarea-dns-verify',
                'vars' => array( 
                    'status_irsfa' => $request->data->status_registry,
                    'hostname' => $params['domain'],
                    'approval_email' => $params['customfields']['Approval Email'],
                    'id' => $params['serviceid'],
                    'dataApproval' => $requestApproval->data,
                    'dataContact' => $dataContactArray,
                    'myContact' => $contact_domain,
                    'company' => $params['clientsdetails']['companyname'],
                    'email' => $params['clientsdetails']['email'],
                    'error_message' => $errormsg
                ),
            );
        }
        
        return $return_template;
    }else{
        
        // convert Expire date to Jakarta Time
        $utcTime = new DateTime($requestUrl->data->expireAt, new DateTimeZone('UTC'));
        
        $jakartaTimezone = new DateTimeZone('Asia/Jakarta');
        $utcTime->setTimezone($jakartaTimezone);
    
        $jakartaTime = $utcTime->format('Y-m-d H:i:s');
        $certificate = $request->data->certificate ?? '';
        $ca_bundle = $request->data->ca_bundle ?? '';
        return array(
            'tabOverviewReplacementTemplate' => 'templates/clientarea-created',
            'vars' => array(
                'status_irsfa' => $request->data->status_registry,
                'uri' => $requestUrl->data->uri,
                'token' => $requestUrl->data->token,
                'expires' => $jakartaTime,
                'hostname' => $params['domain'],
                'approval_email' => $params['customfields']['Approval Email'],
                'id' => $params['serviceid'],
                'dataApproval' => $requestApproval->data,
                'dataContact' => $dataContactArray,
                'dataLanguage' => $dataArrayLanguage,
                'myContact' => $contact_domain,
                "email_client" => $params['clientsdetails']['email'],
                'certificate' => "modules/servers/aksaradatassl/apis/download_cert.php?certificate=$certificate",
                "ca_bundle" => "modules/servers/aksaradatassl/apis/download_ca.php?ca_bundle=$ca_bundle",
                "exp_date" => $request->data->exp_date ?? '',
            ),
        );
    }
}

/**
 * @return array
 */
function aksaradatassl_AdminCustomButtonArray()
{
    return [
        'Reissue' => 'Reissue',
        'Process SSL' => 'process_ssl',
    ];
}

function aksaradatassl_AdminServicesTabFields($params)
{
    if (isset($_GET['viewDetails'])) {
        $service = Capsule::table('tblhosting')->where('id', $params['serviceid'])->first();
        $product = Capsule::table('tblproducts')->where('id', $service->packageid)->first();
        $order = Capsule::table('openprovidersslnew_orders')->where('service_id', $service->id)->first();

        $html = '';

        if ($order->order_id) {
            $configuration = ConfigHelper::getServerConfigurationFromParams($product,
                EnvHelper::getServerEnvironmentFromParams($product));

            $apiCredentials = [
                'username' => ArrayHelper::getValue($configuration, 'username'),
                'password' => ArrayHelper::getValue($configuration, 'password'),
                'apiUrl' => ArrayHelper::getValue($configuration, 'opApiUrl'),
                'id' => $order->order_id,
            ];

            $reply = opApiWrapper::retrieveOrder($apiCredentials);

            $link1 = ArrayHelper::getValue($configuration,
                    'opRcpUrl') . '/ssl/order-details.php?ssl_order_id=' . $reply['id'];
            $link2 = ArrayHelper::getValue($configuration,
                    'sslPanelUrl') . '/#/orders/' . $reply['sslinhvaOrderId'] . '/details';

            $html = '<br /><a href=\'' . $link1 . '\' target=\'_blank\'>' . $link1 . '</a><br />';
            $html .= '<a href=\'' . $link2 . '\' target=\'_blank\'>' . $link2 . '</a><br /><br />';

            $html .= '<table style=\'border: solid 1px;\'>';

            $csrFieldMap = [
                'countryName' => 'Country',
                'stateOrProvinceName' => 'State',
                'localityName' => 'Locality',
                'organizationName' => 'Organization',
                'organizationalUnitName' => 'Organization Unit',
                'commonName' => 'Common Name',
                'emailAddress' => 'Email',
            ];

            foreach ($reply as $key => $value) {
                $html .= '<tr style=\'border: solid 1px;\'><td style=\'border: solid 1px;\'>' . $key . '</td><td>';

                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $html .= $k ? $k . ':' . $v . '<br />' : nl2br($v) . '<br />';
                    }
                } else {
                    $html .= nl2br($value);

                    if ($key === 'csr' && $value) {
                        $csrData = opApiWrapper::processRequest(
                            'decodeCsrSslCertRequest',
                            opApiWrapper::buildParams($apiCredentials),
                            ['csr' => $value]
                        );

                        $html .= '<br /><strong>Decoded CSR:</strong><br /><table>';

                        foreach ($csrData as $k => $v) {
                            $html .= '<tr><td><b>' . $csrFieldMap[$k] . '</b>:</td><td>' . $v . '</td></tr>';
                        }

                        $html .= '</table>';
                    }
                }

                $html .= '</td></tr>' . PHP_EOL;
            }

            $html .= '</table>';
        }
    } else {
        $html = '<input type="button" value="View Info" onclick="window.location=\'?userid=' . $params['clientdetails']['userid'] . '&id=' . $params['serviceid'] . '&viewDetails\'" />';
    }
    //Define Aksara
    $aksara = new Akasara;
    
    //url and account to rest irsfa
    $url = $params['configoption5'];
    $oauth2 = [
        "grant_type" => "client_credentials",
        "client_id" => '1c81352f-3c60-401a-9c5c-d874f2838755',
        "client_secret" => 'VQczLOykp59r1gb2LEk7o6XnH2N5p8OhnqJo4n9L',
        "scope" => "",
    ];
    
    //get token from rest irsfa
    $auth = $aksara->authentication($url, $oauth2); 
    
    //url and response from rest irsfa
    $data = [ "id" => (int) $params['model']->serviceProperties->get('ssl_irsfa_id')];
    $urlRequest = $url . "/api/rest/v3/ssl/detail";
    $request = $aksara->request($urlRequest,"GET", $auth , $data);
    $res = $request->data;
    //add status_ssl irsfa to admin area
    $statusIrsfa = '<p>'. $res->status_ssl .'</p>';
    
    //field in admin area
    $fieldsarray = array(
     'Certificate Info' => $html,
     'Status Irsfa' => $statusIrsfa,
    );


    return $fieldsarray;
}




