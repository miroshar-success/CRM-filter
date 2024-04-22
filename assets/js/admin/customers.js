(function ($) {
  "use strict";

  $("#report_months").on("change", function () {
    var val = $(this).val();
    console.log(val);
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
      $(".table-clients").DataTable().ajax.reload();
    }
    if (val != "") $("#date_by_wrapper").removeClass("hide");
    else $("#date_by_wrapper").addClass("hide");
  });
  $('input[name="report_from"]').on("change", function () {
    if ($('input[name="report_to"]').val() != "") {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
  $('input[name="report_to"]').on("change", function () {
    if ($('input[name="report_from"]').val() != "") {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
})(jQuery);
