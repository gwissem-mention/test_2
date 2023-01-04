import {EtaLabElementState} from "./etalab-element-state";

export class EtaLabAutoCompleter {
    private readonly etaLabElements: Map<string, EtaLabElementState>;
    private readonly SUGGESTIONS_LIMIT: number = 5;
    private readonly SUGGESTIONS_START_AT_CHARS: number = 5;

    constructor() {
        this.etaLabElements = new Map<string, EtaLabElementState>();
    }

    public bind(): void {
        document.querySelectorAll(".etalab").forEach((field: Element) => {
            this.bindElement(field);
        });
    }

    private bindElement(field: Element): void {
        this.setElementAttributes(field);

        field.addEventListener("keyup", (event: Event) => {
            this.onKeyUpEvent(field, event);
        });
    }

    private setElementAttributes(field: Element): void {
        field.setAttribute("autocomplete", "off");
        field.setAttribute("list", `datalist-${field.id}`);
    }

    private onKeyUpEvent(field: Element, event: Event): void {
        if (event && event.target) {
            // Must be ignored because property "value" does not exist on type EventTarget.
            // @ts-ignore
            const query = event.target.value;

            if (query.length >= this.SUGGESTIONS_START_AT_CHARS) {
                // Must be ignored because "value" attribute does not exist on type EventTarget.
                // @ts-ignore
                fetch(`https://api-adresse.data.gouv.fr/search/?q=${query}&limit=${this.SUGGESTIONS_LIMIT}`)
                    .then((response: Response) => response.json())
                    .then((addresses) => {
                        this.render(field, event.target, addresses.features);
                    });
            }
        }
    }

    private render(field: Element, target: EventTarget | null, addresses: any[]): void {
        const etaLabElementState: EtaLabElementState | undefined = (this.etaLabElements.has(field.id)) ? this.etaLabElements.get(field.id) : new EtaLabElementState();

        if (etaLabElementState) {
            if (!etaLabElementState.renderedResultContainer) {
                const renderedResultContainer: HTMLUListElement | null = document.createElement("ul");
                renderedResultContainer.classList.add("list--addresses");
                etaLabElementState.renderedResultContainer = renderedResultContainer;
            }

            this.clearAddresses(etaLabElementState);
            this.populateAdresses(etaLabElementState, addresses);

            const fieldParent: ParentNode | null = field.parentNode;

            if (fieldParent) {
                this.clearFieldParent(fieldParent);
                this.renderFieldParent(fieldParent, etaLabElementState);
            }

            if (!this.etaLabElements.has(field.id)) {
                this.etaLabElements.set(field.id, etaLabElementState);
            }
        }
    }

    private clearAddresses(etaLabElementState: EtaLabElementState): void {
        if (etaLabElementState.renderedResultContainer) {
            for (const child of etaLabElementState.renderedResultContainer.children) {
                child.remove();
            }
        }
    }

    private populateAdresses(etaLabElementState: EtaLabElementState, addresses: any[]): void {
        for (const address of addresses) {
            const item: HTMLLIElement = document.createElement("li");
            const value: string = this.formatAddress(address);

            if (item && value) {
                item.classList.add("list--addresses__item");
                item.setAttribute("id", address.properties.id);
                item.innerText = value;

                if (etaLabElementState.renderedResultContainer) {
                    etaLabElementState.renderedResultContainer.appendChild(item);
                }
            }
        }
    }

    private formatAddress(address: any): string {
        return `${(address.properties.name) ? address.properties.name : ""} ${(address.properties.district) ? address.properties.district : ""} ${(address.properties.postcode) ? address.properties.postcode : ""} ${(address.properties.city) ? address.properties.city : ""}`;
    }

    private clearFieldParent(fieldParent: ParentNode): void {
        for (const child of fieldParent.children) {
            if (child.classList.contains("list--addresses")) {
                child.remove();
            }
        }
    }

    private renderFieldParent(fieldParent: ParentNode, etaLabElementState: EtaLabElementState): void {
        if (etaLabElementState.renderedResultContainer) {
            fieldParent.appendChild(etaLabElementState.renderedResultContainer);
        }
    }
}
