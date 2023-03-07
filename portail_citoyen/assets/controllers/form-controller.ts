import {Controller} from "@hotwired/stimulus";
import TomSelect from "tom-select";

export default class extends Controller {
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

    public sameAddress(): void {
        const checkboxRepresentedPerson: HTMLElement | null = document.getElementById("identity_representedPersonContactInformation_sameAddress");
        const checkboxCorporation: HTMLElement | null = document.getElementById("identity_corporation_sameAddress");
        const frenchAddressDeclarant: HTMLElement | null = document.querySelector("[name=contact-information-address]");
        const frenchAddressRepresentedPerson: HTMLElement | null = document.querySelector("[name=represented-person-address]");
        const frenchAddressCorporation: HTMLElement | null = document.querySelector("[name=corporation-address]");

        // Must be ignored because "checked" property does not exist on HTMLElement type.
        // @ts-ignore
        if (checkboxRepresentedPerson && checkboxRepresentedPerson.checked) {
            if (frenchAddressDeclarant && frenchAddressRepresentedPerson) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                frenchAddressRepresentedPerson.value = frenchAddressDeclarant.value;
                frenchAddressRepresentedPerson.setAttribute("disabled", "disabled");
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
        }

        // Must be ignored because "checked" property does not exist on HTMLElement type.
        // @ts-ignore
        if (checkboxCorporation && checkboxCorporation.checked) {
            if (frenchAddressDeclarant && frenchAddressCorporation) {
                // Must be ignored because "value" property does not exist on HTMLElement type
                // @ts-ignore
                frenchAddressCorporation.value = frenchAddressDeclarant.value;
                frenchAddressCorporation.setAttribute("disabled", "disabled");
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
        }
    }
}
