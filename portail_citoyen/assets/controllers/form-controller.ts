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

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public trimByPattern({params: {pattern}}): void {
        const element: Element = this.element;

        if (element) {
            // Must be ignored because "value" property does not exist on Element type.
            // @ts-ignore
            element.value = element.value.replaceAll(new RegExp(pattern, "g"), "");
        }
    }

    public sameAddress(): void {
        const checkboxRepresentedPerson: HTMLElement | null = document.getElementById("identity_representedPersonContactInformation_sameAddress");
        const checkboxCorporation: HTMLElement | null = document.getElementById("identity_corporation_sameAddress");
        const addressDeclarant: HTMLElement | null = document.getElementById("identity_contactInformation_frenchAddress_address");
        const frenchAddressRepresentedPerson: HTMLElement | null = document.getElementById("identity_representedPersonContactInformation_frenchAddress_address");
        const foreignAddressRepresentedPerson: HTMLElement | null = document.getElementById("identity_representedPersonContactInformation_foreignAddress");
        const frenchAddressCorporation: HTMLElement | null = document.getElementById("identity_corporation_frenchAddress_address");
        const foreignAddressCorporation: HTMLElement | null = document.getElementById("identity_corporation_foreignAddress");

        // Must be ignored because "checked" property does not exist on HTMLElement type.
        // @ts-ignore
        if (checkboxRepresentedPerson && checkboxRepresentedPerson.checked) {
            if (addressDeclarant && frenchAddressRepresentedPerson) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                frenchAddressRepresentedPerson.value = addressDeclarant.value;
                frenchAddressRepresentedPerson.setAttribute("disabled", "disabled");
            }

            if (addressDeclarant && foreignAddressRepresentedPerson) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                foreignAddressRepresentedPerson.value = addressDeclarant.value;
                foreignAddressRepresentedPerson.setAttribute("disabled", "disabled");
            }
        }

        // Must be ignored because "checked" property does not exist on HTMLElement type
        // @ts-ignore
        if (checkboxRepresentedPerson && !checkboxRepresentedPerson.checked) {
            if (frenchAddressRepresentedPerson) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                frenchAddressRepresentedPerson.value = "";
                frenchAddressRepresentedPerson.removeAttribute("disabled");
            }

            if (foreignAddressRepresentedPerson) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                foreignAddressRepresentedPerson.value = "";
                foreignAddressRepresentedPerson.removeAttribute("disabled");
            }
        }

        // Must be ignored because "checked" property does not exist on HTMLElement type.
        // @ts-ignore
        if (checkboxCorporation && checkboxCorporation.checked) {
            if (addressDeclarant && frenchAddressCorporation) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                frenchAddressCorporation.value = addressDeclarant.value;
                frenchAddressCorporation.setAttribute("disabled", "disabled");
            }

            if (addressDeclarant && foreignAddressCorporation) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                foreignAddressCorporation.value = addressDeclarant.value;
                foreignAddressCorporation.setAttribute("disabled", "disabled");
            }
        }

        // Must be ignored because "checked" property does not exist on HTMLElement type
        // @ts-ignore
        if (checkboxCorporation && !checkboxCorporation.checked) {
            if (frenchAddressCorporation) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                frenchAddressCorporation.value = "";
                frenchAddressCorporation.removeAttribute("disabled");
            }

            if (foreignAddressCorporation) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                foreignAddressCorporation.value = "";
                foreignAddressCorporation.removeAttribute("disabled");
            }
        }
    }
}
