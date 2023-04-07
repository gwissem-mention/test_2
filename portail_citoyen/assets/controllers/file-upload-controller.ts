import {Controller} from "@hotwired/stimulus";
import {getComponent, Component} from "@symfony/ux-live-component";

export default class extends Controller {
    static override targets: string[] = ["input"];
    protected component: Component | undefined;

    declare readonly inputTarget: HTMLInputElement;

    override async initialize(): Promise<void> {
        this.component = await getComponent(this.element as HTMLElement);
    }

    public upload(event: Event): void {
        const component: Component | undefined = this.component;
        const fileElement: HTMLInputElement | null = event.target as HTMLInputElement;

        if (component && fileElement) {
            const files: FileList | null = fileElement.files;
            if (files && files.length > 0) {
                const formData: FormData = new FormData();
                const componentData = component.valueStore.all();
                const data: string = JSON.stringify(componentData);
                [...files].forEach((file: File, index: number): void => {
                    formData.append("file_" + index, file);
                });

                fetch(component["backend"]["url"] + "/upload?" + new URLSearchParams({
                    data: data,
                }), {
                    method: "POST",
                    headers: {"X-CSRF-TOKEN": component["backend"]["csrfToken"]},
                    body: formData
                })
                    .then(async response => {
                        if (!response.ok) {
                            throw await response.text();
                        }

                        return response.text();
                    })
                    .then(data => {
                        this.removeErrors(component);
                        const element: HTMLDivElement = document.createElement("div");
                        element.innerHTML = data;

                        const dataLiveDataValue: string | null | undefined = element.querySelector(".fr-upload-group")?.getAttribute("data-live-data-value");

                        if (dataLiveDataValue) {
                            const dataLiveDataValueFiles = JSON.parse(dataLiveDataValue)["files"];
                            component.element.setAttribute("data-live-data-value", dataLiveDataValue);
                            component.set("files", dataLiveDataValueFiles);

                            const componentParent: Component | null = component.getParent();

                            if (componentParent) {
                                const parentDataFiles = {
                                    ...componentParent.getData("files"),
                                    ...dataLiveDataValueFiles,
                                };
                                componentParent.set("files", parentDataFiles);
                            }
                        }
                    }).catch((data) => {
                        this.removeErrors(component);
                        const element: HTMLDivElement = document.createElement("div");
                        element.innerHTML = data;

                        const dataLiveDataValue: string | null | undefined = element.querySelector(".fr-upload-group")?.getAttribute("data-live-data-value");
                        const errors = dataLiveDataValue ? JSON.parse(dataLiveDataValue)["errors"] : [this.element.getAttribute("data-general-error-text") as string];
                        this.addErrors(errors, component);
                    });
            }
        }
    }

    private addErrors(errors: string[] = [], component: Component): void {
        component.element.classList.add("fr-input-group--error");
        this.inputTarget.setAttribute("aria-describedby", component.element.id + "-error");

        if (errors.length > 0) {
            errors.forEach((error: string) => {
                const p: HTMLParagraphElement = document.createElement("p");
                p.classList.add("fr-error-text");
                p.id = component.element.id + "-error";
                p.innerHTML = error;
                component.element.append(p);
            });
        }
    }

    private removeErrors(component: Component): void {
        component.element.classList.remove("fr-input-group--error");
        this.inputTarget.removeAttribute("aria-describedby");
        component.element.querySelectorAll(".fr-error-text")?.forEach((element) => {
            element.remove();
        });
    }
}
