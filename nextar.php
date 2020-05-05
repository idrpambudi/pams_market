<?php

class NextarDisbursement {
    private const NEXTAR_DISBURSEMENT_URL = "https://nextar.flip.id/disburse";

    function get_header(){
        $auth_str = getenv("SECRET_KEY") . ":";
        return array(
            sprintf("Authorization: Basic %s", base64_encode($auth_str)),
            "Content-Type: application/x-www-form-urlencoded"
        );
    }

    function get_disbursement($disbursement_id){
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => sprintf("%s/%s", self::NEXTAR_DISBURSEMENT_URL, $disbursement_id),
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => $this->get_header(),
          CURLOPT_RETURNTRANSFER => true
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    function create_disbursement($bank_code, $account_number, $amount, $remark){
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => self::NEXTAR_DISBURSEMENT_URL,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_HTTPHEADER => $this->get_header(),
          CURLOPT_POSTFIELDS => sprintf("bank_code=%s&account_number=%d&amount=%d&remark=%s", $bank_code, $account_number, $amount, $remark),
          CURLOPT_RETURNTRANSFER => true
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}

$nextar_disbursement = new NextarDisbursement();
// echo $nextar_disbursement->get_disburse("6752634963")

?>