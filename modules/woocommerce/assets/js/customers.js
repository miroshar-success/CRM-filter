function spass() {
    var checkBox = document.getElementById("changePassword");
    var divb = document.getElementById("spass");
    if (checkBox.checked == true) {
        divb.classList.remove('hide');
    } else {
        divb.classList.add('hide');
    }
}



$(document).on('click','.editCust',(event)=>{
    let id = event.target.attributes["data-id"].value;
    document.querySelector("#custId").value = id

    const gname=()=>{
        const xname = document.getElementById(`${id}name`);
        let name = {
             'fname': xname.attributes['fname'].value,
             'lname': xname.attributes['lname'].value,
            };
        return name;
    }

    const render = (name, username, email) => {
        document.querySelector("#firstName").value = name['fname'];
        document.querySelector("#lastName").value = name['lname'];
        document.querySelector("#username").value = username;
        document.querySelector("#email").value = email;
    }

    let username = document.getElementById(`${id}username`).innerText;
    let email = document.getElementById(`${id}email`).innerText;
    let name = gname();

    render(name, username, email);



})

$(function () {
    "use strict";
    init_btn_with_tooltips();
    var opts = {
        'custom_view': '[name="custom_view"]'
    }
    var wooNotSortable = [5];
    initDataTable('.table-woocommerce', admin_url + 'woocommerce/table/customers', [], wooNotSortable, opts, [0, 'desc']);
});

$(document).on("click", ".viewID", function () {
    "use strict";
    var custId = $(this).data('id');
    $(".modal-body #id").val(custId);
});

$('#company').on('blur', function () {
    var company = $(this).val();
    var $companyExistsDiv = $('#company_exists_info');

    if (company == '') {
        $companyExistsDiv.addClass('hide');
        return;
    }

    $.post(admin_url + 'clients/check_duplicate_customer_name', {
            company: company
        })
        .done(function (response) {
            if (response) {
                response = JSON.parse(response);
                if (response.exists == true) {
                    $companyExistsDiv.removeClass('hide');
                    $companyExistsDiv.html('<div class="info-block mbot15">' + response.message + '</div>');
                } else {
                    $companyExistsDiv.addClass('hide');
                }
            }
        });
});

const checkpass=(form)=>{
    if(!document.getElementById("changePassword").checked){
        return true;
    }

    if(  form.password.value !="" && form.password.value === form.passwordr.value){
        return true;
    }
    document.querySelector(".pcheck").classList.remove("hide")
    form.passwordr.focus();
    return false;
}

document.querySelector("#passwordr").onkeyup =(event)=>{
    let p1 = document.querySelector("#password").value
    let p2 = event.target.value

    if (p1 != p2){
        document.querySelector(".pcheck").classList.remove("hide")
    } else {
        document.querySelector(".pcheck").classList.add("hide")
    }
}

function deleteCustomer (el){
    let id = $(el).data('id')
    let name = $(el).data('name')
    $('#cname').val(name);
    $('#productId').val(id);
}
