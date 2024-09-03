<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use libphonenumber\PhoneNumberUtil;

/**
 * @param $params
 *
 * @return string
 * @throws Exception
 * @throws opApiException
 */
 
class Akasara{

    function message($code, $msg){
        return [
            "code"    => $code,
            "message" => $msg
        ];
    }

    function messageWithData($code, $msg, $data){
        return [
            "code"    => $code,
            "message" => $msg,
            "data"    => $data
        ];
    }
    
    function request($url, $method, $oauth2, $givenData = null) {
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
    function authentication($url,$data){
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
    
    function create($params){
    
        try {
            
            $url = $params['configoption5'];
            $oauth2 = [
                "grant_type" => "client_credentials",
                "client_id" => $params['configoption3'],
                "client_secret" => $params['configoption4'],
                "scope" => "",
            ];
            
            $encoded_json_string = $params['customfields']['Domain Validation Method'];
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
            
            $cleanedPhoneNumber = str_replace(['+', '.'], '', $params['clientsdetails']['phonenumberformatted']);

            $param_ssl = [
                "product_id" => $params['configoption2'],
                "hostname" => [$params['domain']],
                "csr" => $params['customfields']['CSR'],
                "contact" => $params['model']->serviceProperties->get('contact_id'),
                "approval_email" => $params['customfields']['Approval Email'],
                "period" => $periodInt,
                "domain_validation_method" => [$decoded_json_array],
                "email_client" => $params['clientsdetails']['email'],
                "phonenumber_client" => $cleanedPhoneNumber,
            ];
            $auth = $this->authentication($url,$oauth2);
            $request = $this->request($url."/api/rest/v3/ssl/create","POST",$auth,$param_ssl);
            if($request->code !== 200){
                return ["error" => $request->message];
            }else{
                $params['model']->serviceProperties->save(['ssl_irsfa_id' => $request->data->id, 'Registry Id' => $request->data->registry_id]);
                return ["success" => $request->message, "id" => $request->data->id, 'registry_id' => $request->data->registry_id];
            }
            
        } catch (Exception $e) {
            $fullMessage = $e->getFullMessage();
    
            logModuleCall(
                'akasarassl',
                'create',
                $params,
                $fullMessage,
                $fullMessage . ', ' . $e->getTraceAsString()
            );
    
            return $fullMessage;
        } catch (\Exception $e) {
            $message = "Error occurred during order saving: {$e->getMessage()}";
    
            logModuleCall(
                'akasarassl',
                'create',
                $params,
                $message,
                $message . ', ' . $e->getTraceAsString()
            );
    
            return $message;
        }
    
        return 'success';
    }
    
    
    function renew($params){
    
        try {
            
            $url = $params['configoption5'];
            $oauth2 = [
                "grant_type" => "client_credentials",
                "client_id" => $params['configoption3'],
                "client_secret" => $params['configoption4'],
                "scope" => "",
            ];
            
            $params_ssl = [
                "id" => $params['model']->serviceProperties->get('ssl_irsfa_id'),
            ];
            
            $auth = $this->authentication($url,$oauth2);
            $request = $this->request($url."/api/rest/v3/ssl/renew","POST",$auth,$params_ssl);
            if($request->code !== 200){
                return ["error" => $request->message];
            }else{
                return ["success" => $request->message];
            }
            
        } catch (Exception $e) {
            $fullMessage = $e->getFullMessage();
    
            logModuleCall(
                'akasarassl',
                'renew',
                $params,
                $fullMessage,
                $fullMessage . ', ' . $e->getTraceAsString()
            );
    
            return $fullMessage;
        } catch (\Exception $e) {
            $message = "Error occurred during order saving: {$e->getMessage()}";
    
            logModuleCall(
                'akasarassl',
                'renew',
                $params,
                $message,
                $message . ', ' . $e->getTraceAsString()
            );
    
            return $message;
        }
    
        return 'success';
    }
    
    
    function cancel($params){
    
        try {
            
            $url = $params['configoption5'];
            $oauth2 = [
                "grant_type" => "client_credentials",
                "client_id" => $params['configoption3'],
                "client_secret" => $params['configoption4'],
                "scope" => "",
            ];
            
            $params_ssl = [
                "id" => $params['model']->serviceProperties->get('ssl_irsfa_id'),
            ];
            
            $auth = $this->authentication($url,$oauth2);
            $request = $this->request($url."/api/rest/v3/ssl/cancel","POST",$auth,$params_ssl);
            
            if($request->code !== 200){
                return ["error" => $request->message];
            }else{
                return ["success" => $request->message];
            }
            
        } catch (Exception $e) {
            $fullMessage = $e->getFullMessage();
    
            logModuleCall(
                'akasarassl',
                'cancel',
                $params,
                $fullMessage,
                $fullMessage . ', ' . $e->getTraceAsString()
            );
    
            return $fullMessage;
        } catch (\Exception $e) {
            $message = "Error occurred during order saving: {$e->getMessage()}";
    
            logModuleCall(
                'akasarassl',
                'cancel',
                $params,
                $message,
                $message . ', ' . $e->getTraceAsString()
            );
    
            return $message;
        }
    
        return 'success';
    }
    
    
    function reissue($params){
    
        try {
            
            $url = $params['configoption5'];
            $oauth2 = [
                "grant_type" => "client_credentials",
                "client_id" => $params['configoption3'],
                "client_secret" => $params['configoption4'],
                "scope" => "",
            ];
            
            $encoded_json_string = $params['customfields']['Domain Validation Method'];
            $decoded_json_string = html_entity_decode($encoded_json_string);
            $decoded_json_array = json_decode($decoded_json_string, true);
            
            $param_ssl = [
                "id" => $params['model']->serviceProperties->get('ssl_irsfa_id'),
                "hostname" => [$params['customfields']['Domain']],
                "csr" => $params['customfields']['CSR'],
                "contact" => $params['model']->serviceProperties->get('contact_id'),
                "approval_email" => $params['customfields']['Approval Email'],
                "domain_validation_method" => [$decoded_json_array]
            ];
            $auth = $this->authentication($url,$oauth2);
            $request = $this->request($url."/api/rest/v3/ssl/reissue","POST",$auth,$params_ssl);
            
            if($request->code !== 200){
                return ["error" => $request->message];
            }else{
                return ["success" => $request->message, "id" => $request->data->id, 'registry_id' => $request->data->registry_id];
            }
            
        } catch (Exception $e) {
            $fullMessage = $e->getFullMessage();
    
            logModuleCall(
                'akasarassl',
                'reissue',
                $params,
                $fullMessage,
                $fullMessage . ', ' . $e->getTraceAsString()
            );
    
            return $fullMessage;
        } catch (\Exception $e) {
            $message = "Error occurred during order saving: {$e->getMessage()}";
    
            logModuleCall(
                'akasarassl',
                'reissue',
                $params,
                $message,
                $message . ', ' . $e->getTraceAsString()
            );
    
            return $message;
        }
    
        return 'success';
    }
    
}