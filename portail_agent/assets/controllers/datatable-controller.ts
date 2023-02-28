import {Controller} from "@hotwired/stimulus";
import DataTable from "datatables.net";

import "datatables.net-bs5/js/dataTables.bootstrap5.min";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import {Api} from "datatables.net-bs5";

export default class extends Controller {
    protected datatable: Api<any> | undefined;

    public override initialize() {
        this.init();
    }

    public search(): void {
        if (this.datatable) {
            const searchQuery: string | undefined = this.element.querySelector("input")?.value;
            if (undefined !== searchQuery) {
                this.datatable.search(searchQuery).draw();
            }
        }
    }

    private init(): void {
        const datatableElement: Element | null = document.querySelector(".datatable");

        if (datatableElement) {
            const columnsElements: NodeListOf<HTMLElement> | null = this.element.querySelectorAll("th[data-column]");
            const columns: { data: string | null; }[] = [];

            columnsElements.forEach(value => {
                columns.push({data: value.getAttribute("data-column")});
            });

            if (columns) {
                this.datatable = new DataTable("#" + datatableElement.id, {
                    language: {
                        url: "build/json/datatables/fr_FR.json"
                    },
                    dom: "<\"top\">rt<\"bottom\"p><\"clear\">",
                    processing: true,
                    serverSide: true,
                    columns: columns,
                    searching: true,
                });
            }
        }
    }
}
