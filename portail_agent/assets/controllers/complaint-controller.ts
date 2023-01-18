import {Controller} from "@hotwired/stimulus";
import {Toast, Modal} from "bootstrap";

export default class extends Controller {
    public override connect() {
        this.scrollCommentFeed();
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public reject({params: {url}}): void {
        const form: HTMLFormElement | null = document.querySelector("form[name=reject]");

        if (form) {
            fetch(url, {
                method: "POST",
                body: new FormData(form)
            })
                .then(response => response.json())
                .then((response: any) => {
                    const modalElement: Element | null = document.getElementById("modal-complaint-reject");

                    if (modalElement) {
                        if (response.success) {
                            const complaintAssignButton: Element | null = document.getElementById("complaint-assign-button");

                            if (complaintAssignButton) {
                                complaintAssignButton.remove();
                            }

                            const complaintRejectButton: Element | null = document.getElementById("complaint-reject-button");

                            if (complaintRejectButton) {
                                complaintRejectButton.remove();
                            }

                            const complaintSendLrpButton: Element | null = document.getElementById("complaint-send-lrp-button");

                            if (complaintSendLrpButton) {
                                complaintSendLrpButton.remove();
                            }

                            const complaintReassignButton: Element | null = document.getElementById("complaint-reassign-button");

                            if (complaintReassignButton) {
                                complaintReassignButton.remove();
                            }

                            const commentButton: Element | null = document.getElementById("complaint-comment-button");

                            if (commentButton) {
                                commentButton.remove();
                            }

                            const commentField: Element | null = document.getElementById("comment_content");

                            if (commentField) {
                                commentField.setAttribute("disabled", "disabled");
                            }

                            const modal: Modal | null = Modal.getInstance(modalElement);

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
                        } else {
                            const modalForm: HTMLFormElement | null = modalElement.querySelector("form");

                            if (modalForm) {
                                modalForm.innerHTML = response.form;
                            }
                        }
                    }
                });
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public assign({params: {url}}): void {
        const form: HTMLFormElement | null = document.querySelector("form[name=assign]");

        if (url && form) {
            fetch(url, {
                method: "POST",
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

                            const complaintAgent: Element | null = document.getElementById("complaint-agent");

                            if (complaintAgent) {
                                complaintAgent.classList.remove("d-none");
                            }

                            // Must be ignored because in Bootstrap types, Toast element has string | Element type
                            // however we need here to type it as Toast.
                            // @ts-ignore
                            const toast: Toast = new Toast(document.getElementById("toast-complaint-assign"));

                            if (toast) {
                                toast.show();
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

    public send(): void {
        const modalElement: Element | null = document.getElementById("modal-complaint-send-to-lrp");

        if (modalElement) {
            const modal: any = Modal.getInstance(modalElement);

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
            } else {
                commentButton.removeAttribute("disabled");
            }
        }
    }

    public scrollCommentFeed(): void {
        const commentFeed: HTMLElement | null = document.getElementById("comment-box");

        if (commentFeed) {
            commentFeed.scrollTo(0, commentFeed.scrollHeight);
        }
    }
}
