import {EtaLabAutoCompleter} from "../scripts/etalab/etalab-auto-completer";
import {Controller} from "@hotwired/stimulus";
import {IntlTelInput} from "../scripts/intl-tel-input";
import TomSelect from "tom-select";

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

    public changeJobUrl(): void {
        const element: Element = this.element;
        const parent: HTMLElement | null = element.closest(".fr-py-1w");

        if (parent) {
            [...parent.querySelectorAll(".job:not(.ts-wrapper)")].forEach((element) => {
                // Must be ignored because "tomselect" attribute does not exist on type Element.
                // @ts-ignore
                const tomselect: TomSelect | null = element.tomselect;

                // Must be ignored because "value" attribute does not exist on type Element.
                // @ts-ignore
                const urlInput: string | null = element.getAttribute(`data-url-civility-${this.element.value}`);

                if (tomselect && urlInput) {
                    tomselect.settings.firstUrl = query => urlInput + "?query=" + encodeURIComponent(query);
                    tomselect.clear();
                    tomselect.clearOptions();
                    tomselect.load("#");
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
                frenchAddressRepresentedPerson.dispatchEvent(new Event("change", {bubbles: true}));
            }

            if (addressDeclarant && foreignAddressRepresentedPerson) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                foreignAddressRepresentedPerson.value = addressDeclarant.value;
                foreignAddressRepresentedPerson.setAttribute("disabled", "disabled");
                foreignAddressRepresentedPerson.dispatchEvent(new Event("change", {bubbles: true}));
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
                frenchAddressRepresentedPerson.dispatchEvent(new Event("change", {bubbles: true}));
            }

            if (foreignAddressRepresentedPerson) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                foreignAddressRepresentedPerson.value = "";
                foreignAddressRepresentedPerson.removeAttribute("disabled");
                foreignAddressRepresentedPerson.dispatchEvent(new Event("change", {bubbles: true}));
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
                frenchAddressCorporation.dispatchEvent(new Event("change", {bubbles: true}));
            }

            if (addressDeclarant && foreignAddressCorporation) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                foreignAddressCorporation.value = addressDeclarant.value;
                foreignAddressCorporation.setAttribute("disabled", "disabled");
                foreignAddressCorporation.dispatchEvent(new Event("change", {bubbles: true}));
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
                frenchAddressCorporation.dispatchEvent(new Event("change", {bubbles: true}));
            }

            if (foreignAddressCorporation) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                foreignAddressCorporation.value = "";
                foreignAddressCorporation.removeAttribute("disabled");
                foreignAddressCorporation.dispatchEvent(new Event("change", {bubbles: true}));
            }
        }
    }
}
