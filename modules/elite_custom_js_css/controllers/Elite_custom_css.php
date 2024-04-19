<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once ("EliteCustomJsCss.php");

class Elite_custom_css extends EliteCustomJsCss {

    public function __construct() {
        parent::__construct();
        
        if (!is_admin() || !defined('ElITE_CUSTOM_JS_CSS_MODULE_NAME')) {
            access_denied('elite_custom_js_css');
        }        
        $this->title = _l('elite_custom_css');
    }
}
