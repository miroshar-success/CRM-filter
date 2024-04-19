var specialId;

const getDetails = (event, importp) => {
    let id = event.target.attributes["data-id"].value;
    if (!importp) document.getElementById("productId").value = id;
    let url = `${admin_url}woocommerce/get_product/${id}`;
    let xhr = new XMLHttpRequest;
    xhr.open("GET", url);
    xhr.responseType = "json";
    xhr.send();
    xhr.onload = () => {
        const result = xhr.response;
        let name, price, status, description, stock;
        if (result.success) {
            name = result.message.name;
            price = result.message.regular_price;
            status = result.message.status;
            description = result.message.short_description;
            stock = result.message.stock_quantity;
            document.querySelector(".currentPrice").innerHTML = `current price : ${result.message.price_html}`;
        } else {
            name = document.getElementById(`${id}name`).innerText;
            price = document.getElementById(`${id}price`).innerText
            status = document.getElementById(`${id}status`).innerText
            description = "";
            switch (status) {
                case "publish":
                    document.getElementById("status").selectedIndex = "0";
                    break;
                case "draft":
                    document.getElementById("status").selectedIndex = "1";
                    break;
                case "pending":
                    document.getElementById("status").selectedIndex = "2";
                    break;
                case "private":
                    document.getElementById("status").selectedIndex = "3";
                    break;

            }
        }
        if (importp) {
            impRender(name, price, description)
        } else {
            render(name, price, status, description);
        }
    }

}

$(document).on('click', '.add_item_perfex', (event) => {
    specialId = event.target.attributes["data-id"].value;
    $.get(`${admin_url}woocommerce/getItemId/${specialId}`).done((response)=>{
        response = JSON.parse(response);
        let    itemid = response.itemid; 
        if(itemid) document.querySelector("[name='itemid']").value = itemid ;
    })
    getDetails(event, true)
})

$(document).on('click', '.editProduct', (event) => {
    getDetails(event, false)
})

const render = (name, price, status, description) => {
    document.querySelector("#name").value = name;
    document.querySelector("#price").value = price;
    document.querySelector("#status").value = status;
    document.querySelector("#xdescription").textContent = description;
}

const impRender = (name, price, description, itemid) => {
    document.querySelector("#description").value = name;
    document.querySelector("#rate").value = price;
    document.querySelector("#long_description").innerHTML = stripHtml(description);
}

$(document).on('click', '.product_delete', (event) => {
    let name = event.target.attributes["data-name"].value;
    let id = event.target.attributes["data-id"].value;
    document.querySelector(".productId").value = id;
    document.getElementById("productName").value = name;
})

const addtocrm = () => {
    let data = $("#product_item_form").serialize();
    let url = admin_url + "invoice_items/manage";
    $.post(url, data).done((response) => {
        response = JSON.parse(response);
        if(response.item){
        $.get(`${admin_url}woocommerce/setItemId/${response.item.itemid}/${specialId}`)
        }
        alert_float('success', response.message);
        $('#add_item_perfex').modal('hide');
    }).fail((data) => {
        alert_float('danger', data.responseText);
    });
    return false;
}

const validate_item_form = () => {
    // Set validation for invoice item form
    appValidateForm($('#product_item_form'), {
        description: 'required',
        rate: {
            required: true,
        }
    }, addtocrm);
}
validate_item_form();

const stripHtml = (html) => {
    let tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
}