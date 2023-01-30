import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    public override initialize(): void {
        this._onPreConnect = this._onPreConnect.bind(this);
    }

    public override connect(): void {
        this.element.addEventListener("autocomplete:pre-connect", this._onPreConnect);
    }

    public override disconnect(): void {
        this.element.removeEventListener("autocomplete:connect", this._onPreConnect);
    }

    public _onPreConnect(event: Event): void {
        const loadMoreText: string = this.element.getAttribute("data-load-text") ?? "Loading more results ...";

        // Must be ignored because "detail" attribute does not exist on type EventTarget.
        // @ts-ignore
        event.detail.options.render.loading_more = function () {
            return `<div class="loading-more-results">${loadMoreText}</div>`;
        };
    }
}
