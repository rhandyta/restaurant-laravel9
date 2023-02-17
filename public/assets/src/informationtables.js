const categoryTableHandler = document.querySelector("#categorytablestore");
const categoryTableEditHandler = document.querySelector("#categorytableedit");
const btnEdit = document.querySelectorAll(".btn-edit");
const btnDestroy = document.querySelectorAll(".btn-delete");
const form = document.querySelector("#formaddinformationtable");
const formEdit = document.querySelector("#formeditinformationtable");

const __submitStoreHandler = async (event) => {
    try {
        event.preventDefault();
        const formDataStore = new FormData(form);
        const request = await fetch(`${SEGMENT_URL}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formDataStore,
        });
        const response = await request.json();
        if (response.status_code != 201) {
            let errorsMessage = Object.values(response.messages);
            errorsMessage.forEach((itemError) => {
                throw new Error(itemError[0]);
            });
        }
        successToast(response.message, 1000);
        return setTimeout(() => window.location.reload(), 1000);
    } catch (error) {
        errorToast(error);
    }
};

const __getDataById = async (id) => {
    try {
        const request = await fetch(`${SEGMENT_URL}/${id}`);
        const response = await request.json();
        if (response.status_code != 200) {
            throw new Error(response.messages);
        }
        formEdit.elements[0].value = response.results.category_table_id;
        formEdit.elements[1].value = response.results.seating_capacity;
        formEdit.elements[2].value = response.results.available;
        formEdit.elements[3].value = response.results.location;
    } catch (error) {
        errorToast(error);
    }
};

const __submitEditHandler = async (id) => {
    try {
        const formData = new FormData(formEdit);
        const formEncodedData = new URLSearchParams(formData).toString();
        console.log(formEncodedData);
        const request = await fetch(`${SEGMENT_URL}/${id}`, {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: formEncodedData,
        });
        const response = await request.json();
        if (response.status_code != 200) {
            let errorsMessage = Object.values(response.messages);
            errorsMessage.forEach((itemError) => {
                throw new Error(itemError[0]);
            });
        }
        successToast(response.message, 1000);
        setTimeout(() => window.location.reload(), 1000);
    } catch (error) {
        errorToast(error);
    }
};

const __submitDestroyHandler = async (id) => {
    try {
        const request = await fetch(`${SEGMENT_URL}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
                "Content-Type": "application/json",
            },
        });
        const response = await request.json();
        if (response.status_code != 200) {
            throw new Error("something went wrong");
        }
        return response;
    } catch (error) {
        errorToast(error);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const changeCategoryTableHandler = (event) => {
        let categoryStatus =
            event.target.selectedOptions[0].getAttribute("data-status");
        let options = Array.from(form.elements[2].options);

        if (categoryStatus == "deactive")
            return (options.find(
                (item) => item.value == "not available"
            ).selected = true);

        if (categoryStatus == "active")
            return (options.find(
                (item) => item.value == "available"
            ).selected = true);
    };

    const changeCategoryEditTableHandler = (event) => {
        let categoryStatus =
            event.target.selectedOptions[0].getAttribute("data-status");
        let options = Array.from(formEdit.elements[2].options);

        if (categoryStatus == "deactive")
            return (options.find(
                (item) => item.value == "not available"
            ).selected = true);

        if (categoryStatus == "active")
            return (options.find(
                (item) => item.value == "available"
            ).selected = true);
    };

    const clickEditInformationTableHandler = (event) => {
        let id = event.target.getAttribute("data-id");
        __getDataById(id);

        categoryTableEditHandler.addEventListener(
            "change",
            changeCategoryEditTableHandler
        );

        const submitEditHandler = (event) => {
            event.preventDefault();
            __submitEditHandler(id);
            formEdit.removeEventListener("submit", submitEditHandler);
        };

        formEdit.addEventListener("submit", submitEditHandler);
    };

    const clickDestroyHandler = (event) => {
        const id = event.target.getAttribute("data-id");
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

    categoryTableHandler.addEventListener("change", changeCategoryTableHandler);
    form.addEventListener("submit", __submitStoreHandler);

    btnEdit.forEach((selectId) => {
        selectId.addEventListener("click", clickEditInformationTableHandler);
    });

    btnDestroy.forEach((selectId) => {
        selectId.addEventListener("click", clickDestroyHandler);
    });

    return () => {
        categoryTableHandler.removeEventListener(
            "change",
            changeCategoryTableHandler
        );
        form.removeEventListener("submit", __submitStoreHandler);

        btnEdit.forEach((selectId) => {
            selectId.removeEventListener(
                "click",
                clickEditInformationTableHandler
            );
        });

        categoryTableHandler.removeEventListener(
            "change",
            changeCategoryEditTableHandler
        );

        btnDestroy.forEach((selectId) => {
            selectId.removeEventListener("click", clickDestroyHandler);
        });
    };
});
