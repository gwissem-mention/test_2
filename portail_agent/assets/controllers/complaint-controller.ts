import {Controller} from "@hotwired/stimulus";
import {Toast, Modal} from "bootstrap";

import {HttpMethodsEnum} from "../scripts/utils/HttpMethodsEnum";

export default class extends Controller {
    static override targets: string[] = [
        "complaintContainer",
        "dropzoneFile",
        "rejectForm",
        "rejectModal"
    ];

    declare readonly complaintContainerTarget: HTMLElement;
    declare readonly dropzoneFileTarget: HTMLElement;
    declare readonly rejectFormTarget: HTMLFormElement;
    declare readonly rejectModalTarget: HTMLElement;

    public override connect() {
        this.scrollCommentFeed();
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public reject({params: {url}}): void {
        fetch(url, {
            method: HttpMethodsEnum.POST,
            body: new FormData(this.rejectFormTarget)
        })
            .then(response => response.json())
            .then((response: any) => {
                if (response.success) {
                    const modal: Modal | null = Modal.getInstance(this.rejectModalTarget);

                    if (modal) {
                        modal.hide();
                        modal.dispose();
                    }

                    // Must be ignored because in Bootstrap types, Toast element has string | Element type
                    // however we need here to type it as Toast.
                    // @ts-ignore
                    const toast: Toast = new Toast(document.getElementById("toast-complaint-reject"));

                    if (toast) {
                        toast.show();
                    }

                    this.reloadComplaintContainer();
                } else {
                    this.rejectFormTarget.innerHTML = response.form;
                }
            });
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public assign({params: {url}}): void {
        const form: HTMLFormElement | null = document.querySelector("form[name=assign]");

        if (url && form) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(form)
            })
                .then(response => response.json())
                .then((data: any) => {
                    const modalElement: Element | null = document.getElementById("modal-complaint-assign");

                    if (modalElement) {
                        if (data.success) {
                            const modal: Modal | null = Modal.getInstance(modalElement);

                            if (modal) {
                                modal.hide();
                            }
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
                            const modalForm: HTMLFormElement | null = modalElement.querySelector("form");

                            if (modalForm) {
                                modalForm.innerHTML = data.form;
                            }
                        }
                    }
                });
        }
    }

    // Must be ignored because we can't type url, redirection and supervisor here.
    // @ts-ignore
    public unitReassign({params: {url, redirection, supervisor}}): void {
        const form: HTMLFormElement | null = document.querySelector("form[name=unit_reassign]");

        if (url && form) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(form)
            })
                .then(response => response.json())
                .then((data: any) => {
                    const modalElement: Element | null = document.getElementById("modal-complaint-unit-reassign");

                    if (modalElement) {
                        if (data.success) {
                            const modal: Modal | null = Modal.getInstance(modalElement);

                            if (modal) {
                                modal.hide();
                            }

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
                            const modalForm: HTMLFormElement | null = modalElement.querySelector("form");

                            if (modalForm) {
                                modalForm.innerHTML = data.form;
                            }
                        }
                    }
                });
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public send({params: {url}}): void {
        if (url) {
            location.href = url;

            const modalElement: Element | null = document.getElementById("modal-complaint-send-to-lrp");

            if (modalElement) {
                const modal: Modal | null = Modal.getInstance(modalElement);

                if (modal) {
                    modal.hide();
                    modal.dispose();
                }
            }

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
        const form: HTMLFormElement | null = document.querySelector("form[name=drop_zone]");

        if (url && form) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(form)
            })
                .then(response => response.json())
                .then((data: any) => {
                    const modalElement: Element | null = document.getElementById("modal-complaint-send-report-to-victim");

                    if (modalElement) {
                        if (data.success) {
                            const modal: Modal | null = Modal.getInstance(modalElement);

                            if (modal) {
                                modal.hide();
                            }

                            // Must be ignored because in Bootstrap types, Toast element has string | Element type
                            // however we need here to type it as Toast.
                            // @ts-ignore
                            const toast: Toast = new Toast(document.getElementById("toast-validation-send-report-to-victim"));

                            if (toast) {
                                toast.show();
                            }

                            this.reloadComplaintContainer();
                        } else if (data.form) {
                            const modalForm: HTMLFormElement | null = modalElement.querySelector("form");

                            if (modalForm) {
                                modalForm.innerHTML = data.form;
                            }
                        }
                    }
                });
        }
    }

    public commentFocus(): void {
        const commentContent: HTMLElement | null = document.getElementById("comment_content");

        if (commentContent) {
            commentContent.focus();
        }
    }

    public commentButton(): void {
        // Type any must be used here because the value attribute needs to be retrieved and did not exist on HTMLElement | null type
        const commentContent: any | null = document.getElementById("comment_content");
        const commentButton: HTMLElement | null = document.getElementById("comment-button");

        if (commentContent && commentButton) {
            const empty = (commentContent.value === "");

            if (empty) {
                commentButton.setAttribute("disabled", "disabled");
                commentButton.classList.remove("btn-primary");
                commentButton.classList.add("btn-secondary");
            } else {
                commentButton.removeAttribute("disabled");
                commentButton.classList.remove("btn-secondary");
                commentButton.classList.add("btn-primary");
            }
        }
    }

    public scrollCommentFeed(): void {
        const commentFeed: HTMLElement | null = document.getElementById("comment-box");

        if (commentFeed) {
            commentFeed.scrollTo(0, commentFeed.scrollHeight);
        }
    }

    public browseReport(): void {
        this.dropzoneFileTarget?.click();
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
