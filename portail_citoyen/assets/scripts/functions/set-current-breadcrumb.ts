/**
 * Sets current breadcrumb.
 */
export const setCurrentBreadcrumb = (breadcrumb: Element, currentBreadcrumbId: number): void => {
    const currentBreadcrumbItem = Array.from(breadcrumb.querySelectorAll(".fr-breadcrumb__link")).filter(breadcrumbItem => parseInt(String(breadcrumbItem.getAttribute("data-accordion"))) === currentBreadcrumbId)[0];

    if (currentBreadcrumbItem) {
        currentBreadcrumbItem.removeAttribute("href");
        currentBreadcrumbItem.setAttribute("aria-current", "true");
    }
};
