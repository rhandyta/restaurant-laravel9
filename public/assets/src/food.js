const imagesInput = document.querySelectorAll("#images");
const progress = document.querySelector("#progress");
const progressLabel = document.querySelector("#progressbar");
const formStore = document.querySelector("#formfood");

document.addEventListener("DOMContentLoaded", () => {
    function progressUpload(formData) {
        progress.style.display = "block";

        return new Promise(function (resolve, reject) {
            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener("progress", (event) => {
                let percentCompleted = Math.round(
                    (event.loaded / event.total) * 100
                );
                progressLabel.style.width = `${percentCompleted}%`;
                progressLabel.innerHTML = `${percentCompleted}%`;
            });

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        resolve(xhr.responseText);
                    } else {
                        const parsedResponse = JSON.parse(xhr.responseText);
                        if (parsedResponse.status_code != 201) {
                            const errorMessages = Object.values(
                                parsedResponse.messages
                            );
                            errorMessages.forEach((errorItem) => {
                                reject(errorItem[0]);
                            });
                        }
                    }
                }
            };

            xhr.open("POST", `${SEGMENT_URL}`);
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
            xhr.send(formData);
        });
    }

    async function storeHandler(event) {
        try {
            event.preventDefault();
            const form = document.querySelector("form");
            const formData = new FormData(form);
            const response = await progressUpload(formData);
            const parsedResponse = JSON.parse(response);
            successToast(parsedResponse.message, 1000);
            return setTimeout(() => {
                window.location.reload();
            }, 1000);
        } catch (error) {
            console.error(error);
            errorToast(error, 3000);
        }
    }

    formStore.addEventListener("submit", storeHandler);

    return () => {
        formStore.removeEventListener("submit", storeHandler);
    };
});
