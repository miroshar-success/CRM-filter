<?php
defined('BASEPATH') or exit('No direct script access allowed');

$check =  __dir__ ;
$str= preg_replace('/\W\w+\s*(\W*)$/', '$1', $check);
$str.'/twilio-web/src/Twilio/autoload.php';
/*use Twilio\TwiML\TwiML;*/
use Twilio\TwiML\VoiceResponse;

class Twilio_request extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('call_logs_model');
    }

    public function new_call()
    {
        $response = new VoiceResponse();
        $callerIdNumber = "";

        if((get_option('sms_twilio_active') == 1) || (get_option('sms_twilio_active') == '1')) {
            $callerIdNumber = get_option('sms_twilio_phone_number');
        }    


        if((get_option('staff_members_twilio_account_share_staff') == 1) || (get_option('staff_members_twilio_account_share_staff') == '1')) {

            $result = $this->call_logs_model->get_twilio_account(get_option('loggin_user_temp_id'));

            if( isset($result) ){
                $active = "".$result->active;
                str_replace(' ', '', $active); 
                if($active == '1'){ 
                    $callerIdNumber =  $result->twilio_phone_number;
                    str_replace(' ', '', $callerIdNumber);

                } else {
                    $callerIdNumber = "";
                }
            }
            
        }  
        
        if(!empty($callerIdNumber)){

            str_replace(' ', '', $callerIdNumber);
           // str_replace('+', '', $callerIdNumber);

            $dial = $response->dial(null, ['callerId'=>$callerIdNumber]);
            $phoneNumberToDial = isset($_GET['phoneNumber']) ? $_GET['phoneNumber'] : null;

            

            if (isset($phoneNumberToDial)) {

                str_replace(' ', '', $phoneNumberToDial);
                //str_replace('+', '', $phoneNumberToDial);

                $dial->number($phoneNumberToDial);
            } else {
                $dial->client('support_agent');
            }
        }

        //$response->record(['action' => '/','method' => 'GET', 'maxLength' => 20, 'finishOnKey' => '*', 'transcribe' => 'true', 'transcribeCallback' => '/']);

        //$response->record([ 'transcribe' => 'true']);
        //$response->record(['transcribe' => 'true', 'transcribeCallback' => '/handle_transcribe.php']);

        // $recordingURL = htmlentities($_POST['RecordingUrl']);   The URL to the recording is: {$recordingURL}.mp3
        header('Content-Type: text/xml');
        echo $response;
    }
}