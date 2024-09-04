<?php
    /*
       Created By Aep.
       Cron running setiap 10 menit.
       Cron untuk mengirim sertifikat SSL ke client saat sertifikatnya udah ready.
    */

    use WHMCS\Database\Capsule;
    require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
    header('Content-Type: application/json');
    
    
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
        
    }
    
    $fieldname = 'ssl_irsfa_id'; // [ENV] change to yours
    
    $field = Capsule::table('tblcustomfields')->where('fieldname',$fieldname)->first();
    if ($field){
        $data = Capsule::table('tblcustomfieldsvalues')
            ->select(['tblcustomfieldsvalues.*', 'tblhosting.domainstatus','tblhosting.domain','tblhosting.userid', 'tblhosting.id AS id_hosting'])
            ->join('tblhosting', 'tblcustomfieldsvalues.relid', '=', 'tblhosting.id')
            ->where('tblcustomfieldsvalues.fieldid',$field->id)
            ->where('tblhosting.domainstatus','Active') // filter only active service
            ->where(function ($query) {
                $query->whereNotNull('tblcustomfieldsvalues.value')
                      ->orWhere('tblcustomfieldsvalues.value', '<>', '');
            })
            ->whereNotExists(function ($query) {
                $query->select(Capsule::raw(1))
                    ->from('send_email_ssl')
                    ->whereRaw('send_email_ssl.hostingid = tblhosting.id');
            })
            ->get();
        
        if (empty($data->toArray())){
            echo json_encode(["status"=>false, "data"=>[], "error" => "Data kosong"]);
            die();
        }
        
        // get detail to irsfa
        $aksara = new Akasara;
        
        $url = 'https://api6.irsfa.id';
        $oauth2 = [
            "grant_type" => "client_credentials",
            "client_id" => '1c81352f-3c60-401a-9c5c-d874f2838755',
            "client_secret" => 'VQczLOykp59r1gb2LEk7o6XnH2N5p8OhnqJo4n9L',
            "scope" => "",
        ];
        
        $auth = $aksara->authentication($url, $oauth2); 

        $result = [];
        foreach ($data as $obj) {
            
            
            if ((int)$obj->value == 0){
                continue;
            }
            
            $param = ["id" => (int) $obj->value];
            $urlRequest = $url . "/api/rest/v3/ssl/detail";
            $request = $aksara->request($urlRequest,"GET", $auth , $param);
            
            $logMessage = date('Y-m-d H:i:s') . ' - ' . json_encode($request) . "\n";
            error_log($logMessage, 3, "/home/poqwcom/public_html/modules/servers/aksaradatassl/log/error.log");
            
            $result[] = [$param,$request];
            
            if ($request->code == 200){
                if ($request->data->status_registry == "ACT"){ 
                    
                    $isSendedNotif = Capsule::table('send_email_ssl')
                        ->where('hostingid', $obj->id_hosting)
                        ->first();
                    
                    if (!$isSendedNotif){
                        
                        // notif email
                        $command = "sendemail";
                        $values["customtype"] = "general";
                        $values["customsubject"] = "Detail SSL Certificate";
                        $values["custommessage"] = "Berikut detail sertifikatnya : <br><br><code>" . $request->data->certificate . "</code>";
                        $values["id"]            = $obj->userid;
                        $results = localAPI($command, $values);
                        
                        // buatkan flag bahwa sudah kirim email (agar tidak terus2an kirim email)
                        $insertedrow = Capsule::table('send_email_ssl')->insert(['hostingid' => $obj->id_hosting]);
                        
                    } else {
                        $logMessage = date('Y-m-d H:i:s') . ' - Email already sent - ' . "\n";
                        error_log($logMessage, 3, "/home/poqwcom/public_html/modules/servers/aksaradatassl/log/error.log");    
                    }
            
                    
                } else {
                    $logMessage = date('Y-m-d H:i:s') . ' - Status not ACT - ' . "\n";
                    error_log($logMessage, 3, "/home/poqwcom/public_html/modules/servers/aksaradatassl/log/error.log");
                }
            } else {
                $logMessage = date('Y-m-d H:i:s') . ' - code not 200 -' . "\n";
                error_log($logMessage, 3, "/home/poqwcom/public_html/modules/servers/aksaradatassl/log/error.log");
            }
        }
        
        echo json_encode(["status" => true, "data"=> $result]);
    } else {
        echo json_encode(["status"=>false, "data"=>[], "error" => "fieldname tidak di temukan"]);
        die();
    }