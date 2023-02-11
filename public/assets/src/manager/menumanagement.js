const SEGMENT_URL = `${BASE_URL}manager/menu-managements/`;
const headers = {
    Accept: "application/json",
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": csrfToken,
};

const __handleChangeLabelMenu = async (label_id, role_value) => {
    try {
        const request = await fetch(`${SEGMENT_URL}label`, {
            method: "POST",
            body: JSON.stringify({ label_id, role_value }),
            headers,
        });
        const response = await request.json();
        if (response.status_code !== 200) {
            throw new Error("Something went wrong");
        }

        let menu = document.querySelector(".menu");
        let titleSidebar = document.querySelectorAll(".sidebar-title");
        let itemSidebar = document.querySelectorAll(".sidebar-item");
        titleSidebar.forEach((li, index) => {
            menu.removeChild(li);
        });
        itemSidebar.forEach((li, index) => {
            menu.removeChild(li);
        });

        response.results.map((title, index) => {
            const li = document.createElement("li");
            li.classList.add("sidebar-title");
            li.textContent = title.label_title;
            document.querySelector(".menu").appendChild(li);
        });
        return successToast(response.message);
    } catch (error) {
        errorToast(error.message);
    }
};

const __handleChangeMenu = async (menu_id, role_value) => {
    console.log(menu_id, role_value);
};

document.addEventListener("DOMContentLoaded", () => {
    const handleChangeLabel = document.querySelectorAll(".label_menu");
    const handleChangeMenu = document.querySelectorAll(".menu_menu");

    handleChangeLabel.forEach((labelMenu) => {
        labelMenu.addEventListener("change", (event) => {
            const label_id = event.target.getAttribute("data-id");
            const role_value = event.target.value;
            __handleChangeLabelMenu(label_id, role_value);
        });
    });
    handleChangeMenu.forEach((menu) => {
        menu.addEventListener("change", (event) => {
            const menu_id = event.target.getAttribute("data-id");
            const role_value = event.target.value;
            __handleChangeMenu(menu_id, role_value);
        });
    });
});
