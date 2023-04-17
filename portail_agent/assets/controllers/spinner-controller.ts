import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override targets: string[] = ["button"];

    declare readonly buttonTarget: HTMLElement;

    public show(): void {
        this.buttonTarget.setAttribute("disabled", "disabled");
        this.buttonTarget.innerHTML = "<div class=\"spinner-border\"><span class=\"sr-only\"></span></div>";
    }
}
