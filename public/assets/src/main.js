const BASE_URL = "http://restaurant.test/";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const successToast = (message) => {
    Toastify({
        text: `${message}`,
        duration: 3000,
        close: true,
        backgroundColor: "#53d13d",
    }).showToast();
};
const errorToast = (message) => {
    Toastify({
        text: `${message}`,
        duration: 3000,
        close: true,
        backgroundColor: "#eb4f34",
    }).showToast();
};
