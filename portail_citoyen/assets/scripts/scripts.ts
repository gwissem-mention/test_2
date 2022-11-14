import {persistCurrentAccordion} from "./functions/persist-current-accordion";
import {unsetAccordions} from "./functions/unset-accordion";
import {setCurrentAccordion} from "./functions/set-current-accordion";
import {unsetBreadcrumb} from "./functions/unset-breadcrumb";
import {setCurrentBreadcrumb} from "./functions/set-current-breadcrumb";
import {getPersistedCurrentAccordion} from "./functions/get-persisted-accordion";

document.addEventListener("DOMContentLoaded", () => {
    const complaintsBreadcrumb: HTMLElement | null = document.getElementById("complaintsBreadcrumb");

    // Initializes breadcrumb and accordion with persisted data at page load.
    const persistedAccordionId: number = parseInt(String(getPersistedCurrentAccordion()));

    if (persistedAccordionId) {
        persistCurrentAccordion(persistedAccordionId);
        unsetAccordions();
        setCurrentAccordion(persistedAccordionId);

        if (complaintsBreadcrumb) {
            unsetBreadcrumb(complaintsBreadcrumb);
            setCurrentBreadcrumb(complaintsBreadcrumb, persistedAccordionId);
        }
    }

    // Updates breadcrumb when accordion title is clicked.
    document.querySelectorAll(".accordion__item__title").forEach((accordionTitle: Element) => {
        accordionTitle.addEventListener("click", () => {
            const currentAccordionId: number = parseInt(String(accordionTitle.getAttribute("data-accordion-id")));

            if (currentAccordionId) {
                persistCurrentAccordion(currentAccordionId);
                unsetAccordions();
                setCurrentAccordion(currentAccordionId);

                if (complaintsBreadcrumb) {
                    unsetBreadcrumb(complaintsBreadcrumb);
                    setCurrentBreadcrumb(complaintsBreadcrumb, currentAccordionId);
                }
            }
        });
    });

    // Updates accordion when breadcrumb item is clicked.
    if (complaintsBreadcrumb) {
        complaintsBreadcrumb.querySelectorAll(".fr-breadcrumb__link").forEach((breadcrumbItem: Element) => {
            breadcrumbItem.addEventListener("click", (e) => {
                e.preventDefault();

                const currentAccordionId: number = parseInt(String(breadcrumbItem.getAttribute("data-accordion")));

                if (currentAccordionId) {
                    persistCurrentAccordion(currentAccordionId);
                    unsetAccordions();
                    setCurrentAccordion(currentAccordionId);

                    if (complaintsBreadcrumb) {
                        unsetBreadcrumb(complaintsBreadcrumb);
                        setCurrentBreadcrumb(complaintsBreadcrumb, currentAccordionId);
                    }
                }
            });
        });
    }
});
