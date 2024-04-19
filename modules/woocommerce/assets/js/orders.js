   $(document).on("click", ".order_update", function() {
     "use strict";
       var Id = $(this).data('id');
   $(".modal-body #orderId").val(Id);
   });
   
   $(document).on("click", ".order_delete", function() {
     "use strict";
        var Id = $(this).data('id');
       $(".modal-body #orderid").val(Id);
   });