document.addEventListener("DOMContentLoaded", () => {
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
