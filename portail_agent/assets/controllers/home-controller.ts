import {Controller} from "@hotwired/stimulus";
import {Toast} from "bootstrap";

export default class extends Controller {
    public override connect() {
        this.showUnitReassignmentValidationToast();
    }

    private showUnitReassignmentValidationToast(): void {
        const urlParams: URLSearchParams = new URLSearchParams(window.location.search);

        if (urlParams.get("unitReassignmentAccepted") === "1") {
            // Must be ignored because in Bootstrap types, Toast element has string | Element type
            // however we need here to type it as Toast.
            // @ts-ignore
            const toast: Toast | null = new Toast(document.getElementById("toast-complaint-unit-reassign-validated"));

            if (toast) {
                toast.show();
            }
        }
    }
}
