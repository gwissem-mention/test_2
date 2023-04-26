import {Controller} from "@hotwired/stimulus";
import {Modal, Toast} from "bootstrap";

export default class extends Controller {
    static override targets = [
        "bulkAssignComplaints",
        "bulkAssignForm",
        "bulkAssignModal",
        "bulkReassignComplaints",
        "bulkReassignForm",
        "bulkReassignModal"
    ];

    static override values = {
        urlBulkAssign: String,
        urlBulkReassign: String,
    };

    declare readonly bulkAssignComplaintsTarget: HTMLInputElement;
    declare readonly bulkAssignFormTarget: HTMLFormElement;
    declare readonly bulkAssignModalTarget: HTMLFormElement;
    declare readonly urlBulkAssignValue: string;
    declare readonly bulkReassignComplaintsTarget: HTMLInputElement;
    declare readonly bulkReassignFormTarget: HTMLFormElement;
    declare readonly bulkReassignModalTarget: HTMLFormElement;
    declare readonly urlBulkReassignValue: string;

    setBulkTarget(event: { detail: { checked: HTMLInputElement[] }}): void {
        const selectedComplaints = event.detail.checked.map(checkbox => checkbox.id).join(",");
        this.bulkAssignComplaintsTarget.value = selectedComplaints;
        this.bulkReassignComplaintsTarget.value = selectedComplaints;
    }

    public bulkAssign(): void {
        const fetchOptions: RequestInit = {
            method: "POST",
            body: new FormData(this.bulkAssignFormTarget)
        };

        fetch(this.urlBulkAssignValue, fetchOptions)
            .then(response => response.json())
            .then((data: any) => {
                if (data.success) {
                    Modal.getInstance(this.bulkAssignModalTarget)?.hide();

                    console.log(new FormData(this.bulkAssignFormTarget));

                    // TODO: Find a stimulus way to control TomSelect
                    // this.bulkAssignFormTarget.querySelectorAll("[data-ts-item]").forEach((element) => {
                    //     element.remove();
                    // });

                    document.querySelectorAll(".agent-name").forEach((element) => {
                        element.textContent = data.agent_name;
                        console.log(data);
                    });

                    // @ts-ignore
                    const toast: Toast = new Toast(document.getElementById("toast-complaint-assign"));

                    if (toast) {
                        toast.show();
                    }

                    this.clearSelectedComplaints();
                    this.dispatch("finished");
                } else if (data.form) {
                    this.bulkAssignFormTarget.innerHTML = data.form;
                }
            });
    }

    public bulkReassign(): void {
        console.log(this.urlBulkReassignValue);
        const fetchOptions: RequestInit = {
            method: "POST",
            body: new FormData(this.bulkReassignFormTarget)
        };

        fetch(this.urlBulkReassignValue, fetchOptions)
            .then(response => response.json())
            .then((data: any) => {
                if (data.success) {
                    Modal.getInstance(this.bulkReassignModalTarget)?.hide();
                    // TODO: Find a stimulus way to control TomSelect
                    // this.bulkReassignFormTarget.querySelectorAll("[data-ts-item]").forEach((element) => {
                    //     element.remove();
                    // });

                    document.querySelectorAll(".unit-name").forEach((element) => {
                        element.textContent = data.unit_name;
                    });

                    // @ts-ignore
                    const toast: Toast = new Toast(document.getElementById("toast-complaint-unit-reassign-ordered"));

                    if (toast) {
                        toast.show();
                    }

                    this.clearSelectedComplaints();
                    this.dispatch("finished");
                } else if (data.form) {
                    this.bulkReassignFormTarget.innerHTML = data.form;
                }
            });
    }

    private clearSelectedComplaints(): void {
        this.bulkAssignComplaintsTarget.value = "";
        this.bulkReassignComplaintsTarget.value = "";
    }
}
