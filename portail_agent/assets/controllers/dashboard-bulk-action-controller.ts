import {Controller} from "@hotwired/stimulus";
import {Modal, Toast} from "bootstrap";

import {HttpMethodsEnum} from "../scripts/utils/HttpMethodsEnum";

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
            method: HttpMethodsEnum.POST,
            body: new FormData(this.bulkAssignFormTarget)
        };

        fetch(this.urlBulkAssignValue, fetchOptions)
            .then((response: Response) => {
                response.json()
                    .then((data: any) => {
                        if (response.status === 200) {
                            Modal.getInstance(this.bulkAssignModalTarget)?.hide();

                            // TODO: Find a stimulus way to control TomSelect
                            // this.bulkAssignFormTarget.querySelectorAll("[data-ts-item]").forEach((element) => {
                            //     element.remove();
                            // });

                            document.querySelectorAll(".agent-name").forEach((element) => {
                                element.textContent = data.agent_name;
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
            });
    }

    public bulkReassign(): void {
        const fetchOptions: RequestInit = {
            method: HttpMethodsEnum.POST,
            body: new FormData(this.bulkReassignFormTarget)
        };

        fetch(this.urlBulkReassignValue, fetchOptions)
            .then((response: Response) => {
                response.json()
                    .then((data: any) => {
                        if (response.status === 200) {
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
            });
    }

    private clearSelectedComplaints(): void {
        this.bulkAssignComplaintsTarget.value = "";
        this.bulkReassignComplaintsTarget.value = "";
    }
}
