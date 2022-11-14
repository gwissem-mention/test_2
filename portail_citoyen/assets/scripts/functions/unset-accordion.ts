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
        // Must be ignored due to style attribute.
        // @ts-ignore
        icon.style.rotate = "0deg";
    });
};
