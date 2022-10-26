import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    public toUpperCase(): void
    {
        const element: Element = this.element;

        // Must be ignored because "value" property does not exist on Element type.
        // @ts-ignore
        element.value = element.value.toUpperCase();
    }
}
