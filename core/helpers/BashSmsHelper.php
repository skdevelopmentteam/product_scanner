<?php

/* 
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */
class BashSmsHelper{
    /* Bash SMS Credentials */
    private $user;
    private $password;
    private $sender_id;
    private $priority;
    private $sms_type;
    function __construct() {
        $this->user         =   'shubhendu';
        $this->password     =   'lotus1234';
        $this->sender_id    =   'SKWRLD';
        $this->priority     =   'ndnd';
        $this->sms_type     =   'normal';
    }
    /* @Description - Sends SMS through BASH SMS API
     * @Parameters  - mobilenumber, message
     * @Return      - True is SMS Sent else False
     */
    public function sendSms($mobileNumber, $message) {
        if(!(strlen($mobileNumber) == 10)){
            return false;
        }
        $message    = rawurlencode($message);
        //Create the URL link where it needs to request
        $redirect_link	=	"http://bhashsms.com/api/sendmsg.php?user=".$this->user."&pass=".$this->password."&sender=".$this->sender_id."&phone=$mobileNumber&text=".$message."&priority=".$this->priority."&stype=".$this->sms_type;
        
        //Initialize the CURL
        $curl	=	curl_init($redirect_link);
        
        $header[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"; 
        $header[] = "Cache-Control: max-age=0"; 
        $header[] = "Connection: keep-alive"; 
        $header[] = "Keep-Alive:timeout=5, max=100"; 
        $header[] = "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3"; 
        $header[] = "Accept-Language:es-ES,es;q=0.8"; 
        $header[] = "Pragma: "; 
  
        //Set the CURL options by default I am sending with GET Request
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11'); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); 
  
        //Execute the CURL
        $result             =   curl_exec($curl);
        $curl_information   =   curl_getinfo($curl);
        return ($curl_information['http_code'] == 200) ? true : false;
    }
}

