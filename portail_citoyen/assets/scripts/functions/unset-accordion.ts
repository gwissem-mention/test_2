/**
 * Unsets all existing accordions.
 */
export const unsetAccordions = (): void => {
    document.querySelectorAll(".accordion__item__content").forEach((accordionContent: Element) => {
        // Must be ignored because of style attribute.
        // @ts-ignore
        accordionContent.style.display = "none";
    });

    document.querySelectorAll(".accordion__item__title span").forEach((icon: Element) => {
        icon.classList.remove("fr-icon-arrow-down-s-line");
        icon.classList.add("fr-icon-arrow-right-s-line");
    });
};
