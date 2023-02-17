const categoryTableHandler = document.querySelector("#category_table_id");
const form = document.querySelector("#formaddinformationtable");

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

// ready
document.addEventListener("DOMContentLoaded", () => {
    const changeCategoryTableHandler = (event) => {
        let categoryStatus =
            event.target.selectedOptions[0].getAttribute("data-status");
        let options = Array.from(form.elements[2].options);

        if (categoryStatus == "deactive")
            return (options.find(
                (item) => item.value == "Not Available"
            ).selected = true);

        if (categoryStatus == "active")
            return (options.find(
                (item) => item.value == "Available"
            ).selected = true);
    };

    categoryTableHandler.addEventListener("change", changeCategoryTableHandler);
    form.addEventListener("submit", __submitStoreHandler);

    return () => {
        categoryTableHandler.removeEventListener(
            "change",
            changeCategoryTableHandler
        );
        form.removeEventListener("submit", __submitStoreHandler);
    };
});
