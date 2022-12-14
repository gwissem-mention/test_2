import {EtaLabAutoCompleter} from "../scripts/etalab/etalab-auto-completer";
import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    public override connect(): void
    {
        const element: Element = this.element;

        if (element) {
            element.addEventListener("live:connect", () => {
                (new EtaLabAutoCompleter()).bind();
            });
        }
    }

    public toUpperCase(): void
    {
        const element: Element = this.element;

        if (element) {
            // Must be ignored because "value" property does not exist on Element type.
            // @ts-ignore
            element.value = element.value.toUpperCase();
        }
    }
}
