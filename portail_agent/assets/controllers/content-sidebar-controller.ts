import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override targets: string[] = ["contentSidebar", "contentSidebarOpenButton"];

    declare readonly contentSidebarTarget: HTMLInputElement;
    declare readonly contentSidebarOpenButtonTarget: HTMLInputElement;

    public openSidebar(): void {
        this.setSidebarState("show");
    }

    public closeSidebar(): void {
        this.setSidebarState("hide");
    }

    private setSidebarState(state: string): void {
        if (this.contentSidebarOpenButtonTarget) {
            this.contentSidebarOpenButtonTarget.classList.toggle("d-none", state === "show");
        }

        if (this.contentSidebarTarget) {
            this.contentSidebarTarget.classList.toggle("d-none", state === "hide");
            this.contentSidebarTarget.classList.toggle("col-md-4", state === "show");
            this.contentSidebarTarget.classList.toggle("col-lg-3", state === "show");
            this.contentSidebarTarget.classList.toggle("col-xxl-2", state === "show");
        }
    }
}
