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
    declare readonly hasParentTarget: boolean;
    declare readonly parentTarget: HTMLInputElement;
    declare readonly maxlengthValue: number;
    declare readonly minlengthValue: number;

    override initialize(): void {
        this.initializeCounters();
    }

    override connect() {
        this.parentTarget.addEventListener("input", this.updateCount.bind(this));
        this.updateCount();
    }

    override disconnect() {
        if (this.hasParentTarget) {
            this.parentTarget.removeEventListener("input", this.updateCount.bind(this));
        }
    }

    private initializeCounters(): void {
        this.counterTarget.textContent = `(${this.parentTarget.value.length}/${this.maxlengthValue})`;
        if (this.hasCounterMinLengthTarget) {
            this.counterMinLengthTarget.textContent = this.minlengthValue.toString();
        }
        if (this.hasCounterMaxLengthTarget) {
            this.counterMaxLengthTarget.textContent = this.maxlengthValue.toString();
        }
    }

    private updateCount(): void {
        if (!this.parentTarget) return;
        const count = this.parentTarget.value.length;
        this.counterTarget.textContent = `(${count.toString()}/${this.maxlengthValue})`;

        if (count < this.minlengthValue) {
            this.counterTarget.classList.add("fr-text--red-marianne");
            this.counterTarget.classList.remove("fr-text--high-green-emeraude");
        } else if (count >= this.minlengthValue) {
            this.counterTarget.classList.add("fr-text--high-green-emeraude");
            this.counterTarget.classList.remove("fr-text--red-marianne");
        } else {
            this.counterTarget.classList.remove("fr-text--red-marianne", "fr-text--high-green-emeraude");
        }
    }
}
