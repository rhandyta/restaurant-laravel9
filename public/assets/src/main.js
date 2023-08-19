const BASE_URL = "http://kape.test";
const API_URL = "http://kape.test/api";
const PUSHER_KEY = "594aa5c6ec5db09db4f9";
const PUSHER_CLUSTER = "ap1";
const pathname = window.location.pathname;
const SEGMENT_URL = `${BASE_URL}${pathname}`;
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

const convertRupiah = (value) => {
    return value.toLocaleString("id-ID");
};

const formatTime = (value) => {
    const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    const date = new Date(value);
    return `${date.getDate()} ${month[date.getMonth()]} ${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}`;
}