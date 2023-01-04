export class BreadcrumbPersister {
    public setCurrentBreadcrumb(breadcrumb: HTMLElement | null, currentBreadcrumbId: number): void {
        if (breadcrumb) {
            const currentBreadcrumbItem: Element | undefined = Array.from(breadcrumb.querySelectorAll(".fr-breadcrumb__link")).filter(breadcrumbItem => parseInt(String(breadcrumbItem.getAttribute("data-accordion"))) === currentBreadcrumbId)[0];

            if (currentBreadcrumbItem) {
                const locked: string | null = String(currentBreadcrumbItem.getAttribute("data-locked"));

                if (locked && locked === "false") {
                    this.unsetBreadcrumb(breadcrumb);

                    currentBreadcrumbItem.removeAttribute("href");
                    currentBreadcrumbItem.setAttribute("aria-current", "true");
                }
            }
        }
    }

    public unsetBreadcrumb(breadcrumb: Element): void {
        breadcrumb.querySelectorAll(".fr-breadcrumb__link").forEach((breadcrumbItem: Element) => {
            breadcrumbItem.setAttribute("href", "#");
            breadcrumbItem.removeAttribute("aria-current");
        });
    }
}
