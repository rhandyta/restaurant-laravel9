const imagesInput = document.querySelector("#images");

const formStore = document.querySelector("#formfood");

document.addEventListener("DOMContentLoaded", () => {
    async function storeHandler(event) {
        try {
            event.preventDefault();
            const form = document.querySelector("form");
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
                const errorMessages = Object.values(response.messages);
                errorMessages.forEach((errorItem) => {
                    throw new Error(errorItem[0]);
                });
            }
            successToast(response.message, 1000);
            return setTimeout(() => {
                window.location.reload();
            }, 1000);
        } catch (error) {
            errorToast(error, 3000);
        }
    }

    formStore.addEventListener("submit", storeHandler);

    return () => {
        formStore.removeEventListener("submit", storeHandler);
    };
});
