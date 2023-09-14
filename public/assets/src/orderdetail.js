
var pusher = new Pusher(PUSHER_KEY, {
    authEndpoint: "/broadcasting/auth",
    auth: {
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    },
    cluster: PUSHER_CLUSTER,
});

document.addEventListener("DOMContentLoaded", () => {
    const transactionStatus = document.querySelector('#transaction_status')
    const transactionConfirm = document.querySelector('#transaction_confirm')

    var channel = pusher.subscribe("private-order." + auth.id);
    channel.bind("order-event", function ({ order }) {
        let newElement = "";
        transactionStatus.innerHTML = '';
        switch(order.transaction_status) {
            case 'settlement': 
                newElement = `<span class="badge text-bg-success transaction_status">
                                    ${order.transaction_status}
                                </span>`
                transactionStatus.insertAdjacentHTML('beforeend', newElement)
                transactionConfirm.textContent = formatTime(order.updated_at)
                break;
            case 'deny': 
                newElement = `<span class="badge text-bg-danger transaction_status">
                                ${order.transaction_status}
                            </span>`
                transactionStatus.insertAdjacentHTML('beforeend', newElement)
                transactionConfirm.textContent = formatTime(order.updated_at)
                break;
            case 'expire': 
                newElement = `<span class="badge text-bg-info transaction_status">
                                ${order.transaction_status}
                            </span>`
                transactionStatus.insertAdjacentHTML('beforeend', newElement)
                transactionConfirm.textContent = formatTime(order.updated_at)
                break;
            case 'cancel': 
                newElement = `<span class="badge text-bg-danger transaction_status">
                                ${order.transaction_status}
                            </span>`
                transactionStatus.insertAdjacentHTML('beforeend', newElement)
                transactionConfirm.textContent = formatTime(order.updated_at)
                break;
            default: 
                newElement = `<span class="badge text-bg-primary transaction_status">
                                    ${order.transaction_status}
                                </span>`
                transactionStatus.insertAdjacentHTML('beforeend', newElement)
                transactionConfirm.textContent = formatTime(order.updated_at)
                break;
        }
    });

    const ratingElement = document.querySelectorAll(".rating");
    ratingElement.forEach((item) => {
        raterJs({
            element: item,
            starSize: 20,
            rating: Number(item.getAttribute("data-rating")),
            readOnly: true,
            showToolTip: true,
            rateCallback: function rateCallback(rating, done) {
                this.setRating(rating);
                done();
            },
        });
    });
});
