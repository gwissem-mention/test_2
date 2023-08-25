import {Controller} from "@hotwired/stimulus";
export default class extends Controller {
    public removeFrenchTown(): void {
        const country: Element = this.element;

        if (country) {
            // Must be ignored because "value" property does not exist on Element type.
            // @ts-ignore
            const value = country.value;
            const frenchTown = 99160;

            if (value && value !== frenchTown) {
                // @ts-ignore
                const parent: Element = country.closest(".fr-form-group");
                // @ts-ignore
                const next: Element = parent.nextElementSibling;
                [...next.querySelectorAll(".french-town")].forEach((element) => {
                    element.remove();
                });
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
