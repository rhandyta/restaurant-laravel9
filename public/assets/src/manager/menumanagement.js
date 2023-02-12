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

        let menuSidebar = document.querySelector(".menu");
        let titleSidebar = document.querySelectorAll(".sidebar-title");
        let itemSidebar = document.querySelectorAll(".sidebar-item");

        titleSidebar.forEach((li, index) => {
            menuSidebar.removeChild(li);
        });
        itemSidebar.forEach((li, index) => {
            menuSidebar.removeChild(li);
        });

        await response.results.forEach((title, index) => {
            const liElement1 = document.createElement("li");
            const liElement2 = document.createElement("li");
            const aElement1 = document.createElement("a");
            const iElement1 = document.createElement("i");
            const spanElement1 = document.createElement("span");
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

                aElement1.classList.add("sidebar-link");
                aElement1.setAttribute("href", `${menu.path}`);
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
                        aElement2.setAttribute("href", `${submenu.path}`);
                        aElement2.textContent = submenu.label_submenu;
                        liElement3.appendChild(aElement2);
                        ulElement.appendChild(liElement3);
                    });
                }
            });
        });

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

        let menuSidebar = document.querySelector(".menu");
        let titleSidebar = document.querySelectorAll(".sidebar-title");
        let itemSidebar = document.querySelectorAll(".sidebar-item");

        titleSidebar.forEach((li, index) => {
            menuSidebar.removeChild(li);
        });
        itemSidebar.forEach((li, index) => {
            menuSidebar.removeChild(li);
        });

        await response.results.forEach((title, index) => {
            const liElement1 = document.createElement("li");
            const liElement2 = document.createElement("li");
            const aElement1 = document.createElement("a");
            const iElement1 = document.createElement("i");
            const spanElement1 = document.createElement("span");
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

                aElement1.classList.add("sidebar-link");
                aElement1.setAttribute("href", `${menu.path}`);
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
                        aElement2.setAttribute("href", `${submenu.path}`);
                        aElement2.textContent = submenu.label_submenu;
                        liElement3.appendChild(aElement2);
                        ulElement.appendChild(liElement3);
                    });
                }
            });
        });

        return successToast(response.message);
    } catch (error) {
        errorToast(error.message);
    }
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
