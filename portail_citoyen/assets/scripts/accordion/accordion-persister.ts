export class AccordionPersister {
    public getPersistedCurrentAccordion(): string | null {
        return localStorage.getItem("currentAccordionId");
    }

    public persistCurrentAccordion(currentAccordionId: number): void {
        localStorage.setItem("currentAccordionId", String(currentAccordionId));
    }

    public setCurrentAccordion(accordionId: number): void {
        const currentAccordionTitle: Element | undefined = Array.from(document.querySelectorAll(".accordion__item__title")).filter(accordionItem => parseInt(String(accordionItem.getAttribute("data-accordion-id"))) === accordionId)[0];

        if (currentAccordionTitle && currentAccordionTitle.parentElement) {
            const locked: string | null = String(currentAccordionTitle.getAttribute("data-locked"));

            if (locked && locked === "false") {
                this.unsetAccordions();

                currentAccordionTitle.parentElement.querySelectorAll(".accordion__item__content").forEach((accordionContent: Element) => {
                    // Must be ignored due to style attribute.
                    // @ts-ignore
                    accordionContent.style.display = "block";
                });

                currentAccordionTitle.querySelectorAll("span").forEach((icon: Element) => {
                    icon.classList.remove("fr-icon-arrow-right-s-line");
                    icon.classList.add("fr-icon-arrow-down-s-line");
                    icon.setAttribute("aria-hidden", "false");
                });
            }
        }
    }

    public unsetAccordions(): void {
        document.querySelectorAll(".accordion__item__content").forEach((accordionContent: Element) => {
            // Must be ignored because of style attribute.
            // @ts-ignore
            accordionContent.style.display = "none";
        });

        document.querySelectorAll(".accordion__item__title span").forEach((icon: Element) => {
            icon.classList.remove("fr-icon-arrow-down-s-line");
            icon.classList.add("fr-icon-arrow-right-s-line");
            icon.setAttribute("aria-hidden", "true");
        });
    }
}
