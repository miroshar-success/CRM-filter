<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="text-danger" id="settings_info"></div>
    <h4 class="no-margin"><?php echo html_escape(_l('Woocommerce_settings')) ?> </h4>
    <hr class="hr-panel-heading" />

    <?php $attrs = (get_option('woocommerce_client') != '' ? array() : array('autofocus' => true)); ?>
    <?php echo render_input('settings[woocommerce_client]', _l('woocommerce_client'), get_option('woocommerce_client'), 'url', $attrs); ?>
    <hr />

    <?php $attrs = (get_option('woocommerce_consumer_key') != '' ? array() : array('autofocus' => true)); ?>
    <?php echo render_input('settings[woocommerce_consumer_key]', _l('woocommerce_consumer_key'), get_option('woocommerce_consumer_key'), 'text', $attrs); ?>
    <hr />

    <?php $attrs = (get_option('woocommerce_consumer_secret') != '' ? array() : array('autofocus' => true)); ?>
    <?php echo render_input('settings[woocommerce_consumer_secret]', _l('woocommerce_consumer_secret'), get_option('woocommerce_consumer_secret'), 'text', $attrs); ?>
    <hr />

    <?php

    $selected = '';
    $s_attrs = array('data-none-selected-text' => _l('system_default_string'));
    $currencies = woocommerce_system_currency();
    $wc_currency = get_option('woocommerce_currency');
    foreach ($currencies as $currency) {
      if ($currency['id'] == $wc_currency) {
        $selected = $currency['id'];
      }
    }
    ?>
    <div class="row">
      <div class="col-md-4 col-sm-12">
        <button type="button" name="woo_test_button" id="woo_test_button" class="btn btn-success btn-block" data-loading-text="<i class='fa fa-spinner fa-spin '></i>Checking connection"><?php echo _l('test_connection') ?></button>
      </div>
      <div class="col-md-4 col-sm-12">
        <a href="<?php echo admin_url("woocommerce/manual_check") ?>" data-toggle="tooltip" data-placement="top" title="<?= _l("delay") ?>" class="btn btn-info btn-block" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo _l("checking_store") ?>"><?php echo _l('check_updates') ?></a>
      </div>
      <div class="col-md-4 col-sm-12">
        <a href="<?php echo admin_url("woocommerce/reset") ?>" class="btn btn-danger btn-block" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo _l('reseting') ?>"><?php echo _l('reset_module') ?></a>
      </div>
    </div>
    <hr>
    <div id="woo_test_result"></div>

  </div>