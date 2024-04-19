<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Coming Soon: Webhooks support

// class Wcapi extends App_Controller
// {
//     public function test()
//     {
//         $payload = trim(file_get_contents('php://input'));
//         $webhookContent = "";

//         $webhook = fopen('php://input', 'rb');
//         while (!feof($webhook)) {
//             $webhookContent .= fread($webhook, 4096);
//         }
//         fclose($webhook);
//         // print_r($webhookContent);
//         $headers = getallheaders();

//         print_r($payload);
//         // print_r($headers);

//         log_activity((string) $payload);
//         exit;
//     }
// }
