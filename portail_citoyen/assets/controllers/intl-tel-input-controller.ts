// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-nocheck

import {Controller} from "@hotwired/stimulus";
import intlTelInput from "intl-tel-input";

export default class extends Controller {
    static override targets: string[] = ["number", "code", "country"];

    private telInput: intlTelInput.Plugin;

    public override connect(): void {
        const countryValue: string = this.countryTarget.value;

        this.telInput = intlTelInput(this.numberTarget, {
            preferredCountries: ["FR"],
            separateDialCode: true,
            initialCountry: countryValue ?? "FR",
            placeholderNumberType: this.numberTarget.getAttribute("data-placeholder-type")
        });

        this.numberTarget.addEventListener("countrychange", () => this.onCountryChange(this.telInput));
    }

    public trimByPattern({params: {pattern}}: {params: { pattern: string }}): void {
        this.numberTarget.value = this.numberTarget.value.replaceAll(new RegExp(pattern, "g"), "");

        // To force the formatOnDisplay option rendering
        this.telInput.setNumber(this.numberTarget.value);
    }

    private onCountryChange(telInput: intlTelInput.Plugin): void {
        this.codeTarget.value = telInput.getSelectedCountryData().dialCode;
        this.codeTarget.dispatchEvent(new Event("change", {bubbles: true}));

        this.countryTarget.value = telInput.getSelectedCountryData().iso2;
        this.countryTarget.dispatchEvent(new Event("change", {bubbles: true}));
    }
}
