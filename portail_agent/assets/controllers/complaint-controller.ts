import { Controller } from "@hotwired/stimulus";
import { Modal, Toast } from "bootstrap";
import flatpickr from "flatpickr";
import { Instance } from "flatpickr/dist/types/instance";
import { French } from "flatpickr/dist/l10n/fr";

import { HttpMethodsEnum } from "../scripts/utils/HttpMethodsEnum";
import { HttpStatusCodeEnum } from "../scripts/utils/HttpStatusCodeEnum";

const {Dropzone} = require("dropzone");

type RejectFetchResponse = { form: string };
type AssignFetchResponse = { form: string, agent_name: string };
type UnitReassignFetchResponse = { form: string, unit_name: string };
type UnitReassignRejectFetchResponse = { form: string };
type SendReportFetchResponse = { form: string };
type ValidateAppointmentFetchResponse = { form: string };

export default class ComplaintController extends Controller {
    static override targets: string[] = [
        "appointmentDoneRadioButton",
        "modifyAppointmentButton",
        "cancelAppointmentButton",
        "validateAppointmentButton",
        "pelEnterTheDateOfTheAppointmentWithTheVictim",
        "pelEnterTheTimeOfTheAppointmentWithTheVictim",
        "complaintAppointmentDate",
        "complaintAppointmentTime",
        "appointmentScheduledWithTheDeclarantOn",
        "appointmentForm",
        "assignmentForm",
        "assignmentModal",
        "cancelAppointmentModal",
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
        "dropZoneError",
        "appointmentDateInput",
        "appointmentTimeInput",
        "commentsButton",
        "commentsContainer"
    ];

    declare readonly appointmentDoneRadioButtonTarget: HTMLInputElement;
    declare readonly hasAppointmentDoneRadioButtonTarget: boolean;
    declare readonly appointmentFormTarget: HTMLFormElement;
    declare readonly assignmentFormTarget: HTMLFormElement;
    declare readonly assignmentModalTarget: HTMLElement;
    declare readonly cancelAppointmentModalTarget: HTMLElement;
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
    declare readonly hasCommentBoxTarget: boolean;
    declare readonly modifyAppointmentButtonTarget: HTMLButtonElement;
    declare readonly cancelAppointmentButtonTarget: HTMLButtonElement;
    declare readonly validateAppointmentButtonTarget: HTMLButtonElement;
    declare readonly pelEnterTheDateOfTheAppointmentWithTheVictimTarget: HTMLElement;
    declare readonly pelEnterTheTimeOfTheAppointmentWithTheVictimTarget: HTMLElement;
    declare readonly complaintAppointmentDateTarget: HTMLElement;
    declare readonly complaintAppointmentTimeTarget: HTMLElement;
    declare readonly appointmentScheduledWithTheDeclarantOnTarget: HTMLElement;
    declare readonly appointmentDateInputTarget: HTMLInputElement;
    declare readonly appointmentTimeInputTarget: HTMLInputElement;
    declare readonly commentsButtonTarget: HTMLInputElement;
    declare readonly commentsContainerTarget: HTMLInputElement;

    static fp: Instance | undefined;

    public override connect() {
        this.openUnitReassignmentValidationModal();
    }

    public appointmentDateInputTargetConnected(
        element: HTMLInputElement
    ): void {
        const { id, disabled } = element;

        // init flatpickr
        if (!disabled) {
            this.initFlatpickr(id);
        }
    }

    private initFlatpickr(id: string): void {
        ComplaintController.fp = flatpickr(`#${id}`, {
            inline: true,
            locale: French,
            weekNumbers: true,
            monthSelectorType: "static",
            // https://flatpickr.js.org/examples/#disabling-all-dates-except-select-few
            enable: [
                function (date: Date) {
                    // https://stackoverflow.com/a/10944417
                    const today = new Date();
                    const then = new Date(
                        today.getFullYear(),
                        today.getMonth(),
                        today.getDate(),
                        0,
                        0,
                        0
                    );
                    const diff = today.getTime() - then.getTime();

                    return date.valueOf() >= today.valueOf() - diff;
                },
            ],
        }) as Instance;
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
                    .then((data: RejectFetchResponse) => {
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
                        .then((data: AssignFetchResponse) => {
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
                        .then((data: UnitReassignFetchResponse) => {
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
                        .then((data: UnitReassignRejectFetchResponse) => {
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
        const hasScheduledAppointment: string | null = this.dropZoneFormTarget.getAttribute("data-has-scheduled-appointment");
        const radioInput: HTMLInputElement | null = this.hasAppointmentDoneRadioButtonTarget ? this.appointmentDoneRadioButtonTarget.querySelector("input[name=\"send_report[appointment_done]\"]:checked") : null;

        dropzone.options.url = urlSendReport;

        if (dropzone.getQueuedFiles().length >= 1 && (hasScheduledAppointment !== "true" || (hasScheduledAppointment === "true" && radioInput?.value))) {
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
        } else if (urlClose && hasScheduledAppointment === "true") {
            fetch(urlClose, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.dropZoneFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data: SendReportFetchResponse) => {
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
                            }
                        });
                });
        } else {
            if (this.dropZoneFormTarget.getAttribute("data-empty-message")) {
                this.dropZoneErrorTarget.innerText = this.dropZoneFormTarget.getAttribute("data-empty-message") ?? "";
            }
        }
    }

    public commentFocus(): void {
        this.setCommentsState("show");
        this.scrollCommentFeed();
        this.commentContentTarget?.focus();
    }

    public commentButton(): void {
        // Must be ignored because HTMLElement doesn't have value property
        // @ts-ignore
        const empty = (this.commentContentTarget.value === "");

        if (empty) {
            this.commentButtonTarget.setAttribute("disabled", "disabled");
        } else {
            this.commentButtonTarget.removeAttribute("disabled");
        }
    }

    public scrollCommentFeed(): void {
        if (this.hasCommentBoxTarget) {
            this.commentBoxTarget.scrollTo(0, this.commentBoxTarget.scrollHeight);
        }
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
            // https://til.hashrocket.com/posts/sjkmsvegjn-formdata-doesnt-iterate-over-disabled-inputs
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.appointmentFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data: ValidateAppointmentFetchResponse) => {
                            if (response.status === HttpStatusCodeEnum.OK) {
                                this.reloadComplaintContainer();

                            } else {
                                this.appointmentFormTarget.innerHTML = data.form;
                            }
                        });
                });
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public cancelAppointment({params: {url}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
            })
                .then((response: Response) => {
                    response.json()
                        .then(() => {
                            if (response.status === HttpStatusCodeEnum.OK) {
                                Modal.getInstance(this.cancelAppointmentModalTarget)?.hide();

                                this.reloadComplaintContainer();
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

    private async reloadComplaintContainer(): Promise<void> {
        // Reload the page in ajax, then replace the #complaint-container div by the new one
        const response = await fetch(window.location.href, {
            method: HttpMethodsEnum.GET
        });
        const data: string = await response.text();
        const element: HTMLDivElement = document.createElement("div");
        element.innerHTML = data;
        const complaintContainerSource: HTMLElement | null = element.querySelector("#complaint-container");

        if (this.complaintContainerTarget && complaintContainerSource) {
            this.complaintContainerTarget.innerHTML = complaintContainerSource.innerHTML;
        }
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
                        .then(async () => {
                            if (response.status === HttpStatusCodeEnum.OK) {
                                await this.reloadComplaintContainer();
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

    public modifyAppointment(): void {
        this.initFlatpickr(this.appointmentDateInputTarget.id);

        // https://til.hashrocket.com/posts/sjkmsvegjn-formdata-doesnt-iterate-over-disabled-inputs
        this.appointmentDateInputTarget.removeAttribute("disabled");
        this.appointmentTimeInputTarget.removeAttribute("disabled");

        this.modifyAppointmentButtonTarget.classList.add("d-none");
        this.cancelAppointmentButtonTarget.classList.add("d-none");

        this.appointmentTimeInputTarget.classList.remove("d-none");
        this.validateAppointmentButtonTarget.classList.remove("d-none");
        this.pelEnterTheDateOfTheAppointmentWithTheVictimTarget.classList.remove("d-none");
        this.pelEnterTheTimeOfTheAppointmentWithTheVictimTarget.classList.remove("d-none");

        this.appointmentScheduledWithTheDeclarantOnTarget.classList.add("d-none");
        this.complaintAppointmentDateTarget.classList.add("d-none");
        this.complaintAppointmentTimeTarget.classList.add("d-none");
        this.complaintAppointmentDateTarget.parentElement?.parentElement?.parentElement?.classList.add("fr-mt-md-9v");
    }

    private setSpinnerState(button: HTMLButtonElement): void {
        button.querySelector(".spinner-border")?.classList.toggle("d-none");

        if (button.disabled) {
            button.removeAttribute("disabled");
        } else {
            button.setAttribute("disabled", "disabled");
        }
    }

    public openComments(): void {
        this.setCommentsState("show");
    }

    public closeComments(): void {
        this.setCommentsState("hide");
    }

    private setCommentsState(state: string): void {
        this.commentsButtonTarget.classList.toggle("d-none", state === "show");
        this.commentsButtonTarget.classList.toggle("d-block", state !== "show");

        this.commentsContainerTarget.classList.toggle("d-none", state !== "show");
        this.commentsContainerTarget.classList.toggle("d-block", state === "show");
    }
}
