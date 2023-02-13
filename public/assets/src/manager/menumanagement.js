const SEGMENT_URL = `${BASE_URL}manager/menu-managements/`;
const auth = "/manager";
const headers = {
    Accept: "application/json",
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": csrfToken,
};

async function manipulateSubMenu(results) {
    let menuSidebar = document.querySelector(".menu");
    let titleSidebar = document.querySelectorAll(".sidebar-title");
    let itemSidebar = document.querySelectorAll(".sidebar-item");

    titleSidebar.forEach((li, index) => {
        menuSidebar.removeChild(li);
    });
    itemSidebar.forEach((li, index) => {
        menuSidebar.removeChild(li);
    });

    await results.forEach((title, index) => {
        const liElement1 = document.createElement("li");
        liElement1.classList.add("sidebar-title");
        liElement1.textContent = title.label_title;
        menuSidebar.appendChild(liElement1);

        title.menus.forEach((menu, indexMenu) => {
            const liElement2 = document.createElement("li");
            const aElement1 = document.createElement("a");
            const iElement1 = document.createElement("i");
            const spanElement1 = document.createElement("span");

            liElement2.classList.add("sidebar-item");
            if (menu.submenus.length > 0) {
                liElement2.classList.add("has-sub");
            }
            if (menu.label_menu == "Menu Managements") {
                liElement2.classList.add("active");
            }
            aElement1.classList.add("sidebar-link");
            aElement1.setAttribute("href", `${auth}${menu.path}`);
            iElement1.classList.add("bi");
            iElement1.classList.add(`${menu.icon}`);
            spanElement1.textContent = menu.label_menu;

            liElement2.appendChild(aElement1);
            aElement1.appendChild(iElement1);
            aElement1.appendChild(spanElement1);
            menuSidebar.appendChild(liElement2);

            if (menu.submenus.length > 0) {
                const ulElement = document.createElement("ul");
                ulElement.classList.add("submenu");
                liElement2.appendChild(ulElement);

                menu.submenus.forEach((submenu, indexSubmenu) => {
                    const liElement3 = document.createElement("li");
                    const aElement2 = document.createElement("a");
                    liElement3.classList.add("submenu-item");
                    aElement2.setAttribute(
                        "href",
                        `${auth}${menu.path}${submenu.path}`
                    );
                    aElement2.textContent = submenu.label_submenu;
                    liElement3.appendChild(aElement2);
                    ulElement.appendChild(liElement3);
                });
            }
        });
    });
    const addSubMenu = document.querySelectorAll(
        ".sidebar-item.has-sub .sidebar-link"
    );
    addSubMenu.forEach((liElement2) => {
        liElement2.addEventListener("click", function (event) {
            event.preventDefault();
            const submenu = liElement2.parentElement.querySelector(".submenu");

            submenu.style.transition = "max-height 0.3s ease";
            if (submenu.classList.contains("active")) {
                submenu.style.maxHeight = "0";
                setTimeout(() => {
                    submenu.classList.remove("active");
                    submenu.style.maxHeight = "";
                }, 300);
            } else {
                submenu.classList.add("active");
                submenu.style.maxHeight = "0";
                setTimeout(() => {
                    const height = submenu.scrollHeight + "px";
                    submenu.style.maxHeight = height;
                }, 10);
            }
        });
    });
}

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

        await manipulateSubMenu(response.results);

        return successToast(response.message);
    } catch (error) {
        console.error(error);
        errorToast(error.message);
    }
};

const __handleChangeMenu = async (menu_id, role_value) => {
    try {
        const request = await fetch(`${SEGMENT_URL}menu`, {
            method: "POST",
            headers,
            body: JSON.stringify({ menu_id, role_value }),
        });

        const response = await request.json();
        if (response.status_code !== 200) {
            throw new Error("Something went wrong");
        }

        await manipulateSubMenu(response.results);

        return successToast(response.message);
    } catch (error) {
        errorToast(error.message);
    }
};

const __handleChangeSubMenu = async (submenu_id, role_value) => {
    try {
        const request = await fetch(`${SEGMENT_URL}submenu`, {
            method: "POST",
            headers,
            body: JSON.stringify({ submenu_id, role_value }),
        });

        const response = await request.json();
        if (response.status_code !== 200) {
            throw new Error("Something went wrong");
        }

        await manipulateSubMenu(response.results);

        return successToast(response.message);
    } catch (error) {
        errorToast(error.message);
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const handleChangeLabel = document.querySelectorAll(".label_menu");
    const handleChangeMenu = document.querySelectorAll(".menu_menu");
    const handleChangeSubMenu = document.querySelectorAll(".submenu");

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
    handleChangeSubMenu.forEach((submenu) => {
        submenu.addEventListener("change", (event) => {
            const submenu_id = event.target.getAttribute("data-id");
            const role_value = event.target.value;
            __handleChangeSubMenu(submenu_id, role_value);
        });
    });
});
