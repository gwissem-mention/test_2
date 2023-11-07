import {Controller} from "@hotwired/stimulus";
import {getComponent, Component} from "@symfony/ux-live-component";

export default class extends Controller {
    protected component: Component | undefined;
    static override targets: string[] = ["list", "input"];

    private currentAddress!: string | null;
    private currentAddressElement!: HTMLElement | null;

    declare readonly hasListTarget: boolean;
    declare readonly listTarget: HTMLUListElement;
    declare readonly inputTarget: HTMLInputElement;

    override async initialize(): Promise<void> {
        this.component = await getComponent(this.element as HTMLElement);

        this.component.on("model:set", this.onModelSet.bind(this));
        this.component.on("render:finished", this.onRenderFinished.bind(this));
        this.component.on("render:finished", () => this.bindFocus());

        const inputElement: HTMLElement | null | undefined = this.component.element.querySelector("input");

        if (inputElement instanceof HTMLElement) {
            inputElement.addEventListener("blur", () => this.clearResults(inputElement));
        }

    }

    private bindFocus(): void {
        if (this.hasListTarget) {
            this.inputTarget.addEventListener("keyup", (event: KeyboardEvent) => this.navigateToFirstAddress(event));
            const listItems: NodeListOf<HTMLLIElement> = this.listTarget.querySelectorAll("li");

            if (listItems) {
                for (const listItem of listItems) {
                    listItem.addEventListener("focus", () => this.saveFocusedAddress(listItem));
                    this.bindLiKeyboardEvents(listItem);
                }
            }
        }
    }

    private navigateToFirstAddress(event: KeyboardEvent): void {
        if (event.key === "ArrowDown") {
            const firstAddress: Element | null = this.listTarget.firstElementChild;

            if (firstAddress) {
                (firstAddress as HTMLElement).focus();
                this.currentAddressElement = firstAddress as HTMLElement;
            }
        }
    }

    private saveFocusedAddress(li: HTMLLIElement): void {
        this.currentAddress = li.innerText;
        this.currentAddressElement = li as HTMLElement;
    }

    private bindLiKeyboardEvents(li: HTMLLIElement): void {
        li.addEventListener("keydown", (event: KeyboardEvent) => {
            event.preventDefault();
            event.stopImmediatePropagation();

            if (event.code === "Enter" || event.code === "NumpadEnter") {
                this.inputTarget.focus();
                this.inputTarget.value = `${this.currentAddress}`;

                this.closeAddressesList();
            }

            if (event.code === "Escape") {
                this.inputTarget.focus();

                this.closeAddressesList();
            }

            if (event.code === "ArrowUp") {
                this.navigateToAddress("up");
            }

            if (event.code === "ArrowDown") {
                this.navigateToAddress("down");
            }
        });

        this.inputTarget.addEventListener("keydown", (event: KeyboardEvent) => {
            if (event.code === "Escape") {
                this.inputTarget.focus();

                this.closeAddressesList();
            }
        });
    }

    private navigateToAddress(direction: string): void {
        const address: Element | null | undefined = (direction === "up") ? this.currentAddressElement?.previousElementSibling : this.currentAddressElement?.nextElementSibling;

        if (address) {
            (address as HTMLElement).focus();
            this.currentAddressElement = address as HTMLElement;
        }
    }

    private closeAddressesList(): void {
        if (this.hasListTarget) {
            this.listTarget.style.display = "none";
            this.currentAddress = null;
            this.currentAddressElement = null;

            this.clearResults(this.listTarget);
        }
    }

    clearResults(inputElement: HTMLElement): void {
        const list: HTMLElement | null | undefined = inputElement.parentElement?.querySelector(".list--addresses");

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
            if (component.valueStore.has("dataGirondeEnabled") && true === component.valueStore.get("dataGirondeEnabled")) {
                component.getParent()?.render();
            }
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
