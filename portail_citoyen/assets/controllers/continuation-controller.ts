import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    public redirectToHomepage(): void
    {
        window.location.href = "/";
    }
}
