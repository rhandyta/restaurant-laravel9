const BASE_URL = `${window.location.protocol}//${window.location.host}`;
const API_URL = `${window.location.protocol}//${window.location.host}/api`;
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
    const date = new Date(value);
    const dateOptions = { 
        year: 'numeric', 
        month: 'long', 
        day: '2-digit',
    };
    const timeOptions = {
        hour: '2-digit',
        minute: '2-digit',
    };
    const dateString = date.toLocaleDateString('id-ID', dateOptions);
    const timeString = date.toLocaleTimeString('id-ID', timeOptions);
    
    return `${dateString} ${timeString}`;
}

const formatDate = (value) => {
    const d = new Date(value);
    let day = `${d.getDate()}`;
    let month = `${d.getMonth() + 1}`;
    let year = d.getFullYear();

    if(day.length < 2) {
        day = '0' + day;
    }
    if(month.length < 2){
        month = '0' + month;
    }

    return [year,month,day].join('-')
}