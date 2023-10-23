import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    public override initialize(): void {
        this._onPreConnect = this._onPreConnect.bind(this);
        this._onConnect = this._onConnect.bind(this);
    }

    public override connect(): void {
        this.element.addEventListener("autocomplete:pre-connect", this._onPreConnect);
        this.element.addEventListener("autocomplete:connect", this._onConnect);
    }

    public override disconnect(): void {
        this.element.removeEventListener("autocomplete:pre-connect", this._onConnect);
        this.element.removeEventListener("autocomplete:connect", this._onPreConnect);
    }

    public _onPreConnect(event: Event): void {
        const loadMoreText: string = this.element.getAttribute("data-load-text") ?? "Loading more results ...";

        // Must be ignored because "detail" attribute does not exist on type EventTarget.
        // @ts-ignore
        event.detail.options.render.loading_more = function () {
            return `<div class="loading-more-results">${loadMoreText}</div>`;
        };

        // Remove clear button plugin
        // @ts-ignore
        const settings = event.detail.options;

        if (settings.plugins) {
            delete settings.plugins["clear_button"];
        }
    }

    public _onConnect(event: Event): void {
        // Must be ignored because "detail" attribute does not exist on type EventTarget.
        // @ts-ignore
        const tomSelectInstance = event.detail.tomSelect;

        if (tomSelectInstance) {
            const controlInputElement: HTMLInputElement | null = tomSelectInstance.control_input;
            const controlElement: HTMLDivElement | null = tomSelectInstance.control;

            if (controlInputElement) {
                controlInputElement.removeAttribute("size");
                controlInputElement.removeAttribute("aria-labelledby");
                controlInputElement.setAttribute("aria-autocomplete", "list");
                controlInputElement.removeAttribute("type");
                controlInputElement.classList.add("pel-ellipsis");
            }

            // If error, get error element id
            // and put it in aria-describedby attribute on tom-select input
            if (controlInputElement && controlElement && controlElement.parentElement && controlElement.parentElement.classList.contains("fr-select--error")) {
                controlInputElement.setAttribute("aria-describedby", "form-errors-" + this.element.getAttribute("id") );
            }
        }
    }
}
