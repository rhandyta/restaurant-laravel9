const formStore = document.querySelector("#formfoodcategory");
const btnEdit = document.querySelectorAll(".btn-edit");
const formEdit = document.querySelector("#formeditcategoryfood");
const btnDestroy = document.querySelectorAll(".btn-delete");

const __formStoreSubmitHandler = async (event) => {
    try {
        event.preventDefault();
        const formData = new FormData(formStore);
        const request = await fetch(`${SEGMENT_URL}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });
        const response = await request.json();
        if (response.status_code != 201) {
            Object.values(response.messages).forEach((errorItem) => {
                throw new Error(errorItem);
            });
        }
        successToast(response.message, 1000);
        return setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (error) {
        return errorToast(error, 1000);
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
            throw new Erro("something went wrong");
        }
        formEdit.elements[0].value = response.results.category_name;
        formEdit.elements[1].value = response.results.category_description;
    } catch (error) {
        return errorToast(error);
    }
};

const __formUpdateSubmitHandler = async (id, event) => {
    try {
        event.preventDefault();
        const formData = new FormData(formEdit);
        const formEncoded = new URLSearchParams(formData).toString();
        const request = await fetch(`${SEGMENT_URL}/${id}`, {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: formEncoded,
        });
        const response = await request.json();
        if (response.status_code != 200) {
            Object.values(response.messages).forEach((errorItem) => {
                throw new Error(errorItem[0]);
            });
        }
        successToast(response.message, 1000);
        return setTimeout(() => window.location.reload(), 1000);
    } catch (error) {
        return errorToast(error, 1000);
    }
};

const __submitDestroyHandler = async (id) => {
    try {
        const request = await fetch(`${SEGMENT_URL}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        });
        const response = await request.json();
        if (response?.status_code != 200) {
            throw new Error("something went wrong");
        }
        return response;
    } catch (error) {
        errorToast(error);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    formStore.addEventListener("submit", __formStoreSubmitHandler);

    const clickEditHandler = (event) => {
        let id = event.target.getAttribute("data-id");
        __getDataById(id);
        const formSubmitHandler = __formUpdateSubmitHandler.bind(null, id);
        formEdit.addEventListener("submit", formSubmitHandler);
        formEdit.addEventListener("submit", function removeSubmitListener() {
            formEdit.removeEventListener("submit", formSubmitHandler);
            formEdit.removeEventListener("submit", removeSubmitListener);
        });
    };

    const clickDestroyHandler = (event) => {
        let id = event.target.getAttribute("data-id");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then(async (result) => {
            try {
                if (result.isConfirmed) {
                    const response = await __submitDestroyHandler(id);
                    if (response.status_code != 200)
                        throw new Error("something went wrong");
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
            } catch (error) {
                errorToast(error);
            }
        });
    };

    btnEdit.forEach((selectId) => {
        selectId.addEventListener("click", clickEditHandler);
    });

    btnDestroy.forEach((selectId) => {
        selectId.addEventListener("click", clickDestroyHandler);
    });

    return () => {
        formStore.removeEventListener("submit", __formStoreSubmitHandler);
        btnEdit.forEach((selectId) => {
            selectId.removeEventListener("click", clickEditHandler);
        });
        btnDestroy.forEach((selectId) => {
            selectId.removeEventListener("click", clickDestroyHandler);
        });
    };
});
