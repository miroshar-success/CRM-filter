<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <?php if (is_object($order)) { ?>
                     <h4><?php echo _l('woocommerce_order_detail') . ' - ' . html_escape($order->id); ?> </h4>
                     <hr />
                     <div class="row mbot15">
                        <div class="col-md-2 col-xs-6 border-right">
                           <h3 class="bold"><?php echo html_escape($order->number) ?></h3>
                           <span class="text-info"><?php echo html_escape(_l('order_number')) ?></span>
                        </div>
                        <div class="col-md-2 col-xs-6 border-right">
                           <h3 class="bold"><?php echo html_escape(count($order->line_items)) ?></h3>
                           <span class=""> <?php echo html_escape(_l('products_bought')) ?></span>
                        </div>
                        <div class="col-md-2 col-xs-6 border-right">
                           <h3 class="bold"><?php echo html_escape($order->status) ?></h3>
                           <span class="text-warning"> <?php echo html_escape(_l('status')) ?> </span>
                        </div>
                        <div class="col-md-3 col-xs-6 border-right">
                           <h3 class="bold"><?php echo html_escape($order->currency . ' ' . $order->total) ?></h3>
                           <span class="text-success"> <?php echo html_escape(_l('total_amount')) ?></span>
                        </div>
                        <div class="col-md-3 col-xs-6 text-center">
                           <h3 class="bold"><?php echo _l('woocommerce_payment_method') ?></h3>
                           <div>
                              <span class="text-dark"> <?php echo $order->payment_method ?></span> -
                              <span class="text-info"> <?php echo $order->payment_method_title ?></span>
                           </div>
                        </div>
                     </div>
                     <hr />
                     <!-- customer details -->
                     <div class="row mtop20">
                        <div class="col-md-4 transaction-html-info-col-left border-right">
                           <h4><?php echo _l('customer_details'); ?>:</h4>
                           <?php echo  _l('customer_id') . ': '; ?>
                           <?php echo ($order->customer_id != 0) ? html_escape($order->customer_id) : "Guest" ?>
                           <br />
                           <?php echo html_escape($order->billing->first_name . ' ' . $order->billing->last_name); ?> <br />
                           <?php echo ($order->billing->company !== '') ? html_escape($order->billing->company) : '' ?><br />
                           <?php echo html_escape($order->billing->phone); ?><br />
                           <?php echo html_escape($order->billing->email); ?><br />
                        </div>
                        <!-- blling details -->
                        <div class="col-md-4 transaction-html-info-col-left border-right">
                           <h4><?php echo _l('billing_details'); ?>:</h4>
                           <address class="invoice-html-customer-billing-info">
                              <?php echo  $order->billing->address_1;
                              echo $order->billing->address_2 . ",<br />";
                              echo $order->billing->city . "<br />";
                              echo $order->billing->state . ",<br />";
                              echo $order->billing->country . "," . $order->billing->postcode;
                              ?><br />
                           </address>
                        </div>
                        <!-- shipping details -->
                        <div class="col-md-4 transaction-html-info-col-left">
                           <h4><?php echo _l('shipping_details'); ?>:</h4>
                           <address class="invoice-html-customer-billing-info">
                              <?php echo  $order->shipping->address_1 . ",";
                              echo $order->shipping->address_2 . "<br />";
                              echo $order->shipping->city . ",<br />";
                              echo $order->shipping->state . ",<br />";
                              echo $order->shipping->country . "," . $order->shipping->postcode;
                              ?><br />
                           </address>
                        </div>
                     </div>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                        <h5 class="text-muted">
                           <?php echo _l('woocommerce_line_products'); ?>
                        </h5>
                           <table class="table items items-preview invoice-items-preview">
                              <thead>
                                 <tr>
                                    <th><?php echo html_escape(_l('id')) ?> #</th>
                                    <th><?php echo html_escape(_l('name')) ?></th>
                                    <th><?php echo html_escape(_l('quantity')) ?></th>
                                    <th><?php echo html_escape(_l('price')) ?></th>
                                    <th><?php echo html_escape(_l('total')) ?></th>

                                 </tr>
                              </thead>
                              <tbody>

                                 <?php foreach ($order->line_items as $item) {
                                    echo '<tr><td>' . $item->id   . '</td>';
                                    echo '<td>' . $item->name     . '</td>';
                                    echo '<td>' . $item->quantity . '</td>';
                                    echo '<td>' . $item->price    . '</td>';
                                    echo '<td>' . $item->total     . '</td></tr>';
                                 } ?>
                              </tbody>
                           </table>
                        </div>
                        <div class="col-md-5 col-md-offset-7">
                           <table class="table text-right">
                              <tbody>
                                 <tr id="sub_total">
                                    <td><span class="bold"><?php echo _l('woocommerce_subtotal'); ?></span>
                                    </td>
                                    <td>
                                       <?php echo html_escape($order->currency . ' ' . number_format($order->total - $order->total_tax - $order->shipping_total, 2)) ?>
                                    </td>
                                 </tr>
                                 <tr id="shipping_tax">
                                    <td><span class="bold"><?php echo _l('woocommerce_total_shipping'); ?></span>
                                    </td>
                                    <td>
                                       <?php echo html_escape($order->currency . ' ' . $order->shipping_total) ?>
                                    </td>
                                 </tr>
                                 <tr id="total_tax">
                                    <td><span class="bold"><?php echo _l('woocommerce_total_tax'); ?></span>
                                    </td>
                                    <td>
                                       <?php echo html_escape($order->currency . ' ' . $order->total_tax) ?>
                                    </td>
                                 </tr>
                                 <?php if (floatval($order->discount_total) > 0) { ?>
                                    <tr>
                                       <td>
                                          <span class="bold"><?php echo _l('woocommerce_total_discount'); ?>
                                       <td>
                                          <?php echo '-' . html_escape($order->currency . ' ' . $order->discount_total) ?>
                                       </td>
                                    </tr>
                                 <?php } ?>
                                 <tr>
                                    <td><span class="bold"><?php echo _l('total_amount'); ?></span>
                                    </td>
                                    <td>
                                       <?php echo html_escape($order->currency . ' ' . $order->total); ?>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <a type="button" class="btn btn-default" href="<?php echo admin_url('woocommerce/orders') ?>"><?php echo html_escape(_l('back')) ?></a>
                     <button class='btn btn-info' data-target='#updateModal' data-id=".html_escape($details['id'])." data-toggle='modal'><?php echo html_escape(_l('update_status')) ?></button>
                     <button class='invoiceOrder btn btn-success' data-target='#invoiceModal' data-id=".html_escape($details['id'])." data-toggle='modal'><?php echo html_escape(_l('invoice_order')) ?></button>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>
</div>
<div class="hide" id="cDiv"><?= $order->customer_id ?></div>
<div class="hide" id="oDiv"><?= $order->id ?></div>
<!-- Modal content for create invoice-->
<div class="modal fadeIn" id="updateModal" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <span><?php echo html_escape(_l('update_status')) ?> </span>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <?php echo form_open(admin_url('woocommerce/update_woo')); ?>
            <p>Order ID </p>
            <div class="form-group">
               <input type="text" class="form-control" name="orderId" id="orderId" value="<?= $order->id ?>" readonly>
               <label for="sel1">STATUS (select one):</label>
               <select class="form-control" id="status" name="status" required>
                  <option value="pending" <?php if ($order->status == 'pending') {
                                             echo "selected";
                                          } ?>><?php echo html_escape(_l('pending')) ?></option>
                  <option value="processing" <?php if ($order->status == 'processing') {
                                                echo "selected";
                                             } ?>><?php echo html_escape(_l('processing')) ?></option>
                  <option value="on-hold" <?php if ($order->status == 'on-hold') {
                                             echo "selected";
                                          } ?>><?php echo html_escape(_l('on_hold')) ?></option>
                  <option value="completed" <?php if ($order->status == 'completed') {
                                                echo "selected";
                                             } ?>><?php echo html_escape(_l('completed')) ?></option>
                  <option value="cancelled" <?php if ($order->status == 'cancelled') {
                                                echo "selected";
                                             } ?>><?php echo html_escape(_l('cancelled')) ?></option>
                  <option value="refunded" <?php if ($order->status == 'refunded') {
                                                echo "selected";
                                             } ?>><?php echo html_escape(_l('refunded')) ?></option>
                  <option value="failed" <?php if ($order->status == 'failed') {
                                             echo "selected";
                                          } ?>><?php echo html_escape(_l('failed')) ?></option>
               </select>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_escape(_l('cancel')) ?></button>
            <button type="submit" class="btn btn-info" name="btn-update">Update</button>
         </div>
         <?php echo form_close(); ?>
      </div>
   </div>
</div>
<!--end Modal content for invoice-->


<!-- Modal content for create invoice-->
<div class="modal fadeIn" id="invoiceModal" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <span><?php echo html_escape(_l('invoice_order')) ?> </span>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <?php echo form_open(admin_url('woocommerce/woocommerce_invoice/create_invoice')); ?>
         <div class="modal-body">
            <form action="" method="post">

               <div class="form-group">
                  <input type="text" class="form-control" name="orderid" id="orderid" value="<?= $order->id; ?>" readonly>
               </div>
               <div class="text-danger" id="customer_exists_info"></div>
               <div class="form-group select-placeholder">
                  <label for="clientid" class="control-label"><?php echo _l('invoice_select_customer'); ?></label>
                  <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search<?php if (isset($invoice) && empty($invoice->clientid)) {
                                                                                                                        echo ' customer-removed';
                                                                                                                     } ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                     <?php
                     $selected = (isset($client->userid) ? $client->userid : '');

                     if ($selected != '') {
                        $rel_data = get_relation_data('customer', $selected);
                        $rel_val = get_relation_values($rel_data, 'customer');
                        echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                     } ?>
                  </select>
               </div>
               <?php
                     $s_attrs = array('data-none-selected-text' => _l('system_default_string'));
                     $selected = '';
                     if (isset($client) && client_have_transactions($client->userid)) {
                        // $s_attrs['disabled'] = true;
                     }
                     foreach ($currencies as $currency) {
                        if (isset($order->currency)) {
                           if ($currency['name'] == $order->currency) {
                              $selected = $currency['id'];
                              // show only order currency
                              $currencies = [$currency];
                              break;
                           }
                        }
                     }
                     // Do not remove the currency field from the customer profile!
                     echo render_select('currency', $currencies, array('id', 'name', 'symbol'), 'invoice_add_edit_currency', $selected, $s_attrs); ?>
               <div class="text-danger" id="invoice_exists_info"></div>
               <hr />
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo html_escape(_l('cancel')) ?></button>
               <button type="submit" class="btn btn-info"><?php echo html_escape(_l('import')) ?></button>
         </div>
         </form>
      </div>
      <?php echo form_close(); ?>
   </div>
</div>
</div>
<?php } else { ?>
   <div class='alert alert-warning'>
      <?php echo html_escape(($order)); ?>
   </div>
   </div>
   </div>
   </div>
<?php }
                  init_tail(); ?>
</body>
<script src=" <?php echo site_url('modules/woocommerce/assets/js/order.js'); ?>"></script>

</html>