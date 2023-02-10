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
        response.results.map((title, index) => {
            let sidebar = ``;
            sidebar += `<li class="sidebar-title">${title.label_title}</li>`;
            document.querySelector(".menu").append(sidebar);
        });
        return successToast(response.message);
    } catch (error) {
        errorToast(error.message);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const handleChangeLabel = document.querySelectorAll(".label_menu");
    handleChangeLabel.forEach((labelMenu) => {
        labelMenu.addEventListener("change", (event) => {
            const label_id = event.target.getAttribute("data-id");
            const role_value = event.target.value;
            __handleChangeLabelMenu(label_id, role_value);
        });
    });
});
