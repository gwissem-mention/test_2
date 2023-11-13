// eslint-disable-next-line @typescript-eslint/ban-ts-comment
// @ts-nocheck

import {Controller} from "@hotwired/stimulus";
import intlTelInput from "intl-tel-input";

export default class extends Controller {
    static override targets: string[] = ["number", "code", "country"];

    static values = {
        hasErrors: Boolean,
        formId: String
    };

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
        this.handleErrorsChange(this.hasErrorsValue);
    }

    public trimByPattern({params: {pattern}}: {params: { pattern: string }}): void {
        this.numberTarget.value = this.numberTarget.value.replaceAll(new RegExp(pattern, "g"), "");

        // To force the formatOnDisplay option rendering
        this.telInput.setNumber(this.numberTarget.value);
    }

    public hasErrorsValueChanged(newValue: boolean): void {
        this.handleErrorsChange(newValue);
    }

    private onCountryChange(telInput: intlTelInput.Plugin): void {
        this.codeTarget.value = telInput.getSelectedCountryData().dialCode;
        this.codeTarget.dispatchEvent(new Event("change", {bubbles: true}));

        this.countryTarget.value = telInput.getSelectedCountryData().iso2;
        this.countryTarget.dispatchEvent(new Event("change", {bubbles: true}));

        this.handleErrorsChange(this.hasErrorsValue);
    }

    private handleErrorsChange(hasErrors: boolean): void {
        const ariaDescId = "form-errors-" + this.formIdValue;
        const telInputElement = this.telInput?.telInput as HTMLElement | undefined;

        if (hasErrors) {
            telInputElement?.setAttribute("aria-describedby", ariaDescId);
        } else {
            if (telInputElement?.hasAttribute("aria-describedby")) {
                telInputElement.removeAttribute("aria-describedby");
            }
        }
    }
}
