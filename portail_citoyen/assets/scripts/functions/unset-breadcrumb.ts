/**
 * Unsets breadcrumb.
 */
export const unsetBreadcrumb = (breadcrumb: Element): void => {
    breadcrumb.querySelectorAll(".fr-breadcrumb__link").forEach((breadcrumbItem: Element) => {
        breadcrumbItem.setAttribute("href", "#");
        breadcrumbItem.removeAttribute("aria-current");
    });
};
