const addModal = document.querySelector("#order");
const gross_amount = document.querySelectorAll(".gross_amount");
const searchForm = document.querySelector("#search");
const page = document.querySelector("#page");
const queryString = window.location.search;
const params = new URLSearchParams(queryString);
const queries = Object.fromEntries(params.entries());

const __onSubmitSearchHandle = async (limit, search) => {
    window.location.replace(`${SEGMENT_URL}?limit=${limit}&search=${search}`);
};

const __getProduct = async () => {
    try {
        const request = await fetch(`${API_URL}/products`, {
            method: "GET",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });
        const response = await request.json();
        if (response.status_code != 200) {
            throw Error("something went wrong");
        }
        return response.data;
    } catch (error) {
        errorToast(error);
    }
};

document.addEventListener("DOMContentLoaded", () => {
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

    addModal.addEventListener("shown.bs.modal", async function (event) {
        const tBodyAdd = document.querySelector("#tbody_table_order_add");
        const addRow = document.querySelector("#add_row");
        const deleteRow = document.querySelector("#delete_row");
        let rowNumber = 1;

        addRow.addEventListener("click", function (event) {
            let newRow = rowNumber - 1;
            let sourceElement = document.querySelector(`#product${newRow}`);
            const createElementTr = document.createElement("tr");
            createElementTr.setAttribute("id", `product${rowNumber}`);
            for (const child of sourceElement.children) {
                createElementTr.appendChild(child.cloneNode(true));
            }
            tBodyAdd.appendChild(createElementTr);
            rowNumber++;
        });

        deleteRow.addEventListener("click", function (event) {
            if (rowNumber > 1) {
                tBodyAdd.removeChild(tBodyAdd.lastElementChild);
                rowNumber--;
            }
        });
    });
});
