import intlTelInput from "intl-tel-input";

export class IntlTelInput {
    private readonly FR_CODE: string = "FR";

    public bind(): void {
        document.querySelectorAll(".phone-intl").forEach((field: Element) => {
            this.bindElement(field);
        });
    }

    private bindElement(phoneInput: Element): void {
        const phoneInputGroup: Element | null = phoneInput.closest(".fr-input-group");

        if (phoneInputGroup) {
            const dialCodeInput: Element | null = phoneInputGroup.nextElementSibling;

            if (dialCodeInput) {
                const countryCodeInput: Element | null = dialCodeInput.nextElementSibling;

                // Must be ignored because "value" attribute does not exist on type EventTarget.
                // @ts-ignore
                const countryValue: string = countryCodeInput.value;

                if (phoneInput && countryCodeInput) {
                    const telInput: intlTelInput.Plugin = intlTelInput(phoneInput, {
                        preferredCountries: [this.FR_CODE],
                        separateDialCode: true,
                        initialCountry: countryValue ?? this.FR_CODE,
                    });
                    phoneInput.addEventListener("countrychange", function () {
                        // Must be ignored because "value" attribute does not exist on type EventTarget.
                        // @ts-ignore
                        dialCodeInput.value = telInput.getSelectedCountryData().dialCode;
                        dialCodeInput.dispatchEvent(new Event("change", {bubbles: true}));

                        // Must be ignored because "value" attribute does not exist on type EventTarget.
                        // @ts-ignore
                        countryCodeInput.value = telInput.getSelectedCountryData().iso2;
                        countryCodeInput.dispatchEvent(new Event("change", {bubbles: true}));
                    });
                }
            }
        }
    }
}
