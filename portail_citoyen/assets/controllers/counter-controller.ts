import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override targets: string[] = ["counterMinLength", "counterMaxLength", "counter", "counterContainer", "parent"];
    static override values = {
        maxlength: Number,
        minlength: Number
    };

    declare readonly hasCounterMinLengthTarget: boolean;
    declare readonly counterMinLengthTarget: HTMLInputElement;
    declare readonly hasCounterMaxLengthTarget: boolean;
    declare readonly counterMaxLengthTarget: HTMLInputElement;
    declare readonly counterTarget: HTMLInputElement;
    declare readonly counterContainerTarget: HTMLInputElement;
    declare readonly parentTarget: HTMLInputElement;
    declare readonly maxlengthValue: number;
    declare readonly minlengthValue: number;

    override initialize(): void {
        this.initializeCounters();
        this.bind();
    }

    private initializeCounters(): void {
        this.counterTarget.textContent = `(${this.parentTarget.value.length}/${this.maxlengthValue})`;
        if (this.hasCounterMinLengthTarget) {
            this.counterMinLengthTarget.textContent = this.minlengthValue.toString();
        }
        if (this.hasCounterMaxLengthTarget) {
            this.counterMaxLengthTarget.textContent = this.maxlengthValue.toString();
        }
        this.setCounterColor();
    }

    private bind(): void {
        this.parentTarget.addEventListener("keydown", () => {
            this.counterTarget.textContent = `(${this.parentTarget.value.length}/${this.maxlengthValue})`;
            this.setCounterColor();
        });
    }

    private setCounterColor(): void {
        this.counterTarget.classList.toggle(
            "fr-text--red-marianne",
            this.parentTarget.value.length < this.minlengthValue
        );
        this.counterTarget.classList.toggle(
            "fr-text--high-green-emeraude",
            this.parentTarget.value.length >= this.minlengthValue
        );
    }
}
