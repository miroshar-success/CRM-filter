    $(document).on("click", ".invoiceOrder", function() {
     "use strict";

  var id = document.getElementById("cDiv").innerHTML ;
  var wco_id = document.getElementById("oDiv").innerHTML ;

  var $customerExistsDiv = $('#customer_exists_info');
  var $invoiceExistsDiv = $('#invoice_exists_info');

  $.post(admin_url+'woocommerce/woocommerce_invoice/check_customer', {id:id})
  .done(function(response) {
      if(response) {
          response = JSON.parse(response);
          if(response.exists == true) {
              $customerExistsDiv.addClass('hide');
          } else {
              $customerExistsDiv.removeClass('hide');
              $customerExistsDiv.html('<div class="info-block mbot15">'+response.message+'</div>');
          }
      }
    });

  $.post(admin_url+'woocommerce/woocommerce_invoice/check_invoice', {wco_id:wco_id})
  .done(function(response) {
      if(response) {
          response = JSON.parse(response);
          if(response.exists == true) {
              $invoiceExistsDiv.removeClass('hide');
              $invoiceExistsDiv.html('<div class="info-block mbot15">'+response.message+'</div>');
          } else {
              $invoiceExistsDiv.addClass('hide');
          }
      }
    });
});




   