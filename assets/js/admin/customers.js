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
  $('input[name="upcoming_from"]').on("change", function () {
    if ($('input[name="upcoming_to"]').val() != "") {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
  $('input[name="upcoming_to"]').on("change", function () {
    if ($('input[name="upcoming_from"]').val() != "") {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
  $('input[name="safe_from"]').on("change", function () {
    if ($('input[name="safe_to"]').val() != "") {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
  $('input[name="safe_to"]').on("change", function () {
    if ($('input[name="safe_from"]').val() != "") {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
  $('input[name="creditMax_from"]').on("change", function () {
    if ($('input[name="creditMax_to"]').val() != -1) {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
  $('input[name="creditMax_to"]').on("change", function () {
    if ($('input[name="creditMax_from"]').val() != -1) {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
  $('input[name="creditScore_from"]').on("change", function () {
    if ($('input[name="creditScore_to"]').val() != -1) {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
  $('input[name="creditScore_to"]').on("change", function () {
    if ($('input[name="creditScore_from"]').val() != -1) {
      $(".table-clients").DataTable().ajax.reload();
      return false;
    }
  });
})(jQuery);
