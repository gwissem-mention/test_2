import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override targets: string[] = ["counterMinLength", "counterMaxLength", "counter", "counterContainer", "parent"];
    static override values = {
        maxlength: Number,
        minlength: Number
    };

    declare readonly counterMinLengthTarget: HTMLInputElement;
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
        this.counterTarget.textContent = `(0/${this.maxlengthValue})`;
        this.counterMinLengthTarget.textContent = this.minlengthValue.toString();
        this.counterMaxLengthTarget.textContent = this.maxlengthValue.toString();
    }

    private bind(): void {
        this.parentTarget.addEventListener("keydown", () => {
            this.counterTarget.textContent = `(${this.parentTarget.value.length}/${this.maxlengthValue})`;
            this.counterContainerTarget.classList.toggle(
                "fr-text--red-marianne",
                this.parentTarget.value.length < this.minlengthValue
            );
            this.counterContainerTarget.classList.toggle(
                "fr-text--high-green-emeraude",
                this.parentTarget.value.length >= this.minlengthValue
            );
        });
    }
}
