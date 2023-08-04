const formbank = document.querySelector("#formbank");
const formbank_edit = document.querySelector("#formbank_edit");
const data = {};

async function __onSubmitHandler(event) {
    event.preventDefault();
    try {
        const formData = new FormData(formbank);
        const request = await fetch(`${SEGMENT_URL}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });

        const response = await request.json();
        if (response.status_code == 422) {
            Object.values(response.messages).forEach((item) => {
                throw new Error(item);
            });
        }
        if (response.status_code != 201) throw Error("something went wrong");
        successToast(response.messages, 1000);
        return setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (e) {
        errorToast(e);
    }
}

async function __onSubmitUpdateHandler(event) {
    event.preventDefault();
    try {
        const formData = new FormData(formbank_edit);
        formData.append("_method", "PATCH");
        const request = await fetch(`${SEGMENT_URL}/${data.id}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });
        const response = await request.json();
        if (response.status_code == 422) {
            Object.values(response.messages).forEach((item) => {
                throw new Error(item);
            });
        }
        if (response.status_code !== 200)
            throw new Error("something went wrong");
        successToast(response.messages, 1000);
        return setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (e) {
        return errorToast(e);
    }
}

function __destroyHandler() {
    const id = this.getAttribute("data-id");
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
            try {
                const request = await fetch(`${SEGMENT_URL}/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                });
                const response = await request.json();
                if (response.status_code !== 200)
                    throw new Error(response.messages);
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
            } catch(e) {
                errorToast(e)
            }
        }
    });
}

async function __getDataById() {
    try {
        const request = await fetch(
            `${SEGMENT_URL}/${this.getAttribute("data-id")}`
        );
        const response = await request.json();
        if (response.status_code !== 200) throw new Error(response.messages);
        formbank_edit.id.value = response.data.id;
        formbank_edit.payment_type_id.value = response.data.payment_type_id;
        formbank_edit.payment_type_id.style.pointerEvents= 'none';
        formbank_edit.name.value = response.data.name;
        formbank_edit.status.value = response.data.status;
        return Object.assign(data, response.data);
    } catch (e) {
        return errorToast(e);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    formbank.addEventListener("submit", __onSubmitHandler);

    document.querySelectorAll(".btn-edit").forEach((item) => {
        item.addEventListener("click", __getDataById);
    });
    document.querySelectorAll(".btn-delete").forEach((item) => {
        item.addEventListener("click", __destroyHandler);
    });
    formbank_edit.addEventListener("submit", __onSubmitUpdateHandler);

    return () => {
        formpayment.removeEventListener("submit", __onSubmitHandler);
        formpayment_edit.removeEventListener("submit", __onSubmitUpdateHandler);
        document.querySelectorAll(".btn-edit").forEach((item) => {
            item.addEventListener("click", __getDataById);
        });
        document.querySelectorAll(".btn-delete").forEach((item) => {
            item.addEventListener("click", __destroyHandler);
        });
    };
});
