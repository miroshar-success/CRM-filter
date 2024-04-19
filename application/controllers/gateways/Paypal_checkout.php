<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PayPalCheckoutSdk\Orders\OrdersGetRequest;

/**
 * @property-read Paypal_checkout_gateway $paypal_checkout_gateway
 */
class Paypal_checkout extends App_Controller
{
    public function complete($id, $hash, $attempt_reference = null)
    {
        check_invoice_restrictions($id, $hash);

        $client = $this->paypal_checkout_gateway->client();

        $orderId = $this->input->post('orderID');

        try {
            $response      = $client->execute(new OrdersGetRequest($orderId));
            $transactionid = $response->result->purchase_units[0]->payments->captures[0]->id;
            if ($response->result->status == 'COMPLETED') {
                if (total_rows(db_prefix() . 'invoicepaymentrecords', [
                    'transactionid' => $transactionid,
                    'paymentmode' => $this->paypal_checkout_gateway->getId(),
                ]) === 0) {
                    $success = $this->paypal_checkout_gateway->addPayment(
                        [
                            'amount'        => $response->result->purchase_units[0]->amount->value,
                            'invoiceid'     => $id,
                            'transactionid' => $response->result->purchase_units[0]->payments->captures[0]->id,
                            'payment_attempt_reference' => $attempt_reference,
                        ]
                    );

                    set_alert('success', _l($success ? 'online_payment_recorded_success' : 'online_payment_recorded_success_fail_database'));
                } else {
                    set_alert('warning', 'This transaction/order is already stored in database.');
                }
            }
        } catch (Exception $e) {
            $messageJSON   = $e->getMessage();
            $messageJSON   = json_decode($messageJSON);
            $error_message = false;
            if (isset($messageJSON->error_description)) {
                $error_message = '[' . $messageJSON->error . '] ' . $messageJSON->error_description;
                if ($messageJSON->error == 'invalid_client') {
                    $error_message .= ' - Make sure that you are not using production credentials and have test mode enabled.';
                }
            } elseif (isset($messageJSON->details[0]->description)) {
                $error_message = $messageJSON->details[0]->description;
            }
            if ($error_message) {
                set_alert('warning', $error_message);
            }
        }
    }

    public function payment($id, $hash)
    {
        check_invoice_restrictions($id, $hash);

        $this->load->model('invoices_model');

        $invoice = $this->invoices_model->get($id);

        $language        = load_client_language($invoice->clientid);
        $data['invoice'] = $invoice;

        $data['total']             = $this->input->get('total');
        $data['attempt_reference'] = $this->input->get('attempt_reference');
        $data['paypal_client_id']  = $this->paypal_checkout_gateway->getSetting('client_id');
        $data['button_style']      = json_encode($this->paypal_checkout_gateway->get_styling_button_params());
        $data['order']             = $this->paypal_checkout_gateway->get_order_create_data($invoice, $data['total']);
        $data['attempt_fee']   = $this->session->userdata('attempt_fee') ?? 0;
        $data['attempt_amount']   = $this->session->userdata('attempt_amount') ?? 0;

        $this->get_view($data);
    }

    private function get_view($data = [])
    {
        ?>
<?php echo payment_gateway_head(_l('payment_for_invoice') . ' ' . format_invoice_number($data['invoice']->id)); ?>

<body class="gateway-paypal-checkout">
    <div class="container">
        <div class="col-md-8 col-md-offset-2 mtop30">
            <div class="mbot30 text-center">
                <?php echo payment_gateway_logo(); ?>
            </div>
            <div class="row">
                <div class="panel_s">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?php echo _l('payment_for_invoice'); ?> -
                            <?php echo _l('payment_total', app_format_money($data['total'], $data['invoice']->currency_name)); ?>
                        </div>
                        <a
                            href="<?php echo site_url('invoice/' . $data['invoice']->id . '/' . $data['invoice']->hash); ?>">
                            <?php echo format_invoice_number($data['invoice']->id); ?>
                        </a>
                    </div>
                    <div class="panel-body">
                        <div>
                            </h3>
                            <?php if ($this->paypal_checkout_gateway->processingFees) { ?>
                                <h4><?php echo _l('payment_attempt_amount') . ": " . app_format_money($data['attempt_amount'], $data['invoice']->currency_name); ?></h4>
                                <h4><?php echo _l('payment_attempt_fee') . ": " . app_format_money($data['attempt_fee'], $data['invoice']->currency_name); ?></h4>
                            <?php } ?>
                            <h4><?php echo _l('payment_total', app_format_money($data['total'], $data['invoice']->currency_name)); ?></h4>
                            <hr />
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo payment_gateway_scripts(); ?>
        <script
            src="https://www.paypal.com/sdk/js?client-id=<?php echo $data['paypal_client_id']; ?>&currency=<?php echo $data['invoice']->currency_name; ?>">
        </script>
        <script>
        paypal.Buttons({
            style: <?php echo $data['button_style']; ?>,
            createOrder: function(data, actions) {
                return actions.order.create(<?php echo json_encode($data['order']); ?>);
            },
            onApprove: function(data, actions) {
                var completeURL =
                    '<?php echo site_url('gateways/paypal_checkout/complete/' . $data['invoice']->id . '/' . $data['invoice']->hash . '/' . $data['attempt_reference']); ?>';
                // Capture the funds from the transaction
                return actions.order.capture().then(function(details) {
                    $.post(completeURL, {
                        orderID: data.orderID,
                    }).done(function(response) {
                        window.location.href =
                            '<?php echo site_url('invoice/' . $data['invoice']->id . '/' . $data['invoice']->hash); ?>';
                    });
                });
            }
        }).render('#paypal-button-container');
        </script>
        <?php echo payment_gateway_footer();
    }
}
