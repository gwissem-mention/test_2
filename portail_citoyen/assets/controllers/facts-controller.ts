import {Controller} from "@hotwired/stimulus";
import {Component, getComponent} from "@symfony/ux-live-component";
import {GirondeBoundaryChecker} from "../scripts/GirondeBoundaryChecker";

export default class extends Controller {

    protected component: Component | undefined | null;

    override async initialize(): Promise<void> {
        this.component = await getComponent(this.element as HTMLElement);

        this.component.on("render:finished", this.onRenderFinished.bind(this));
    }

    onRenderFinished(component: Component) {
        const errorMessageElement :HTMLElement | null = document.getElementById("error-message");

        const startAddressLatitude: number | null = component?.getChildren()?.get("facts-startAddress-address")?.valueStore.get("latitude");
        const startAddressLongitude: number | null = component?.getChildren()?.get("facts-startAddress-address")?.valueStore.get("longitude");
        const endAddressLatitude: number | null = component?.getChildren()?.get("facts-endAddress-address")?.valueStore.get("latitude");
        const endAddressLongitude: number | null = component?.getChildren()?.get("facts-endAddress-address")?.valueStore.get("longitude");

        if (startAddressLatitude && startAddressLongitude && endAddressLatitude && endAddressLongitude) {
            if (!GirondeBoundaryChecker.isInsideGironde(startAddressLatitude, startAddressLongitude) && !GirondeBoundaryChecker.isInsideGironde(endAddressLatitude, endAddressLongitude)) {
                if (errorMessageElement) {
                    errorMessageElement.textContent = "Uniquement les trajets vers la gironde ou depuis la gironde sont acceptés";
                }
                return;
            }
            if (errorMessageElement) {
                errorMessageElement.textContent = "";
            }
        }
    }
}
