import {Controller} from "@hotwired/stimulus";
const {Dropzone} = require("dropzone");
import "dropzone/dist/dropzone.css";

export default class extends Controller {
    public override connect(): void
    {
        const form: HTMLFormElement | null = this.element.closest("form");

        if (form) {
            // Must be ignored due to this.element typing.
            // @ts-ignore
            new Dropzone(this.element, {
                url: form.action,
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: this.data.get("maxFiles"),
                maxFiles: this.data.get("maxFiles") ?? null,
                paramName: this.data.get("name"),
                maxFilesize: this.data.get("maxFileSize"),
                acceptedFiles: this.data.get("acceptedFiles"),
                addRemoveLinks: this.data.get("addRemoveLinks"),
                dictDefaultMessage: this.data.get("defaultMessage"),
                dictRemoveFile: this.data.get("removeFile"),
                dictFileTooBig: this.data.get("fileTooBig"),
                dictInvalidFileType: this.data.get("invalidFileType"),
                dictMaxFilesExceeded: this.data.get("maxFilesExceeded"),
                dictCancelUpload: this.data.get("cancelUpload"),
                dictCancelUploadConfirmation: this.data.get("cancelUploadConfirmation"),
                // Function is used there instead of arrow function due to this context.
                init: function() {
                    const submitButton: Element | null = document.querySelector("button[type='submit']");
                    const wrapperThis: any = this;

                    if (submitButton) {
                        submitButton.addEventListener("click", (e) => {
                            e.preventDefault();
                            wrapperThis.processQueue();
                        });
                    }

                    this.on("sending", (files: Dropzone.DropzoneFile, xhr: XMLHttpRequest, formData: FormData) => {
                        [...form.querySelectorAll("input, select, checkbox, textarea")].forEach((item: Element) => {
                            const name: string | null = item.getAttribute("name");

                            if (name) {
                                // Must be ignored because "value" property does not exist on Element type.
                                // @ts-ignore
                                formData.append(name, item.value);
                            }
                        });
                    });

                    this.on("sendingmultiple", (files: Dropzone.DropzoneFile, xhr: XMLHttpRequest, formData: FormData) => {
                        [...form.querySelectorAll("input, select, checkbox, textarea")].forEach((item: Element) => {
                            const name: string | null = item.getAttribute("name");

                            if (name) {
                                // Must be ignored because "value" property does not exist on Element type.
                                // @ts-ignore
                                formData.append(name, item.value);
                            }
                        });
                    });

                    this.on("success", (files: Dropzone.DropzoneFile, response: any) => {
                        window.location.replace(response.redirect_url);
                    });

                    this.on("successmultiple", (files: any, response: any) => {
                        window.location.replace(response.redirect_url);
                    });
                }
            });
        }
    }
}
