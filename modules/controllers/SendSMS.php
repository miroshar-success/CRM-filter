<?php
defined('BASEPATH') or exit('No direct script access allowed');
$check =  __dir__ ;

$str= preg_replace('/\W\w+\s*(\W*)$/', '$1', $check);
$str.'/twilio-web/src/Twilio/autoload.php';
use Twilio\Rest\Client;

class SendSMS extends AdminController
{
    private $conf;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('call_logs_model');
        $this->conf = twilio_setting();
        //print_r(twilio_setting()); exit;
    }

    public function send()
    {
        //print_r($this->input->post()); exit;
        $post = $this->cleanPost($this->input->post());

        if (!empty($post['error'])) {
            echo json_encode([
                'success' => false,
                'errors' => $post['error']
            ]);
            exit;
        }

        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = $this->conf["account_sid"];
        $auth_token = $this->conf["auth_token"];

        // A Twilio number you own with SMS capabilities
        $twilio_number = $this->conf["twilio_number"];

        $client = new Client($account_sid, $auth_token);

        $sms_ids = [];
        $errors = [];
        foreach ($post['phone_numbers'] as $phone_number) {
            try {
                $message = $client->messages->create(
                    $phone_number,
                    array(
                        'from' => $twilio_number,
                        'body' => $post['message']
                    )
                );
                $sms_ids[] = $message->sid;
            } catch (\Throwable $e) {
                $errors[] = [
                    'phone_number' => $phone_number,
                    'message' => $e->getMessage()
                ];
            }
        }

        echo json_encode([
            'success' => true,
            'sent' => $sms_ids,
            'errors'  => $errors
        ]);

        exit;
    }

    public function cleanPost($data)
    {
        $errors = [];

        $data['message'] = strip_tags($data['message']);
        if (empty($data['message'])) {
            $errors['message'] = 'Message is required';
        }

        $phone_numbers = [];
        foreach ($data['phone_number'] as $each) {
            str_replace(' ', '', $each);
            str_replace('+', '', $each);
            if (empty($each)) continue;
            $phone_numbers[] = '+'.$each;
        }

        if (empty($phone_numbers)) {
            $errors['phone_number'] = 'Phone Number cannot be empty';
        }

        return [
            'errors' => $errors,
            'phone_numbers' => $phone_numbers,
            'message' => $data['message']
        ];
    }
}
