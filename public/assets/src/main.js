const BASE_URL = "http://restaurant.test/";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const successToast = (message, duration = 3000) => {
    Toastify({
        text: `${message}`,
        duration,
        close: true,
        backgroundColor: "#53d13d",
    }).showToast();
};
const errorToast = (message, duration = 3000) => {
    Toastify({
        text: `${message}`,
        duration,
        close: true,
        backgroundColor: "#eb4f34",
    }).showToast();
};
