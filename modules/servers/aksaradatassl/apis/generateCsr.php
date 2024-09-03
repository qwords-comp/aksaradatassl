<?php

require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
use WHMCS\Database\Capsule as DB;
    
function requestCSR($url, $method, $oauth2, $givenData = null) {
        $curl = curl_init();
    
        $headers = array(
            "Authorization: Bearer " . $oauth2,
            'Content-Type: application/json',
            'X-Requested-With: XMLHttpRequest',
        );
    
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        );
    
        if ($givenData) {
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($givenData);
        }
    
        curl_setopt_array($curl, $curlOptions);
    
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    
        if ($err) {
            return $err;
        } else {
            return json_decode($response);
        }
    }

//REST IRSFA
function authenticationCSR($url,$data){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url."/oauth/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($data)
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response);
    return $result->access_token;
}

if(isset($_GET['hostname']) && isset($_GET['organization']) && isset($_GET['unit']) && isset($_GET['email']) && isset($_GET['country']) && isset($_GET['state']) && isset($_GET['city']) && isset($_GET['bits']) && isset($_GET['serviceId'])){
    $service = DB::table('tblhosting')->where('id', $_GET['serviceId'])->first();
    $product = DB::table('tblproducts')->where('id', $service->packageid)->first();
    
    $urlCsr = $product->configoption5;
    $oauth2Csr = [
        "grant_type" => "client_credentials",
        "client_id" => $product->configoption3,
        "client_secret" => $product->configoption4,
        "scope" => "",
    ];
    
    $authCsr = authenticationCSR($urlCsr, $oauth2Csr);
    
    $param_csr = [
        "registry_id" => 2,
        "hostname" => $_GET['hostname'],
        "organization" => $_GET['organization'],
        "unit" => $_GET['unit'],
        "email" => $_GET['email'],
        "country" => $_GET['country'],
        "locality" => $_GET['country'],
        "state" => $_GET['state'],
        "city" => $_GET['city'],
        "bits" => $_GET['bits'],
    ];
    
    $resultCsr = requestCSR($urlCsr."/api/rest/v3/ssl/csr/generate","POST",$authCsr,$param_csr);
    $dataResultCsr = json_encode($resultCsr->data);
    echo $dataResultCsr;
}else{
    $errorResponse = array(
        "code" => "error",
        "message" => "hostname, organization, unit, email, country, state, city, bits and serviceId is required!"
    );
    http_response_code(400); 
    echo json_encode($errorResponse);
    exit(); 
}
die();