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

        const startAddressDepartmentNumber: string | null = component?.getChildren()?.get("facts-startAddress-address")?.valueStore.get("postcode")?.substring(0, 2);
        const endAddressDepartmentNumber: string | null = component?.getChildren()?.get("facts-endAddress-address")?.valueStore.get("postcode")?.substring(0, 2);

        if (startAddressDepartmentNumber && endAddressDepartmentNumber) {
            if (!GirondeBoundaryChecker.isInsideGironde(startAddressDepartmentNumber) && !GirondeBoundaryChecker.isInsideGironde(endAddressDepartmentNumber)) {
                if (errorMessageElement) {
                    errorMessageElement.textContent = "Uniquement les trajets vers la Gironde ou depuis la Gironde sont acceptés";
                }
                return;
            }
            if (errorMessageElement) {
                errorMessageElement.textContent = "";
            }
        }
    }
}
