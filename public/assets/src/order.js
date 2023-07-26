const gross_amount = document.querySelectorAll(".gross_amount");
const searchForm = document.querySelector("#search");
const page = document.querySelector("#page");
const queryString = window.location.search;
const params = new URLSearchParams(queryString);
const queries = Object.fromEntries(params.entries());

const __onSubmitSearchHandle = async (limit, search) => {
    window.location.replace(`${SEGMENT_URL}?limit=${limit}&search=${search}`);
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
});
