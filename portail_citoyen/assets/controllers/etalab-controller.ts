import {Controller} from "@hotwired/stimulus";
import {getComponent, Component} from "@symfony/ux-live-component";

export default class extends Controller {
    protected component: Component | undefined;

    override async initialize(): Promise<void> {
        this.component = await getComponent(this.element as HTMLElement);

        this.component.on("model:set", this.onModelSet.bind(this));
        this.component.on("render:finished", this.onRenderFinished.bind(this));

        const inputElement: HTMLElement|null|undefined = this.component.element.querySelector("input");

        if (inputElement instanceof HTMLElement) {
            inputElement.addEventListener("blur", () => this.clearResults(inputElement));
        }
    }

    clearResults(inputElement: HTMLElement): void {
        const list: HTMLElement|null|undefined = inputElement.parentElement?.querySelector(".list--addresses");
        if (list instanceof HTMLElement) {
            list.style.display = "none";
        }
    }

    onModelSet(model: string, value: any, component: Component) {
        if (this.isParentComponentBinded(component)) {
            const dataModel: Map<string, string> = this.extractDataModelMappingFromComponent(component);
            const parentModelName: string | undefined = dataModel.get(model);
            if (parentModelName) {
                component.getParent()?.set(parentModelName, value);
            }
        }
    }

    onRenderFinished(component: Component) {
        if (this.isParentComponentBinded(component)) {
            const dataModel: Map<string, string> = this.extractDataModelMappingFromComponent(component);
            dataModel.forEach((parentModelName: string, modelName: string) => {
                if (component.valueStore.has(modelName)) {
                    component.getParent()?.set(parentModelName, component.valueStore.get(modelName));
                }
            });
            component.getParent()?.render();
        }
    }

    isParentComponentBinded(component: Component): boolean {
        return component.valueStore.get("_attributes").dataModel !== undefined;
    }

    extractDataModelMappingFromComponent(component: Component): Map<string, string> {
        const dataModel: Map<string, string> = new Map();
        component.valueStore.get("_attributes").dataModel.split(" ").forEach((key: string) => {
            const dataModelBind: string[] = key.split(":");
            if (dataModelBind[0] && dataModelBind[1]) {
                dataModel.set(dataModelBind[0], dataModelBind[1]);
            }
        });

        return dataModel;
    }
}
