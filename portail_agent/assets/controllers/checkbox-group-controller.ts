import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override targets = ["main", "secondary", "groupAction"];

    declare readonly mainTarget: HTMLInputElement;
    declare readonly secondaryTargets: HTMLInputElement[];
    declare readonly groupActionTarget: HTMLElement;

    mainClick(): void {
        this.secondaryTargets.forEach(checkbox => checkbox.checked = this.mainTarget.checked);
        this.toggleActionGroupBlock();
        this.dispatchUpdateCheckedEvent();
    }

    secondaryClick(): void {
        this.toggleActionGroupBlock();
        this.dispatchUpdateCheckedEvent();
    }

    toggleActionGroupBlock(): void {
        this.groupActionTarget.classList.toggle(
            "d-none",
            !this.secondaryTargets.some(checkbox => checkbox.checked)
        );
    }

    dispatchUpdateCheckedEvent(): void {
        this.dispatch("updateChecked", {
            detail: {
                checked: this.secondaryTargets.filter(checkbox => checkbox.checked),
            }
        });
    }

    resetAll(): void {
        this.mainTarget.checked = false;
        this.secondaryTargets.forEach(checkbox => checkbox.checked = false);
        this.toggleActionGroupBlock();
    }
}
