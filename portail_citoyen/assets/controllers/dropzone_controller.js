import {Controller} from '@hotwired/stimulus';
import {Dropzone} from "dropzone";
import 'dropzone/dist/dropzone.css';

export default class extends Controller {
    connect() {
        const form = this.element.closest('form');
        new Dropzone(this.element, {
            url: form.action,
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: this.data.get('maxFiles'),
            maxFiles: this.data.get('maxFiles') ?? null,
            paramName: this.data.get('name'),
            maxFilesize: this.data.get('maxFileSize'),
            acceptedFiles: this.data.get('acceptedFiles'),
            addRemoveLinks: this.data.get('addRemoveLinks'),
            dictDefaultMessage: this.data.get('defaultMessage'),
            dictRemoveFile: this.data.get('removeFile'),
            dictFileTooBig: this.data.get('fileTooBig'),
            dictInvalidFileType: this.data.get('invalidFileType'),
            dictMaxFilesExceeded: this.data.get('maxFilesExceeded'),
            dictCancelUpload: this.data.get('cancelUpload'),
            dictCancelUploadConfirmation: this.data.get('cancelUploadConfirmation'),
            init: function () {
                const submitButton = document.querySelector("button[type='submit']");
                const wrapperThis = this;
                submitButton.addEventListener("click", function (e) {
                    e.preventDefault();
                    wrapperThis.processQueue();
                });

                this.on('sending', function (files, xhr, formData) {
                    [...form.querySelectorAll("input, select, checkbox, textarea")].forEach((item) => {
                        formData.append(item.getAttribute('name'), item.value);
                    });
                });

                this.on('sendingmultiple', function (files, xhr, formData) {
                    [...form.querySelectorAll("input, select, checkbox, textarea")].forEach((item) => {
                        formData.append(item.getAttribute('name'), item.value);
                    });
                });

                this.on('success', function (files, response) {
                    window.location.replace(response.redirect_url);
                });

                this.on('successmultiple', function (files, response) {
                    window.location.replace(response.redirect_url);
                });
            }
        });
    }
}
