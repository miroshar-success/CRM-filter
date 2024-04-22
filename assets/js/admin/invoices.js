(function ($) {
  "use strict";

  $("#report_months").on("change", function () {
    var val = $(this).val();
    var report_from = $("#report_from");
    var report_to = $("#report_to");
    var date_range = $("#date-range");

    report_to.val("");
    report_from.val("");
    if (val == "custom") {
      date_range.addClass("fadeIn").removeClass("hide");
      return;
    } else {
      if (!date_range.hasClass("hide")) {
        date_range.removeClass("fadeIn").addClass("hide");
      }
      $(".table-invoices").DataTable().ajax.reload();
    }
    if (val != "") $("#date_by_wrapper").removeClass("hide");
    else $("#date_by_wrapper").addClass("hide");
  });


  $('input[name="report_from"]').on("change", function () {
    if ($('input[name="report_to"]').val() != "") {
      $(".table-invoices").DataTable().ajax.reload();
      return false;
    }
  });

  $('input[name="report_to"]').on("change", function () {
    if ($('input[name="report_from"]').val() != "") {
      $(".table-invoices").DataTable().ajax.reload();
      return false;
    }
  });

  $('input[name="total_min"]').on("change", function () {
    if ($('input[name="total_max"]').val() != -1) {
      $(".table-invoices").DataTable().ajax.reload();
      return false;
    }
  });

  $('input[name="total_max"]').on("change", function () {
    if ($('input[name="total_min"]').val() != -1) {
      $(".table-invoices").DataTable().ajax.reload();
      return false;
    }
  });

})(jQuery);
