<?php

defined('BASEPATH') or exit('No direct script access allowed');

function is_custom_js(){
    $CI = &get_instance();
    
    if($CI->uri->segment(3) == 'elite_custom_js'){
        return true;
    }
    return false;
}