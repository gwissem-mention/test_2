import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override targets: string[] = ["sidebarAside"];

    declare readonly sidebarAsideTarget: HTMLInputElement;

    public openSidebar(): void {
        this.setSidebarState("show");
    }

    public closeSidebar(): void {
        this.setSidebarState("hide");
    }

    private setSidebarState(state: string): void {
        if (this.sidebarAsideTarget) {
            this.sidebarAsideTarget.classList.toggle("d-none", state === "hide");
        }
    }
}
