document.addEventListener('DOMContentLoaded', function () {
    "use strict";
    $(".table-proposals").attr("data-default-order", JSON.stringify([
        [0, 'asc']
    ]));
    $(".table-projects").attr("data-default-order", JSON.stringify([
        [0, 'asc']
    ]));
    $(".table-contracts").attr("data-default-order", JSON.stringify([
        [0, 'asc']
    ]));
    $(".table-expenses").attr("data-default-order", JSON.stringify([
        [0, 'asc']
    ]));
    $(".table-projects-single-client").attr("data-default-order", JSON.stringify([
        [0, 'asc']
    ]));
    $(".table-expenses-single-client").attr("data-default-order", JSON.stringify([
        [0, 'asc']
    ]));
}, false);