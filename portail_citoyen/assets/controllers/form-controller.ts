import {EtaLabAutoCompleter} from "../scripts/etalab/etalab-auto-completer";
import {Controller} from "@hotwired/stimulus";
import {IntlTelInput} from "../scripts/intl-tel-input";

export default class extends Controller {
    public override connect(): void {
        const element: Element = this.element;

        if (element) {
            element.addEventListener("live:connect", (event: Event) => {
                (new EtaLabAutoCompleter()).bind();
                (new IntlTelInput()).bind();

                // Must be ignored because "detail" attribute does not exist on type EventTarget.
                // @ts-ignore
                const component = event.detail.component;

                if (component) {
                    component.on("render:finished", () => {
                        (new IntlTelInput()).bind();
                    });
                }
            });
        }
    }

    public removeFrenchTown(): void {
        const country: Element = this.element;

        if (country) {
            // Must be ignored because "value" property does not exist on Element type.
            // @ts-ignore
            const value = country.value;
            const frenchTown = 99100;

            if (value !== frenchTown) {
                // @ts-ignore
                const parent: Element = country.parentElement;
                // @ts-ignore
                const next: Element = parent.nextElementSibling;

                for (const child of next.children) {
                    if (child.classList.contains("french-town")) {
                        child.remove();
                    }
                }
            }
        }
    }

    public toUpperCase(): void {
        const element: Element = this.element;

        if (element) {
            // Must be ignored because "value" property does not exist on Element type.
            // @ts-ignore
            element.value = element.value.toUpperCase();
        }
    }
}
