<?php

namespace macroseso\enzona;
use Exception;



class RequestTocken
{
    protected $apiKey = '';
    protected $apiSecret = '';
    protected $username='';
    protected $password='';


  
      public function __construct($username, $password, $key, $secret){
        $this->$apiKey = $key;
        $this->apiSecret = $secret;
        $this->username = $username;
        $this->password = $password;
      }

   public function requestToken()
    {
        try{
          $uri = $this->usedHost . $this->tokenRoute;
        $param = "grant_type=password&username=$this->username&password=$this->password&scope=enzona_business_payment enzona_business_qr";
        //$param = array("grant_type" => "password", "username" => "lrodriguez90", "password" => "Macroseso@0619+", "scope" => "enzona_business_payment enzona_business_qr");

        $ch = curl_init($uri);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic " . base64_encode($this->apiKey . ':' . $this->apiSecret)
        ));

        $result = curl_exec($ch);
        $error = curl_error($ch);
        $erno = curl_errno($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $res = json_decode($result, true);
            foreach ($res as $key => $value) {
                $value = $res['access_token'];
            }
            $response = $value;
            curl_close($ch);
            $this->accessToken=$response;
            return $response;
        } else {
            $response = false;
        }
        }catch(Exception $e){
          return   throw new \Exception($e->getMessage());
        }
    }
}
