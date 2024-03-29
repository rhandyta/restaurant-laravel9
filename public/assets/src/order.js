const addModal = document.querySelector("#order");
const gross_amount = document.querySelectorAll(".gross_amount");
const searchForm = document.querySelector("#search");
const page = document.querySelector("#page");

const queryString = window.location.search;
const params = new URLSearchParams(queryString);
const queries = Object.fromEntries(params.entries());

const table = document.querySelector("#table1");
const tBodyAdd = document.querySelector("#tbody_table_order_add");
const formOrder = document.querySelector("#formorder");
const addSubtotal = document.querySelector("#add_subtotal");
const addDiscount = document.querySelector("#add_discount");
const addTax = document.querySelector("#add_tax");
const addTotal = document.querySelector("#add_total");
const btnEdit = document.querySelectorAll(".btn-edit");
const formeditorder = document.querySelector("#formeditorder");

let rowNumber = 1;
let subtotal = 0;
let discount = 0;
let tax = 0.11;
let total = 0;

// Pusher
var pusher = new Pusher(PUSHER_KEY, {
    authEndpoint: "/broadcasting/auth",
    auth: {
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    },
    cluster: PUSHER_CLUSTER,
});


var channel = pusher.subscribe("private-order." + auth.id);
channel.bind("order-event", function ({ order }) {
    
    const elementOrder =  __manipulateOrderTransaction(order);
    let tbody = table.querySelector('tbody');
    if(order.transaction_status && elementOrder !== undefined) {
        tbody.insertAdjacentHTML('afterbegin', elementOrder)
    }

    const btnEditReload = document.querySelectorAll(".btn-edit");
    btnEditReload.forEach((item) => {
        item.addEventListener("click", __editTransactionStatus);
    });

    return () => {
        btnEditReload.forEach((item) => {
            item.removeEventListener("click", __editTransactionStatus);
        });
    }
});

const __manipulateOrderTransaction = (data) => {
    let newElement = ``;
    if(data.transaction_status == 'pending') {
        let rowOrder = null;
        const getAllTr = table.querySelectorAll('tr')
        getAllTr.forEach(item => {
            if(item.getAttribute('data-id') == data.order_id) {
                rowOrder = item
            }
        })
        if(!rowOrder) {
            if(auth.roles == 'cashier') {
                newElement = `<tr data-id="${data.order_id}">
                <td class="fw-bold">
                    <a href="/cashier/orders/${data.order_id}/show">${data.order_id}</a>
                </td>
                <td><span class="badge text-bg-primary">${data.transaction_status}</span></td>
                <td class="text-nowrap">${data.information_table}</td>
                <td class="gross_amount"><span class="fw-bold">Rp${convertRupiah(Number(data.gross_amount))}</span></td>
                <td style="white-space: nowrap;">${formatTime(data.created_at)}</td>
                <td style="text-transform: uppercase;">${data.payment_type}</td>
                <td class="text-uppercase">${!data.bank ? '-' : data.bank}</td>
                <td>
                    <button class="btn btn-success btn-sm btn-edit" data-id="${data.order_id}" data-bs-toggle="modal" data-bs-target="#editorder">Edit</button>
                </td>
            </tr>`
            } else {
                newElement = `<tr data-id="${data.order_id}">
                <td class="fw-bold">
                    <a href="/manager/orders/${data.order_id}/show">${data.order_id}</a>
                </td>
                <td><span class="badge text-bg-primary">${data.transaction_status}</span></td>
                <td class="text-nowrap">${data.information_table}</td>
                <td class="gross_amount"><span class="fw-bold">Rp${convertRupiah(Number(data.gross_amount))}</span></td>
                <td style="white-space: nowrap;">${formatTime(data.created_at)}</td>
                <td style="text-transform: uppercase;">${data.payment_type}</td>
                <td class="text-uppercase">${!data.bank ? '-' : data.bank}</td>
            </tr>`;
            }
        }
        return newElement;
    }

    if(data.transaction_status == 'settlement' && Number(data.transaction_code) == 200) {
        let rowOrder = null;
        const getAllTr = table.querySelectorAll('tr')
        getAllTr.forEach(item => {
            if(item.getAttribute('data-id') == data.order_id) {
                rowOrder = item
            }
        })

        rowOrder.querySelector('span.badge').classList.remove('text-bg-primary')
        rowOrder.querySelector('span.badge').classList.add('text-bg-success')
        rowOrder.querySelector('span.badge').textContent = 'settlement'
        if(rowOrder.querySelector('button[data-id="'+ data.order_id +'"]')) {
            rowOrder.querySelector('button[data-id="'+ data.order_id +'"]').closest('td').remove();
        }
    } else if (data.transaction_status == 'settlement' && Number(data.transaction_code) == 201) {
        let rowOrder = null;
        const getAllTr = table.querySelectorAll('tr')
        getAllTr.forEach(item => {
            if(item.getAttribute('data-id') == data.order_id) {
                rowOrder = item
            }
        })
        if(!rowOrder) {
            newElement = `<tr data-id="${data.order_id}">
                            <td class="fw-bold">
                            <a href="/cashier/orders/${data.order_id}/show">${data.order_id}</a>
                            </td>
                            <td><span class="badge text-bg-success">${data.transaction_status}</span></td>
                            <td class="text-nowrap">${data.information_table}</td>
                            <td class="gross_amount"><span class="fw-bold">Rp${convertRupiah(Number(data.gross_amount))}</span></td>
                            <td style="white-space: nowrap;">${formatTime(data.created_at)}</td>
                            <td style="text-transform: uppercase;">${data.payment_type}</td>
                            <td class="text-uppercase">${!data.bank ? '-' : data.bank}</td>
                            </tr>`;
            return newElement;
        }
    } else if(data.transaction_status == 'cancel' && Number(data.transaction_code) == 200) {
        let rowOrder = null;
        const getAllTr = table.querySelectorAll('tr')
        getAllTr.forEach(item => {
            if(item.getAttribute('data-id') == data.order_id) {
                rowOrder = item
            }
        })

        rowOrder.querySelector('span.badge').classList.remove('text-bg-primary')
        rowOrder.querySelector('span.badge').classList.add('text-bg-danger')
        rowOrder.querySelector('span.badge').textContent = 'cancel'
        if(rowOrder.querySelector('button[data-id="'+ data.order_id +'"]')) {
            rowOrder.querySelector('button[data-id="'+ data.order_id +'"]').closest('td').remove();
        }
    }
}

// query search
const __onSubmitSearchHandle = async (limit, search, page) => {
    window.location.replace(
        `${SEGMENT_URL}?limit=${limit}&search=${search}&page=${page}`
    );
};

// dom ketika modal add dibuka
const __addShowModal = () => {
    const addRow = document.querySelector("#add_row");
    const deleteRow = document.querySelector("#delete_row");

    const tables = document.querySelector("#tables_add");
    const payment_type = document.querySelector("#payment_type");

    const productElements = document.querySelector("[name='products[]']");
    const quantities = document.querySelector("[name='quantities[]']");

    addSubtotal.innerHTML = subtotal;
    addDiscount.innerHTML = discount;
    addTax.innerHTML = tax && "11%";
    total = subtotal - discount + subtotal * tax;
    addTotal.innerHTML = total;

    payment_type.addEventListener("change", __changePaymentTypeHandler);
    tables.addEventListener("change", __changeTablesHandler);

    addRow.addEventListener("click", __addRow);
    deleteRow.addEventListener("click", __deleteRow);

    productElements.addEventListener("change", __changeProducts);
    quantities.addEventListener("change", __changeProducts);

    return () => {
        addRow.removeEventListener("click", __addRow);
        deleteRow.removeEventListener("click", __deleteRow);
        payment_type.removeEventListener("change", __changePaymentTypeHandler);
        tables.removeEventListener("change", __changeTablesHandler);
        productElements.removeEventListener("change", __changeProducts);
        quantities.removeEventListener("change", __changeProducts);
    };
};

// add row
const __addRow = () => {
    let newRow = rowNumber - 1;
    let sourceElement = document.querySelector(`#product${newRow}`);
    const createElementTr = document.createElement("tr");
    createElementTr.setAttribute("id", `product${rowNumber}`);
    // Membuat elemen select baru dengan mengkloning dari elemen sebelumnya
    let newSelect = sourceElement.querySelector("select").cloneNode(true);
    // Mendapatkan nilai yang sudah dipilih pada select sebelumnya
    let selectedValue = sourceElement.querySelector("select").value;
    // Menghapus opsi yang sudah dipilih dari elemen select baru
    for (const option of newSelect.options) {
        if (option.value === selectedValue) {
            option.remove();
        }
    }
    // Menambahkan elemen select baru ke dalam elemen <td>
    let newTd = document.createElement("td");
    newTd.classList.add("col-12", "col-md-9");
    newTd.appendChild(newSelect);
    // Menambahkan elemen <td> ke dalam elemen <tr>
    createElementTr.appendChild(newTd);
    // Membuat elemen input quantity baru
    let newQuantityInput = document.createElement("input");
    newQuantityInput.setAttribute("type", "number");
    newQuantityInput.setAttribute("class", "form-control");
    newQuantityInput.setAttribute("min", "1");
    newQuantityInput.setAttribute("name", "quantities[]");
    newQuantityInput.setAttribute("placeholder", "5");
    newQuantityInput.setAttribute("required", true);
    newQuantityInput.value = ""; // Mengatur nilai default quantity menjadi kosong
    // Membuat elemen <td> dengan input quantity baru dan menambahkannya ke dalam elemen <tr>
    let newTdQuantity = document.createElement("td");
    newTdQuantity.classList.add("col-auto");
    newTdQuantity.appendChild(newQuantityInput);
    createElementTr.appendChild(newTdQuantity);
    // Mengklon elemen <td> lainnya dan menambahkannya ke dalam elemen <tr>
    for (let i = 2; i < sourceElement.children.length; i++) {
        let td = sourceElement.children[i].cloneNode(true);
        createElementTr.appendChild(td);
    }
    NiceSelect.bind(createElementTr.firstElementChild.firstElementChild, {
        searchable: true,
    });
    // Menambahkan elemen <tr> ke dalam <tbody>
    tBodyAdd.appendChild(createElementTr);
    const productElements = document.querySelectorAll("[name='products[]']");
    const quantities = document.querySelectorAll("[name='quantities[]']");
    productElements.forEach((item) => {
        item.addEventListener("change", __changeProducts);
    });
    quantities.forEach((item) => {
        item.addEventListener("change", __changeProducts);
    });

    rowNumber++;

    return () => {
        products.forEach((item) => {
            item.removeEventListener("change", __changeProducts);
        });
        quantities.forEach((item) => {
            item.removeEventListener("change", __changeProducts);
        });
    };
};

// delete row
const __deleteRow = () => {
    if (rowNumber > 1) {
        tBodyAdd.removeChild(tBodyAdd.lastElementChild);
        rowNumber--;
        __changeProducts();
    }
};

// manipulasi payment
function __changePaymentTypeHandler() {
    const bank_add = document.querySelector("#bank_add");
    const bank = document.querySelector("#bank");
    if (this.value == "cash") {
        bank_add.classList.add("d-none");
    } else {
        bank.innerHTML = "";
        let via = paymentTypes.filter(
            (item) => item.payment_type.replace(" ", "_") == this.value
        );
        if (via.length > 0) {
            let option = via[0].banks.map((item) => {
                return `<option value="${item.name}">${item.name}</option>`;
            });
            let fragment = document
                .createRange()
                .createContextualFragment(option.join(""));
            bank.appendChild(fragment);
            bank_add.classList.remove("d-none");
        }
    }
}

// manipulasi data table berdasarkan category table
function __changeTablesHandler() {
    let table = getAllTables.filter((item) => item.id == this.value);
    document.querySelector("#table_add").innerHTML = "";

    if (table.length > 0) {
        let option = table[0].informationtables.map((item) => {
            return `<option value="${item.no}">${item.no}</option>`;
        });

        // Membuat elemen DOM dari string HTML
        let fragment = document
            .createRange()
            .createContextualFragment(option.join(""));

        // Menambahkan elemen DOM sebagai anak ke elemen dengan ID "table_add"
        document.querySelector("#table_add").appendChild(fragment);
        document.querySelector("#tableshidden").classList.remove("d-none");
    }
    return;
}

// submit order
async function __storeSubmitHandler(event) {
    try {
        event.preventDefault();
        const productsOrder = Array.from(
            formOrder.querySelectorAll('[name="products[]"]')
        ).map((item) => item.value);
        const quantities = Array.from(
            formOrder.querySelectorAll('[name="quantities[]"]')
        ).map((item) => item.value);
        const payment_type = formOrder.querySelector(
            '[name="payment_type"]'
        ).value;
        const bank = formOrder.querySelector('[name="bank"]').value;
        const tables = formOrder.querySelector('[name="tables"]').value;
        const table = formOrder.querySelector('[name="table"]').value;
        const notes = formOrder.querySelector('[name="notes"]').value;
        const email = formOrder.querySelector('[name="email"]').value;
        const firstname = formOrder.querySelector('[name="firstname"]').value;
        const phone = formOrder.querySelector('[name="phone"]').value;

        const filteringProducts = products.filter((item) => {
            for (let i = 0; i < productsOrder.length; i++) {
                if (productsOrder[i] == item.id) {
                    return item;
                }
            }
        });

        const detail_orders = productsOrder.map((product, index) => ({
            id: index,
            product_id: product,
            quantity: quantities[index],
            food_name: filteringProducts[index].food_name,
            unit_price: filteringProducts[index].price,
        }));

        const data = {
            payment_type,
            bank,
            tables,
            table,
            notes,
            email,
            firstname,
            phone,
            detail_orders,
        };
        const request = await fetch(`${SEGMENT_URL}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });
        const response = await request.json();
        if (response.status_code !== 201) {
            throw Error("something went wrong");
        }
        successToast(response.messages, 1000);
        return setTimeout(() => window.location.reload(), 1000);
    } catch (error) {
        return errorToast(error, 1000);
    }
}

// untuk menghitung subtotal dan total berdasarkan product yang diorder
function __changeProducts() {
    // init selector element
    const productElements = document.querySelectorAll("[name='products[]']");
    const quantitiesElements = document.querySelectorAll(
        "[name='quantities[]']"
    );

    // siapkan wadah
    let idProducts = [];
    let idQuantities = [];
    let tempData = [];

    // mengulang element product yang dipilih
    productElements.forEach((item) => {
        idProducts.push(item.value);
    });
    // mengulang element quantitas yang diorder pada
    quantitiesElements.forEach((item) => {
        idQuantities.push(item.value);
    });
    // data product yang di pilih lebih 1 dari makanya dihapus (bug)
    // idProducts.splice(-1, 1);

    // mengambil data mentah products yang dipassing dari index blade untuk dicocokan dan mengambil harga dan nama product
    let orderProductList = products.filter((item, index) => {
        for (let i = 0; i < idProducts.length; i++) {
            if (item.id == idProducts[i]) {
                return item;
            }
        }
    });

    // membuat object product id dan quantity yang dipilih
    for (let j = 0; j < idProducts.length; j++) {
        tempData.push({
            product_id: idProducts[j],
            quantity: idQuantities[j],
        });
    }

    // menggabung kan data yang diambil dari data data mentah yang sudah dilakukan filter dengan product yang di pilih
    let data = tempData.map((item) => {
        for (let p = 0; p < orderProductList.length; p++) {
            if (item.product_id == orderProductList[p].id) {
                return {
                    id: Number(item.product_id),
                    food_name: orderProductList[p].food_name,
                    price: Number(orderProductList[p].price),
                    quantity: Number(item.quantity),
                };
            }
        }
    });

    // hitung sub total dan total lalu melakukan dom
    subtotal = 0;
    discount = 0;
    total = 0;
    for (const item of data) {
        subtotal += item.price * item.quantity;
    }
    total = subtotal - subtotal * discount + subtotal * tax;
    addSubtotal.innerHTML = `Rp${convertRupiah(subtotal)}`;
    addTotal.innerHTML = `Rp${convertRupiah(total)}`;

    return;
}

function __editTransactionStatus() {
    formeditorder.id.value = this.getAttribute("data-id");
}

async function __updateTransactionStatus(event) {
    event.preventDefault();
    try {
        const formData = new FormData(formeditorder);
        formData.append("_method", "PATCH");
        if (this.transaction_status.checked) {
            formData.append("transaction_status", "settlement");
        } else {
            formData.append("transaction_status", "");
        }
        const request = await fetch(`${SEGMENT_URL}/${this.id.value}/update`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });
        const response = await request.json();
        if (response.status_code == 400) throw new Error(response.messages);
        if (response.status_code != 200)
            throw new Error("something went wrong");

        successToast(response.messages);
        return setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (e) {
        return errorToast(e);
    }
}

// ketika halaman sudah diload
document.addEventListener("DOMContentLoaded", () => {
    // mengubah option select menggunakan library nice
    const select = document.querySelectorAll("[name='products[]']");
    select.forEach((item) =>
        NiceSelect.bind(item, {
            searchable: true,
        })
    );

    // dom amount menjadi format rupiah
    gross_amount.forEach((amount) => {
        amount.innerHTML = `<span class="fw-bold">Rp${convertRupiah(
            Number(amount.innerHTML)
        )}</span>`;
    });
    // query params page
    let limit = 15;
    let search = "";
    let pages = 1;

    page.value = queries.limit ? queries.limit : limit;
    searchForm.search.value = queries.search ? queries.search : "";
    pages = queries.page ? queries.page : pages;

    if (params.has("limit")) {
        page.value = params.get("limit");
    }
    if (params.has("search")) {
        search = queries.search;
    }
    if (params.has("page")) {
        pages = queries.page;
    }
    page.addEventListener("change", function (event) {
        limit = event.target.value;
        window.location.replace(
            `${SEGMENT_URL}?limit=${limit}&search=${search}&page=${pages}`
        );
    });

    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        __onSubmitSearchHandle(page.value, this.search.value, pages);
    });

    addModal.addEventListener("shown.bs.modal", __addShowModal);

    formOrder.addEventListener("submit", __storeSubmitHandler);

    btnEdit.forEach((item) => {
        item.addEventListener("click", __editTransactionStatus);
    });
    formeditorder.addEventListener("submit", __updateTransactionStatus);

    return () => {
        addModal.removeEventListener("shown.bs.modal", __addShowModal);

        btnEdit.forEach((item) => {
            item.removeEventListener("click", __editTransactionStatus);
        });
        formeditorder.removeEventListener("submit", __updateTransactionStatus);
    };
});
