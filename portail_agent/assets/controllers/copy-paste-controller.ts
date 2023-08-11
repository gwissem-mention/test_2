import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override targets: string[] = ["declarationNumber"];

    declare readonly declarationNumberTarget: HTMLInputElement;

    public bind(): void {
        this.copyToClipboard(this.declarationNumberTarget.innerText);
    }

    private copyToClipboard(needle: string): void {
        navigator.clipboard.writeText(needle);
    }
}
