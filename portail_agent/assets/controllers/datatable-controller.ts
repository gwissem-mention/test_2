import {Controller} from "@hotwired/stimulus";
import DataTable from "datatables.net";

import "datatables.net-bs5/js/dataTables.bootstrap5.min";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";

export default class extends Controller {
    protected datatable: any | undefined;

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
        if (this.datatable) {
            this.datatable.ajax.reload();
        }
    }

    // Must be ignored because we can't type filter here
    // @ts-ignore
    public filter({params: {filter}}): void {
        if (this.datatable && filter) {
            this.datatable.search(filter).draw();
        } else if (this.datatable){
            this.datatable.search("").draw();
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
                pagingType: "full_numbers"
            });

            if (statusFilter) {
                this.datatable.search(statusFilter);
            }
        }
    }
}
