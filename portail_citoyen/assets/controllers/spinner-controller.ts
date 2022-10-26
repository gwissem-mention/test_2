import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    public show({detail: {element}}: any): void
    {
        element.innerHTML = "<div class='spinner round'></div>";
    }
}
