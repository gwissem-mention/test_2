import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static override targets = ["list"];

    declare readonly listTarget: HTMLUListElement;

    override connect(): void {
        this.setupDeleteLinks();
    }

    setupDeleteLinks(): void {
        const list = this.listTarget as HTMLUListElement;

        if (list.children.length > 0) {
            list.querySelectorAll(".pel-link--delete").forEach((link: Element) => {
                (link as HTMLAnchorElement).addEventListener("click", (event: Event) => this.handleDelete(event));
            });
        }
    }

    handleDelete (event: Event): void {
        event.preventDefault();

        const target = event.currentTarget;
        if (!(target instanceof HTMLAnchorElement)) {
            return;
        }

        const deleteButtons = Array.from(this.listTarget.querySelectorAll(".pel-link--delete")) as HTMLAnchorElement[];
        const buttonIndex = deleteButtons.indexOf(target);

        if (deleteButtons.length > 1) {
            if (buttonIndex > 0) {
                deleteButtons[buttonIndex - 1]?.focus();
            } else if (deleteButtons.length > 1) {
                deleteButtons[1]?.focus();
            }
        } else {
            const chooseFileBtn = document.querySelector(".pel-btn--custom-upload") as HTMLButtonElement;
            chooseFileBtn.focus();
        }
    }
}
