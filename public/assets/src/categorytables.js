const form = document.querySelector("#formcategorytable");
const formEdit = document.querySelector("#editformcategorytable");
const addCategoryTable = document.querySelector("#addcategorytable");
const editCategoryTable = document.querySelectorAll(".btn-edit");
const buttonDelete = document.querySelectorAll(".btn-delete");

const __submitStoreHandler = async (event) => {
    try {
        event.preventDefault();
        const formData = new FormData(form);
        const request = await fetch(`${SEGMENT_URL}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });
        const response = await request.json();
        if (response.status_code != 201) {
            throw new Error(response.messages.category[0]);
        }
        successToast(response.message, 1000);
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (error) {
        return errorToast(error);
    }
};

const __getDataById = async (id) => {
    try {
        const request = await fetch(`${SEGMENT_URL}/${id}`, {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
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
        if (response.status_code != 200) {
            throw new Error(response.messages.category[0]);
        }
        successToast(response.message, 1000);
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (error) {
        return errorToast(error);
    }
};

const __submitDestroyHandler = async (id) => {
    try {
        const request = await fetch(`${SEGMENT_URL}/${id}`, {
            method: "DELETE",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        });
        const response = await request.json();
        if (response.status_code != 200) {
            throw Error("something went wrong");
        }
        return response;
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

    const clickDestroyCategoryHandler = (e) => {
        const id = e.target.getAttribute("data-id");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await __submitDestroyHandler(id);
                if (response.status_code == 200) {
                    await Swal.fire(
                        "Deleted!",
                        "Your data has been deleted.",
                        "success"
                    );
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                }
            }
        });
    };

    addCategoryTable.addEventListener("click", clickAddCategoryHandler);

    editCategoryTable.forEach((selectId) => {
        selectId.addEventListener("click", clickEditCategoryHandler);
    });

    buttonDelete.forEach((selectId) => {
        selectId.addEventListener("click", clickDestroyCategoryHandler);
    });

    () => {
        addCategoryTable.removeEventListener("click", clickAddCategoryHandler);
        editCategoryTable.forEach((selectId) => {
            selectId.removeEventListener("click", clickEditCategoryHandler);
        });
        buttonDelete.forEach((selectId) => {
            selectId.removeEventListener("click", clickDestroyCategoryHandler);
        });
        form.removeEventListener("submit", __submitStoreHandler);
        formEdit.removeEventListener("submit", __submitUpdateHandler);
    };
});
