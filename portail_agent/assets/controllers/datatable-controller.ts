import {Controller} from "@hotwired/stimulus";
import DataTable from "datatables.net";

import "datatables.net-bs5/js/dataTables.bootstrap5.min";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";

export default class extends Controller {
    public override initialize() {
        this.init();
    }

    private init(): void {
        const columnsElements: NodeListOf<HTMLElement> | null = this.element.querySelectorAll("th[data-column]");
        const columns: { data: string | null; }[] = [];

        columnsElements.forEach(value => {
            columns.push({data: value.getAttribute("data-column")});
        });

        if (columns) {
            new DataTable("#" + this.element.id, {
                language: {
                    url: "build/json/datatables/fr_FR.json"
                },
                dom: "<\"top\">rt<\"bottom\"p><\"clear\">",
                processing: true,
                serverSide: true,
                columns: columns
            });
        }
    }
}
