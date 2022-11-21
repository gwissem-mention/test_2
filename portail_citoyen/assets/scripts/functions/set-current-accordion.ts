import {unsetAccordions} from "./unset-accordion";

/**
 * Sets current accordion element.
 */
export const setCurrentAccordion = (accordionId: number): void => {
    const currentAccordionTitle = Array.from(document.querySelectorAll(".accordion__item__title")).filter(accordionItem => parseInt(String(accordionItem.getAttribute("data-accordion-id"))) === accordionId)[0];

    if (currentAccordionTitle && currentAccordionTitle.parentElement) {
        const locked = String(currentAccordionTitle.getAttribute("data-locked"));

        if (locked && locked === "false") {
            unsetAccordions();

            currentAccordionTitle.parentElement.querySelectorAll(".accordion__item__content").forEach((accordionContent: Element) => {
                // Must be ignored due to style attribute.
                // @ts-ignore
                accordionContent.style.display = "block";
            });

            currentAccordionTitle.querySelectorAll("span").forEach((icon: Element) => {
                icon.classList.remove("fr-icon-arrow-right-s-line");
                icon.classList.add("fr-icon-arrow-down-s-line");
            });
        }
    }
};
