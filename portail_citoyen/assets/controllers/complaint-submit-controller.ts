import {Controller} from "@hotwired/stimulus";
import {getComponent, Component} from "@symfony/ux-live-component";
import BackendResponse from "@symfony/ux-live-component/dist/BackendResponse";

export default class extends Controller {
    protected component: Component | undefined | null;
    private readonly OFFSET_Y = 10;
    private ok = false;

    public async submit(): Promise<void> {
        this.component = await getComponent(this.element as HTMLElement);
        if (this.component) {
            this.component.on("render:started", this.onRenderStarted.bind(this));
            this.component.on("render:finished", this.onRenderFinished.bind(this));
        }
    }

    private onRenderStarted(html: string, backendResponse: BackendResponse): void {
        this.ok = backendResponse.response.ok;
    }

    private onRenderFinished(): void {
        if (this.component && !this.ok) {
            const firstError: Element | null = document.querySelector(".fr-error-text");
            if (firstError) {
                const bounds: DOMRect | undefined = firstError.parentElement?.getBoundingClientRect();
                if (bounds) {
                    window.scrollTo(window.scrollX, bounds.top - this.OFFSET_Y + window.scrollY);
                }
                const element: HTMLElement | null | undefined = firstError.parentElement?.querySelector("input, select, textarea");
                if (element) {
                    window.setTimeout(function () {
                        element.focus({preventScroll: true});
                    }, 0);
                }
                this.component = null; // set component to null to avoid bad behaviour with hook
            }
        }
    }
}
