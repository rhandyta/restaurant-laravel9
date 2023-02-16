const SEGMENT_URL = `${BASE_URL}manager/tables/categories-tables`;
const headers = {
    "X-CSRF-TOKEN": csrfToken,
};

const form = document.querySelector("#formcategorytable");
const formEdit = document.querySelector("#editformcategorytable");
const addCategoryTable = document.querySelector("#addcategorytable");
const editCategoryTable = document.querySelectorAll(".btn-edit");

const __submitStoreHandler = async (event) => {
    try {
        event.preventDefault();
        const formData = new FormData(form);
        const request = await fetch(`${SEGMENT_URL}`, {
            method: "POST",
            headers,
            body: formData,
        });
        const response = await request.json();
        if (response.status_code != 201) {
            throw new Error(response.messages.category[0]);
        }
        successToast(response.message, 1000);
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    } catch (error) {
        return errorToast(error);
    }
};

const __getDataById = async (id) => {
    try {
        const request = await fetch(`${SEGMENT_URL}/${id}`, {
            method: "GET",
            headers,
        });
        const response = await request.json();
        if (response.status_code != 200) {
            throw new Error(response.messages);
        }
        formEdit.elements[0].value = response.results.category;
        formEdit.elements[1].value = response.results.status;
        formEdit.elements[2].value = response.results.id;
    } catch (error) {
        return errorToast(error);
    }
};

const __submitUpdateHandler = async (event) => {
    const categoryId = document.querySelector("#categoryId");
    try {
        event.preventDefault();
        const formData = new FormData(formEdit);
        const formEncodedData = new URLSearchParams(formData).toString();
        const request = await fetch(`${SEGMENT_URL}/${categoryId.value}`, {
            method: "PATCH",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formEncodedData,
        });
        const response = await request.json();
        if (response.status_code != 201) {
            throw new Error(response.messages.category[0]);
        }
        successToast(response.message, 1000);
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    } catch (error) {
        return errorToast(error);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const clickAddCategoryHandler = (e) => {
        const firstInput = form.elements[0];
        setTimeout(() => {
            firstInput.focus();
        }, 500);
        form.addEventListener("submit", __submitStoreHandler);
    };

    const clickEditCategoryHandler = (e) => {
        const id = e.target.getAttribute("data-id");
        const firstInputEdit = formEdit.elements[0];
        __getDataById(id);
        setTimeout(() => {
            firstInputEdit.focus();
        }, 500);
        formEdit.addEventListener("submit", __submitUpdateHandler);
    };

    addCategoryTable.addEventListener("click", clickAddCategoryHandler);

    editCategoryTable.forEach((selectId) => {
        selectId.addEventListener("click", clickEditCategoryHandler);
    });

    () => {
        addCategoryTable.removeEventListener("click", clickAddCategoryHandler);
        editCategoryTable.forEach((selectId) => {
            selectId.removeEventListener("click", clickEditCategoryHandler);
        });
        form.removeEventListener("submit", __submitStoreHandler);
        formEdit.removeEventListener("submit", __submitUpdateHandler);
    };
});
