import {Controller} from "@hotwired/stimulus";
export default class extends Controller {
    focus() {
        if (this.element instanceof HTMLElement) {
            this.element.focus();
        }
    }
}
