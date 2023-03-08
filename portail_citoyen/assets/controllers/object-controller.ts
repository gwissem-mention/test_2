/*import {getComponent} from "@symfony/ux-live-component";
import {Controller} from "@hotwired/stimulus";
import {ObjectFocusHandler} from "../scripts/focus/object-focus-handler";
import {ObjectFocusState} from "../scripts/focus/object-focus-state";

export default class extends Controller {
    private _objectFocusHandlers: Map<string, ObjectFocusHandler> = new Map<string, ObjectFocusHandler>();

    override async initialize() {
        // @ts-ignore
        this.component = await getComponent(this.element);

        // @ts-ignore
        this.component.on("render:finished", (component) => {
            console.log(component.childNodes);
            const element: Element = this.element;

            if (element) {
                const objectFocusHandler: ObjectFocusHandler | undefined = this._objectFocusHandlers.get(element.id);

                if (objectFocusHandler) {
                    objectFocusHandler.bind();
                } else {
                    const addObjectButton: HTMLElement | null = document.querySelector("#objects_objects_add");
                    const objectsCollection: HTMLElement | null = document.querySelector("#objects_objects");

                    if (addObjectButton && objectsCollection) {
                        if (!this._objectFocusHandlers.has(element.id)) {
                            const newObjectFocusHandler: ObjectFocusHandler = (new ObjectFocusHandler(new ObjectFocusState(addObjectButton, objectsCollection)));

                            this._objectFocusHandlers.set(element.id, newObjectFocusHandler);
                            newObjectFocusHandler.bind();
                        }
                    }
                }

                if (objectFocusHandler && null !== component.element.querySelector("#objects_objects")) {
                    // @ts-ignore
                    objectFocusHandler.objectFocusState.objectsCollection = component.element.querySelector("#objects_objects");
                    objectFocusHandler.bind();
                }
            }
        });
    }
}

*/
