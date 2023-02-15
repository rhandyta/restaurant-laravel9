const SEGMENT_URL = `${BASE_URL}manager/tables/categories-tables`;
const headers = {
    "X-CSRF-TOKEN": csrfToken,
};

const form = document.querySelector("#formcategorytable");

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

document.addEventListener("DOMContentLoaded", () => {
    const addCategoryTable = document.querySelector("#addcategorytable");
    const editCategoryTable = document.querySelector("#editcategorytable");
    const nameForm = document.querySelectorAll(".name_form");

    const clickAddCategoryHandler = (e) => {
        const firstInput = form.elements[0];
        setTimeout(() => {
            firstInput.focus();
        }, 500);
        nameForm.forEach((name) => (name.textContent = "Add Category Table"));
        form.addEventListener("submit", __submitStoreHandler);
    };

    const clickEditCategoryHandler = (e) => {
        const id = e.target.getAttribute("data-id");
        const firstInput = form.elements[0];
        setTimeout(() => {
            firstInput.focus();
        }, 500);
        nameForm.forEach((name) => (name.textContent = "Edit Category Table"));
        form.addEventListener("submit", __submitStoreHandler);
    };

    addCategoryTable.addEventListener("click", clickAddCategoryHandler);
    // editCategoryTable.addEventListener("click", clickEditCategoryHandler);

    return () => {
        addCategoryTable.removeEventListener("click", clickAddCategoryHandler);
        // editCategoryTable.removeEventListener(
        //     "click",
        //     clickEditCategoryHandler
        // );
        form.removeEventListener("submit", __submitStoreHandler);
    };
});
