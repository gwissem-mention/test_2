// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-nocheck

import {Controller} from "@hotwired/stimulus";
import intlTelInput from "intl-tel-input";

export default class extends Controller {
    static override targets: string[] = ["number", "code", "country"];
    public override connect(): void {
        const countryValue: string = this.countryTarget.value;

        const telInput: intlTelInput.Plugin = intlTelInput(this.numberTarget, {
            preferredCountries: ["FR"],
            separateDialCode: true,
            initialCountry: countryValue ?? "FR",
        });

        this.numberTarget.addEventListener("countrychange", () => this.onCountryChange(telInput));
    }

    public trimByPattern({params: {pattern}}: {params: { pattern: string }}): void {
        this.numberTarget.value = this.numberTarget.value.replaceAll(new RegExp(pattern, "g"), "");
    }

    private onCountryChange(telInput: intlTelInput.Plugin): void {
        this.codeTarget.value = telInput.getSelectedCountryData().dialCode;
        this.codeTarget.dispatchEvent(new Event("change", {bubbles: true}));

        this.countryTarget.value = telInput.getSelectedCountryData().iso2;
        this.countryTarget.dispatchEvent(new Event("change", {bubbles: true}));
    }
}
