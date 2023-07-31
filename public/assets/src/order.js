const addModal = document.querySelector("#order");
const gross_amount = document.querySelectorAll(".gross_amount");
const searchForm = document.querySelector("#search");
const page = document.querySelector("#page");

const queryString = window.location.search;
const params = new URLSearchParams(queryString);
const queries = Object.fromEntries(params.entries());

const tBodyAdd = document.querySelector("#tbody_table_order_add");
const formOrder = document.querySelector("#formorder");
const addSubtotal = document.querySelector("#add_subtotal");
const addDiscount = document.querySelector("#add_discount");
const addTax = document.querySelector("#add_tax");
const addTotal = document.querySelector("#add_total");
let rowNumber = 1;
let subtotal = 0;
let discount = 0;
let tax = 0.11;
let total = 0;

const __onSubmitSearchHandle = async (limit, search) => {
    window.location.replace(`${SEGMENT_URL}?limit=${limit}&search=${search}`);
};

const __addShowModal = () => {
    const addRow = document.querySelector("#add_row");
    const deleteRow = document.querySelector("#delete_row");

    const productElements = document.querySelector("[name='products[]']");
    const quantities = document.querySelector("[name='quantities[]']");

    addSubtotal.innerHTML = subtotal;
    addDiscount.innerHTML = discount;
    addTax.innerHTML = tax && "11%";
    total = subtotal - discount + subtotal * tax;
    addTotal.innerHTML = total;

    addRow.addEventListener("click", __addRow);
    deleteRow.addEventListener("click", __deleteRow);

    productElements.addEventListener("change", __changeProducts);
    quantities.addEventListener("change", __changeProducts);

    return () => {
        addRow.removeEventListener("click", __addRow);
        deleteRow.removeEventListener("click", __deleteRow);

        productElements.removeEventListener("change", __changeProducts);
        quantities.removeEventListener("change", __changeProducts);
    };
};

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

const __deleteRow = () => {
    if (rowNumber > 1) {
        tBodyAdd.removeChild(tBodyAdd.lastElementChild);
        rowNumber--;
        __changeProducts();
    }
};

async function __storeSubmitHandler(event) {
    event.preventDefault();
    const productsOrder = Array.from(
        formOrder.querySelectorAll('[name="products[]"]')
    ).map((item) => item.value);
    const quantities = Array.from(
        formOrder.querySelectorAll('[name="quantities[]"]')
    ).map((item) => item.value);
    const payment_type = formOrder.querySelector('[name="payment_type"]').value;
    const bank = formOrder.querySelector('[name="bank"]').value;
    const tables = formOrder.querySelector('[name="tables"]').value;
    const table = formOrder.querySelector('[name="table"]').value;
    const notes = formOrder.querySelector('[name="notes"]').value;
    const email = formOrder.querySelector('[name="email"]').value;
    const firstname = formOrder.querySelector('[name="firstname"]').value;
    const phone = formOrder.querySelector('[name="phone"]').value;

    const filteringProducts = products.filter((item, index) => {
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
    const request = await fetch(`${BASE_URL}/manager/orders`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    });
    const response = await request.json();
    console.log(response);
}

function __changeProducts() {
    const productElements = document.querySelectorAll("[name='products[]']");
    const quantitiesElements = document.querySelectorAll(
        "[name='quantities[]']"
    );
    let idProducts = [];
    let idQuantities = [];
    let tempData = [];
    productElements.forEach((item) => {
        idProducts.push(item.value);
    });
    quantitiesElements.forEach((item) => {
        idQuantities.push(item.value);
    });
    idProducts.splice(-1, 1);
    let orderProductList = products.filter((item, index) => {
        for (let i = 0; i < idProducts.length; i++) {
            if (item.id == idProducts[i]) {
                return item;
            }
        }
    });
    for (let j = 0; j < idProducts.length; j++) {
        tempData.push({
            product_id: idProducts[j],
            quantity: idQuantities[j],
        });
    }

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
function __changeQuantities() {
    let data = __changeProducts();

    data = [{ ...data }];
    console.log(data);
}

document.addEventListener("DOMContentLoaded", () => {
    const select = document.querySelectorAll("[name='products[]']");
    select.forEach((item) =>
        NiceSelect.bind(item, {
            searchable: true,
        })
    );
    let limit = 15;
    let search = "";
    gross_amount.forEach((amount) => {
        amount.innerHTML = `<span class="fw-bold">Rp${convertRupiah(
            Number(amount.innerHTML)
        )}</span>`;
    });
    page.value = queries.limit ? queries.limit : limit;
    searchForm.search.value = queries.search ? queries.search : "";

    if (params.has("limit")) {
        page.value = params.get("limit");
    }
    if (params.has("search")) {
        search = queries.search;
    }
    page.addEventListener("change", function (event) {
        limit = event.target.value;
        window.location.replace(
            `${SEGMENT_URL}?limit=${limit}&search=${search}`
        );
    });

    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        __onSubmitSearchHandle(page.value, this.search.value);
    });

    addModal.addEventListener("shown.bs.modal", __addShowModal);

    formOrder.addEventListener("submit", __storeSubmitHandler);

    return () => {
        addModal.removeEventListener("shown.bs.modal", __addShowModal);
    };
});
