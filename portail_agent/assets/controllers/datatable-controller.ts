import {Controller} from "@hotwired/stimulus";
import DataTable from "datatables.net";

import "datatables.net-bs5/js/dataTables.bootstrap5.min";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import {Api} from "datatables.net-bs5";

export default class extends Controller {
    protected datatable: Api<any> | undefined;

    static override targets = [
        "table",
        "theadCell"
    ];

    declare readonly tableTarget: HTMLElement;
    declare readonly theadCellTargets: HTMLElement[];

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

    public refresh(): void {
        console.log("reload");
        if (this.datatable) {
            this.datatable.ajax.reload(() => console.log("reloaded"));
        }
    }

    private init(): void {
        const statusFilter: string | null  = new URLSearchParams(window.location.search).get("status");
        const columns: { data: string | null; }[] = [];

        this.theadCellTargets.forEach(theadCell => {
            columns.push({data: theadCell.getAttribute("data-column")});
        });

        if (columns) {
            this.datatable = new DataTable("#" + this.tableTarget.id, {
                language: {
                    url: "build/json/datatables/fr_FR.json"
                },
                dom: "<\"top\">rt<\"bottom\"p><\"clear\">",
                processing: true,
                serverSide: true,
                columns: columns,
                searching: true,
            });

            if (statusFilter) {
                this.datatable.search(statusFilter);
            }
        }
    }
}
