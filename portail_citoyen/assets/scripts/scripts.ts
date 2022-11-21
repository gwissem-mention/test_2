import {setCurrentAccordion} from "./functions/set-current-accordion";
import {setCurrentBreadcrumb} from "./functions/set-current-breadcrumb";
import {getPersistedCurrentAccordion} from "./functions/get-persisted-accordion";
import {persistCurrentAccordion} from "./functions/persist-current-accordion";

document.addEventListener("DOMContentLoaded", () => {
    const complaintsBreadcrumb: HTMLElement | null = document.getElementById("complaintsBreadcrumb");
    const persistedAccordionId: number = (getPersistedCurrentAccordion()) ? parseInt(String(getPersistedCurrentAccordion())) : 1;

    // Initializes breadcrumb and accordion with persisted data at page load.
    if (persistedAccordionId) {
        persistCurrentAccordion(persistedAccordionId);
        setCurrentAccordion(persistedAccordionId);

        if (complaintsBreadcrumb) {
            setCurrentBreadcrumb(complaintsBreadcrumb, persistedAccordionId);
        }
    }

    // Updates breadcrumb when accordion title is clicked.
    document.querySelectorAll(".accordion__item__title").forEach((accordionTitle: Element) => {
        const currentAccordionId: number = parseInt(String(accordionTitle.getAttribute("data-accordion-id")));

        accordionTitle.setAttribute("data-locked", String(!(persistedAccordionId >= currentAccordionId)));
        accordionTitle.addEventListener("click", () => {
            if (currentAccordionId) {
                setCurrentAccordion(currentAccordionId);

                if (complaintsBreadcrumb) {
                    setCurrentBreadcrumb(complaintsBreadcrumb, currentAccordionId);
                }
            }
        });
    });

    // Updates accordion when breadcrumb item is clicked.
    if (complaintsBreadcrumb) {
        complaintsBreadcrumb.querySelectorAll(".fr-breadcrumb__link").forEach((breadcrumbItem: Element) => {
            const currentAccordionId: number = parseInt(String(breadcrumbItem.getAttribute("data-accordion")));

            breadcrumbItem.setAttribute("data-locked", String(!(persistedAccordionId >= currentAccordionId)));
            breadcrumbItem.addEventListener("click", (event: Event) => {
                event.preventDefault();

                if (currentAccordionId) {
                    setCurrentAccordion(currentAccordionId);

                    if (complaintsBreadcrumb) {
                        setCurrentBreadcrumb(complaintsBreadcrumb, currentAccordionId);
                    }
                }
            });
        });
    }

    // Updates accordion and breadcrumb lock status when form is submitted.
    document.querySelectorAll("form").forEach((form: HTMLFormElement) => {
        form.addEventListener("submit", () => {
            if (form.checkValidity()) {
                form.querySelectorAll("button").forEach((button: Element) => {
                    const nextAccordionId: number = parseInt(String(button.getAttribute("data-next-accordion")));

                    if (nextAccordionId) {
                        const nextAccordionTitle: Element | undefined = Array.from(document.querySelectorAll(".accordion__item__title")).filter(accordionItem => parseInt(String(accordionItem.getAttribute("data-accordion-id"))) === nextAccordionId)[0];

                        if (nextAccordionTitle) {
                            const accordionLocked = String(nextAccordionTitle.getAttribute("data-locked"));

                            if (accordionLocked === "true") {
                                nextAccordionTitle.setAttribute("data-locked", "false");
                            }

                            setCurrentAccordion(nextAccordionId);
                            persistCurrentAccordion(nextAccordionId);

                            if (complaintsBreadcrumb) {
                                const nextBreadcrumb: Element | undefined = Array.from(complaintsBreadcrumb.querySelectorAll(".fr-breadcrumb__link")).filter(breadcrumbItem => parseInt(String(breadcrumbItem.getAttribute("data-accordion"))) === nextAccordionId)[0];

                                if (nextBreadcrumb) {
                                    const breadcrumbLocked = String(nextBreadcrumb.getAttribute("data-locked"));

                                    if (breadcrumbLocked === "true") {
                                        nextBreadcrumb.setAttribute("data-locked", "false");
                                    }

                                    setCurrentBreadcrumb(complaintsBreadcrumb, nextAccordionId);
                                }
                            }
                        }
                    }
                });
            }
        });
    });
});
