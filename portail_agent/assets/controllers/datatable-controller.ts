import {Controller} from "@hotwired/stimulus";
import DataTable from "datatables.net";

import "datatables.net-bs5/js/dataTables.bootstrap5.min";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";

export default class extends Controller {
    public override initialize() {
        this.init();
    }

    private init(): void {
        const order: string | null = this.element.getAttribute("data-order");
        const columnDefs: string | null = this.element.getAttribute("data-columnDefs");

        if (order && columnDefs) {
            new DataTable("#" + this.element.id, {
                order: JSON.parse(order),
                columnDefs: JSON.parse(columnDefs),
                language: {
                    url: "build/json/datatables/fr_FR.json"
                },
                dom: "<\"top\">rt<\"bottom\"p><\"clear\">",
            });
        }
    }
}
