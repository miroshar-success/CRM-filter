$(document).on("click", "#woo_test_button", function() {
    "use strict";
    let $woob = $(this);
 let $customerExistsDiv = $('#woo_test_result');

 $.post(admin_url+'woocommerce/woocommerce/test_connection', {}) 
 .done(function(response) {
     if(response) {
         response = JSON.parse(response);
         if(response.success == true) {
             $customerExistsDiv.html(`\<div class=\"info-block bg-success text-white mbot15\"\>${response.message}\<\/div\>`);
             $woob.button('reset');

         } else {
             $customerExistsDiv.html(`<div class=\"info-block bg-warning text-white mbot15\">${response.message}\</div\>`);
             $woob.button('reset');

         }
     }
   });

});

