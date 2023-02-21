const imagesInput = document.querySelectorAll("#images");
const progress = document.querySelector("#progress");
const progressLabel = document.querySelector("#progressbar");
const formStore = document.querySelector("#formfood");
const btnEdit = document.querySelectorAll(".btn-edit");
const formEdit = document.querySelector("#formeditfood");
const imagePreview = document.querySelector("#imagepreview");

const __progressUpload = (formData) => {
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
            throw new Error("something went wrong");
        }
        formEdit.elements[0].value = response.results.food_category_id;
        formEdit.elements[1].value = response.results.food_name;
        formEdit.elements[2].value = Number(response.results.price);
        formEdit.elements[4].value = response.results.food_description;

        for (let i = 0; i < imagePreview.childNodes.length + 1; i++) {
            imagePreview.childNodes.forEach((item) => item.remove());
        }
        response.results.foodimages.forEach((image) => {
            let elementImg = document.createElement("img");
            elementImg.setAttribute("width", "70");
            elementImg.setAttribute("height", "70");
            elementImg.setAttribute("style", "margin: 2px");
            elementImg.setAttribute("src", image.image_url);
            imagePreview.appendChild(elementImg);
        });
    } catch (error) {
        return errorToast(error);
    }
};

const __editSubmitHandler = async (id, event) => {
    try {
        event.preventDefault();
        const formData = new FormData(formEdit);
        const request = await fetch(`${SEGMENT_URL}/${id}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });
        const response = await request.json();
        if (response.status_code != 200) {
            const errorMessages = Object.values(response.messages);
            errorMessages.forEach((itemError) => {
                throw new Error(itemError[0]);
            });
        }
        successToast(response.message, 1000);
        return setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (error) {
        return errorToast(error);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    async function storeHandler(event) {
        try {
            event.preventDefault();
            const form = document.querySelector("form");
            const formData = new FormData(form);
            const response = await __progressUpload(formData);
            const parsedResponse = JSON.parse(response);
            console.log(progress);
            progress.style.display = "none";
            progressLabel.style.width = `0%`;
            progressLabel.innerHTML = `0%`;
            successToast(parsedResponse.message, 1000);
            return setTimeout(() => {
                window.location.reload();
            }, 1000);
        } catch (error) {
            progress.style.display = "none";
            progressLabel.style.width = `0%`;
            progressLabel.innerHTML = `0%`;
            errorToast(error, 3000);
        }
    }

    function clickEditHandler(event) {
        let id = event.target.getAttribute("data-id");
        __getDataById(id);
        const editSubmitHandler = __editSubmitHandler.bind(null, id);
        formEdit.addEventListener("submit", editSubmitHandler);
        formEdit.addEventListener("submit", function removeSubmitListener() {
            formEdit.removeEventListener("submit", editSubmitHandler);
            formEdit.removeEventListener("submit", removeSubmitListener);
        });
    }

    // ---------------------------------------------------------------------------------------------------

    formStore.addEventListener("submit", storeHandler);

    btnEdit.forEach((selectId) => {
        selectId.addEventListener("click", clickEditHandler);
    });

    return () => {
        formStore.removeEventListener("submit", storeHandler);

        btnEdit.forEach((selectId) => {
            selectId.removeEventListener("click", clickEditHandler);
        });
    };
});
