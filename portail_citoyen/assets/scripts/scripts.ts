import {AccordionPersister} from "./accordion/accordion-persister";
import {BreadcrumbPersister} from "./breadcrumb/breadcrumb-persister";

document.addEventListener("DOMContentLoaded", () => {
    const accordionPersister: AccordionPersister = new AccordionPersister();
    const breadcrumbPersister: BreadcrumbPersister = new BreadcrumbPersister();
    const complaintsBreadcrumb: HTMLElement | null = document.getElementById("complaintsBreadcrumb");
    const persistedAccordionId: number = (accordionPersister.getPersistedCurrentAccordion()) ? parseInt(String(accordionPersister.getPersistedCurrentAccordion())) : 1;

    // Initializes breadcrumb and accordion with persisted data at page load.
    accordionPersister.persistCurrentAccordion(1);
    accordionPersister.setCurrentAccordion(1);

    if (complaintsBreadcrumb) {
        breadcrumbPersister.setCurrentBreadcrumb(complaintsBreadcrumb, 1);
    }

    // Updates breadcrumb when accordion title is clicked.
    document.querySelectorAll(".accordion__item__title").forEach((accordionTitle: Element) => {
        const currentAccordionId: number = parseInt(String(accordionTitle.getAttribute("data-accordion-id")));

        accordionTitle.setAttribute("data-locked", String(!(persistedAccordionId >= currentAccordionId)));
        accordionTitle.addEventListener("click", () => {
            if (currentAccordionId) {
                accordionPersister.setCurrentAccordion(currentAccordionId);

                if (complaintsBreadcrumb) {
                    breadcrumbPersister.setCurrentBreadcrumb(complaintsBreadcrumb, currentAccordionId);
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
                    accordionPersister.setCurrentAccordion(currentAccordionId);

                    if (complaintsBreadcrumb) {
                        breadcrumbPersister.setCurrentBreadcrumb(complaintsBreadcrumb, currentAccordionId);
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

                            accordionPersister.setCurrentAccordion(nextAccordionId);
                            accordionPersister.persistCurrentAccordion(nextAccordionId);

                            if (complaintsBreadcrumb) {
                                const nextBreadcrumb: Element | undefined = Array.from(complaintsBreadcrumb.querySelectorAll(".fr-breadcrumb__link")).filter(breadcrumbItem => parseInt(String(breadcrumbItem.getAttribute("data-accordion"))) === nextAccordionId)[0];

                                if (nextBreadcrumb) {
                                    const breadcrumbLocked = String(nextBreadcrumb.getAttribute("data-locked"));

                                    if (breadcrumbLocked === "true") {
                                        nextBreadcrumb.setAttribute("data-locked", "false");
                                    }

                                    breadcrumbPersister.setCurrentBreadcrumb(complaintsBreadcrumb, nextAccordionId);
                                }
                            }
                        }
                    }
                });
            }
        });
    });

    // Updates current accordion and breadcrumb when update button is clicked and redirects user to previous page.
    document.querySelectorAll(".btn--update").forEach((button: Element) => {
        const newAccordionId: number = parseInt(String(button.getAttribute("data-previous-accordion-id")));
        const previousPath: string | null = button.getAttribute("href");

        if (newAccordionId && previousPath) {
            button.addEventListener("click", () => {
                accordionPersister.persistCurrentAccordion(newAccordionId);
                breadcrumbPersister.setCurrentBreadcrumb(complaintsBreadcrumb, newAccordionId);
                window.location.href = previousPath;
            });
        }
    });
});
