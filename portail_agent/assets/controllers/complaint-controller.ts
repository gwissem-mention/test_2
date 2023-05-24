import {Controller} from "@hotwired/stimulus";
import {Toast, Modal} from "bootstrap";

import {HttpMethodsEnum} from "../scripts/utils/HttpMethodsEnum";

export default class extends Controller {
    static override targets: string[] = [
        "appointmentForm",
        "assignmentForm",
        "assignmentModal",
        "commentButton",
        "commentContent",
        "commentBox",
        "complaintContainer",
        "dropzoneFile",
        "dropZoneForm",
        "rejectForm",
        "rejectModal",
        "unitReassignmentForm",
        "unitReassignmentModal",
        "sendReportModal",
        "sendToLrpModal"
    ];

    declare readonly appointmentFormTarget: HTMLFormElement;
    declare readonly assignmentFormTarget: HTMLFormElement;
    declare readonly assignmentModalTarget: HTMLElement;
    declare readonly commentButtonTarget: HTMLElement;
    declare readonly commentContentTarget: HTMLElement;
    declare readonly commentBoxTarget: HTMLElement;
    declare readonly complaintContainerTarget: HTMLElement;
    declare readonly dropzoneFileTarget: HTMLElement;
    declare readonly dropZoneFormTarget: HTMLFormElement;
    declare readonly rejectFormTarget: HTMLFormElement;
    declare readonly rejectModalTarget: HTMLElement;
    declare readonly hasUnitReassignmentModalTarget: boolean;
    declare readonly unitReassignmentModalTarget: HTMLElement;
    declare readonly unitReassignmentFormTarget: HTMLFormElement;
    declare readonly sendReportModalTarget: HTMLElement;
    declare readonly sendToLrpModalTarget: HTMLElement;

    public override connect() {
        this.scrollCommentFeed();
        this.openUnitReassignmentValidationModal();
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public reject({params: {url}}): void {
        fetch(url, {
            method: HttpMethodsEnum.POST,
            body: new FormData(this.rejectFormTarget)
        })
            .then((response: Response) => {
                response.json()
                    .then((data: any) => {
                        if (response.status === 200) {
                            Modal.getInstance(this.rejectModalTarget)?.hide();
                            Modal.getInstance(this.rejectModalTarget)?.dispose();

                            // Must be ignored because in Bootstrap types, Toast element has string | Element type
                            // however we need here to type it as Toast.
                            // @ts-ignore
                            const toast: Toast = new Toast(document.getElementById("toast-complaint-reject"));

                            if (toast) {
                                toast.show();
                            }

                            this.reloadComplaintContainer();
                        } else if (data.form) {
                            this.rejectFormTarget.innerHTML = data.form;
                        }
                    });
            });
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public assign({params: {url}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.assignmentFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then(data => {
                            if (response.status === 200) {
                                Modal.getInstance(this.assignmentModalTarget)?.hide();

                                document.querySelectorAll(".agent-name").forEach((element) => {
                                    element.textContent = data.agent_name;
                                });
                                // Must be ignored because in Bootstrap types, Toast element has string | Element type
                                // however we need here to type it as Toast.
                                // @ts-ignore
                                const toast: Toast = new Toast(document.getElementById("toast-complaint-assign"));

                                if (toast) {
                                    toast.show();
                                }

                                this.reloadComplaintContainer();
                            } else if (data.form) {
                                this.assignmentModalTarget.innerHTML = data.form;
                            }
                        });
                });
        }
    }

    // Must be ignored because we can't type url, redirection and supervisor here.
    // @ts-ignore
    public unitReassign({params: {url, redirection, supervisor}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.unitReassignmentFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data: any) => {
                            if (response.status === 200) {
                                Modal.getInstance(this.unitReassignmentModalTarget)?.hide();

                                if (supervisor && redirection) {
                                    location.href = redirection;
                                } else {
                                    document.querySelectorAll(".unit-name").forEach((element) => {
                                        element.textContent = data.unit_name;
                                    });
                                    // Must be ignored because in Bootstrap types, Toast element has string | Element type
                                    // however we need here to type it as Toast.
                                    // @ts-ignore
                                    const toast: Toast = new Toast(document.getElementById("toast-complaint-unit-reassign-ordered"));

                                    if (toast) {
                                        toast.show();
                                    }

                                    this.reloadComplaintContainer();
                                }
                            } else if (data.form) {
                                this.unitReassignmentFormTarget.innerHTML = data.form;
                            }
                        });
                });
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public unitReassignReject({params: {url}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.unitReassignmentFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data: any) => {
                            if (response.status === 200) {
                                Modal.getInstance(this.unitReassignmentModalTarget)?.hide();

                                // Must be ignored because in Bootstrap types, Toast element has string | Element type
                                // however we need here to type it as Toast.
                                // @ts-ignore
                                const toast: Toast = new Toast(document.getElementById("toast-complaint-unit-reassign-rejected"));

                                if (toast) {
                                    toast.show();
                                }

                                this.reloadComplaintContainer();
                            } else if (data.form) {
                                this.unitReassignmentFormTarget.innerHTML = data.form;
                            }
                        });

                });
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public send({params: {url}}): void {
        if (url) {
            location.href = url;

            Modal.getInstance(this.sendToLrpModalTarget)?.hide();
            Modal.getInstance(this.sendToLrpModalTarget)?.dispose();

            // Must be ignored because in Bootstrap types, Toast element has string | Element type
            // however we need here to type it as Toast.
            // @ts-ignore
            const toast: Toast = new Toast(document.getElementById("toast-validation-send-to-lrp"));

            if (toast) {
                toast.show();
            }

            this.reloadComplaintContainer();
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public sendReport({params: {url}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.dropZoneFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data:any) => {
                            if (response.status === 200) {
                                Modal.getInstance(this.sendReportModalTarget)?.hide();

                                // Must be ignored because in Bootstrap types, Toast element has string | Element type
                                // however we need here to type it as Toast.
                                // @ts-ignore
                                const toast: Toast = new Toast(document.getElementById("toast-validation-send-report-to-victim"));

                                if (toast) {
                                    toast.show();
                                }

                                this.reloadComplaintContainer();
                            } else if (data.form) {
                                this.dropZoneFormTarget.innerHTML = data.form;
                            }
                        });
                });
        }
    }

    public commentFocus(): void {
        this.commentContentTarget?.focus();
    }

    public commentButton(): void {
        // Must be ignored because HTMLElement doesn't have value property
        // @ts-ignore
        const empty = (this.commentContentTarget.value === "");

        if (empty) {
            this.commentButtonTarget.setAttribute("disabled", "disabled");
            this.commentButtonTarget.classList.remove("btn-primary");
            this.commentButtonTarget.classList.add("btn-secondary");
        } else {
            this.commentButtonTarget.removeAttribute("disabled");
            this.commentButtonTarget.classList.remove("btn-secondary");
            this.commentButtonTarget.classList.add("btn-primary");
        }
    }

    public scrollCommentFeed(): void {
        this.commentBoxTarget.scrollTo(0, this.commentBoxTarget.scrollHeight);
    }

    public browseReport(): void {
        this.dropzoneFileTarget?.click();
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public validateAppointment({params: {url}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.appointmentFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data: any) => {
                            if (response.status === 200) {
                                this.reloadComplaintContainer();
                            } else {
                                this.appointmentFormTarget.innerHTML = data.form;
                            }
                        });
                });
        }
    }

    public openUnitReassignmentValidationModal(): void {
        const urlParams: URLSearchParams = new URLSearchParams(window.location.search);

        if (urlParams.get("showUnitReassignmentValidationModal") == "1" && this.hasUnitReassignmentModalTarget) {
            const modal: Modal | null = new Modal(this.unitReassignmentModalTarget);

            if (modal) {
                modal.show();
            }
        }
    }

    private reloadComplaintContainer(): void {
        // Reload the page in ajax, then replace the #complaint-container div by the new one
        fetch(window.location.href, {
            method: HttpMethodsEnum.GET
        })
            .then(response => response.text())
            .then((data: string) => {
                const element: HTMLDivElement = document.createElement("div");
                element.innerHTML = data;
                const complaintContainerSource: HTMLElement | null = element.querySelector("#complaint-container");

                if (this.complaintContainerTarget && complaintContainerSource) {
                    this.complaintContainerTarget.innerHTML = complaintContainerSource.innerHTML;
                }
            });
    }
}
