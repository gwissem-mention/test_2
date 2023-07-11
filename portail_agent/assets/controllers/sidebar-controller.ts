import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override targets: string[] = ["sidebar", "background", "avatar"];

    declare readonly sidebarTarget: HTMLInputElement;
    declare readonly backgroundTarget: HTMLInputElement;
    declare readonly avatarTarget: HTMLInputElement;

    public openSidebar(): void {
        this.setSidebarState("show");
    }

    public closeSidebar(): void {
        this.setSidebarState("hide");
    }

    private setSidebarState(state: string): void {
        if (this.sidebarTarget && this.backgroundTarget) {
            this.sidebarTarget.classList.toggle("d-none", state === "hide");
            this.backgroundTarget.classList.toggle("d-none", state === "hide");
        }

        if (this.avatarTarget) {
            this.avatarTarget.classList.toggle("z-max", state === "show");
            this.avatarTarget.classList.toggle("background-blue", state === "show");
        }
    }
}
