const SEGMENT_URL = `${BASE_URL}manager/tables/categories-tables`;
const headers = {
    "X-CSRF-TOKEN": csrfToken,
};

const handleStoreData = async (formData) => {
    try {
        const request = await fetch(`${SEGMENT_URL}`, {
            method: "POST",
            headers,
            body: formData,
        });
        const response = await request.json();
        if (response.status_code != 201)
            throw new Error("Something went wrong");
        return "ok";
    } catch (error) {
        console.error(error.message);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const addCategoryTable = document.querySelector("#addcategorytable");
    const editCategoryTable = document.querySelector("#editcategorytable");
    const form = document.querySelector("#formcategorytable");
    const nameForm = document.querySelectorAll(".name_form");

    const __submitHandler = (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        handleStoreData(formData);
    };

    const clickAddCategoryHandler = (e) => {
        const firstInput = form.elements[0];
        setTimeout(() => {
            firstInput.focus();
        }, 500);
        nameForm.forEach((name) => (name.textContent = "Add Category Table"));
        form.addEventListener("submit", __submitHandler);
    };
    const clickEditCategoryHandler = (e) => {
        const firstInput = form.elements[0];
        setTimeout(() => {
            firstInput.focus();
        }, 500);
        nameForm.forEach((name) => (name.textContent = "Edit Category Table"));
        form.addEventListener("submit", __submitHandler);
    };

    addCategoryTable.addEventListener("click", clickAddCategoryHandler);
    editCategoryTable.addEventListener("click", clickEditCategoryHandler);

    return () => {
        addCategoryTable.removeEventListener("click", clickAddCategoryHandler);
        addCategoryTable.removeEventListener("click", clickEditCategoryHandler);
        form.removeEventListener("submit", __submitHandler);
    };
});
