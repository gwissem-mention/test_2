import {Controller} from "@hotwired/stimulus";
import {Toast, Modal} from "bootstrap";

import {HttpMethodsEnum} from "../scripts/utils/HttpMethodsEnum";
import {HttpStatusCodeEnum} from "../scripts/utils/HttpStatusCodeEnum";

const {Dropzone} = require("dropzone");

export default class extends Controller {
    static override targets: string[] = [
        "appointmentDoneRadioButton",
        "appointmentForm",
        "assignmentForm",
        "assignmentModal",
        "commentButton",
        "commentContent",
        "commentBox",
        "complaintContainer",
        "dropZoneForm",
        "rejectForm",
        "rejectModal",
        "unitReassignmentForm",
        "unitReassignmentModal",
        "sendReportModal",
        "sendReportValidationButton",
        "sendToLrpModal",
        "dropZoneError"
    ];

    declare readonly appointmentDoneRadioButtonTarget: HTMLInputElement;
    declare readonly hasAppointmentDoneRadioButtonTarget: boolean;
    declare readonly appointmentFormTarget: HTMLFormElement;
    declare readonly assignmentFormTarget: HTMLFormElement;
    declare readonly assignmentModalTarget: HTMLElement;
    declare readonly commentButtonTarget: HTMLElement;
    declare readonly commentContentTarget: HTMLElement;
    declare readonly commentBoxTarget: HTMLElement;
    declare readonly complaintContainerTarget: HTMLElement;
    declare readonly dropZoneFormTarget: HTMLFormElement;
    declare readonly rejectFormTarget: HTMLFormElement;
    declare readonly rejectModalTarget: HTMLElement;
    declare readonly hasUnitReassignmentModalTarget: boolean;
    declare readonly unitReassignmentModalTarget: HTMLElement;
    declare readonly unitReassignmentFormTarget: HTMLFormElement;
    declare readonly sendReportModalTarget: HTMLElement;
    declare readonly sendReportValidationButtonTarget: HTMLButtonElement;
    declare readonly sendToLrpModalTarget: HTMLElement;
    declare readonly dropZoneErrorTarget: HTMLFormElement;

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
                    .then((data: any) => { // TODO: Remove any type, create a specific type for this.
                        if (response.status === HttpStatusCodeEnum.OK) {
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
                            if (response.status === HttpStatusCodeEnum.OK) {
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
                        .then((data: any) => { // TODO: Remove any type, create a specific type for this.
                            if (response.status === HttpStatusCodeEnum.OK) {
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
                        .then((data: any) => { // TODO: Remove any type, create a specific type for this.
                            if (response.status === HttpStatusCodeEnum.OK) {
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

    // Must be ignored because we can't type urlSendReport and urlClose here.
    // @ts-ignore
    private sendReport({params: {urlSendReport, urlClose}}): void {
        const dropzone: Dropzone = Dropzone.forElement(this.dropZoneFormTarget.querySelector(".dropzone"));
        const isAfterAppointment: string | null = this.dropZoneFormTarget.getAttribute("data-is-after-appointment");
        const radioInput: HTMLInputElement | null = this.hasAppointmentDoneRadioButtonTarget ? this.appointmentDoneRadioButtonTarget.querySelector("input[name=\"send_report[appointment_done]\"]:checked") : null;

        dropzone.options.url = urlSendReport;

        if (dropzone.getQueuedFiles().length >= 1 && (isAfterAppointment !== "true" || (isAfterAppointment === "true" && radioInput?.value === "1"))) {
            this.setSpinnerState(this.sendReportValidationButtonTarget);
            dropzone.processQueue();

            dropzone.on("successmultiple", () => {
                Modal.getInstance(this.sendReportModalTarget)?.hide();

                // Must be ignored because in Bootstrap types, Toast element has string | Element type
                // however we need here to type it as Toast.
                // @ts-ignore
                const toast: Toast = new Toast(document.getElementById("toast-validation-send-report-to-victim"));

                if (toast) {
                    toast.show();
                }

                this.reloadComplaintContainer();
            });

            dropzone.on("errormultiple", (files: any, response: any) => {
                if (response.form) {
                    this.dropZoneFormTarget.innerHTML = response.form;
                    this.setSpinnerState(this.sendReportValidationButtonTarget);
                }
            });
        } else if (urlClose && isAfterAppointment === "true" && radioInput?.value === "1") {
            fetch(urlClose, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.dropZoneFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data: any) => { // TODO: Remove any type, create a specific type for this.
                            if (response.status === HttpStatusCodeEnum.OK) {
                                Modal.getInstance(this.sendReportModalTarget)?.hide();

                                // Must be ignored because in Bootstrap types, Toast element has string | Element type
                                // however we need here to type it as Toast.
                                // @ts-ignore
                                const toast: Toast = new Toast(document.getElementById("toast-close-complaint-after-the-appointment"));

                                if (toast) {
                                    toast.show();
                                }

                                this.reloadComplaintContainer();
                            } else {
                                this.dropZoneFormTarget.innerHTML = data.form;
                                this.dropZoneErrorTarget.innerText = this.dropZoneFormTarget.getAttribute("data-error-message") ?? "";
                            }
                        });
                });
        } else {
            if (this.dropZoneFormTarget.getAttribute("data-empty-message")) {
                this.dropZoneErrorTarget.innerText = this.dropZoneFormTarget.getAttribute("data-empty-message") ?? "";
            } else if (this.dropZoneFormTarget.getAttribute("data-error-message")) {
                this.dropZoneErrorTarget.innerText = this.dropZoneFormTarget.getAttribute("data-error-message") ?? "";
            }
        }
    }

    public isClosableAfterAppointment(): void {
        const radioInput: HTMLInputElement | null = this.appointmentDoneRadioButtonTarget.querySelector("input[name=\"send_report[appointment_done]\"]:checked");

        if (radioInput?.value === "1") {
            this.sendReportValidationButtonTarget.removeAttribute("disabled");
        } else {
            this.sendReportValidationButtonTarget.setAttribute("disabled", "disabled");
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
        const dropzone: Dropzone = Dropzone.forElement(this.dropZoneFormTarget.querySelector(".dropzone"));

        if (dropzone.hiddenFileInput) {
            dropzone.hiddenFileInput.click();
        }
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
                        .then((data: any) => { // TODO: Remove any type, create a specific type for this.
                            if (response.status === HttpStatusCodeEnum.OK) {
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

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public selfAssign({params: {url}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
            })
                .then((response: Response) => {
                    response.json()
                        .then(() => {
                            if (response.status === HttpStatusCodeEnum.OK) {
                                this.reloadComplaintContainer();
                                // Must be ignored because in Bootstrap types, Toast element has string | Element type
                                // however we need here to type it as Toast.
                                // @ts-ignore
                                const toast: Toast = new Toast(document.getElementById("toast-complaint-self-assign"));

                                if (toast) {
                                    toast.show();
                                }
                            }
                        });
                });
        }
    }

    private setSpinnerState(button: HTMLButtonElement): void {
        button.querySelector(".spinner-border")?.classList.toggle("d-none");

        if (button.disabled) {
            button.removeAttribute("disabled");
        } else {
            button.setAttribute("disabled", "disabled");
        }
    }
}
