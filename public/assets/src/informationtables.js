const categoryTableHandler = document.querySelector("#category_table_id");
const formAddInformationTable = document.querySelector(
    "#formaddinformationtable"
);

// ready
document.addEventListener("DOMContentLoaded", () => {
    const changeCategoryTableHandler = (event) => {
        let categoryStatus =
            event.target.selectedOptions[0].getAttribute("data-status");
        let options = Array.from(formAddInformationTable.elements[2].options);

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

    return () => {
        categoryTableHandler.removeEventListener(
            "change",
            changeCategoryTableHandler
        );
    };
});
