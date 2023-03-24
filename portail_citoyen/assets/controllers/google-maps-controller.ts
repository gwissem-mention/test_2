import {Controller} from "@hotwired/stimulus";
import {getComponent, Component} from "@symfony/ux-live-component";
import {GoogleMap} from "../scripts/google/google-map";

export default class extends Controller {
    protected component: Component | undefined | null;

    override async initialize(): Promise<void> {
        this.component = await getComponent(this.element as HTMLElement);

        if (this.component) {
            this.init();
        }
    }

    private init(): void {
        const map: HTMLElement | null = document.getElementById("map");

        if (map) {
            new GoogleMap(map);
        }
    }
}
