import {Controller} from '@hotwired/stimulus';
import {Toast, Modal} from 'bootstrap';

export default class extends Controller {
    reject({params: {url}}) {
        const form = document.querySelector('form[name=reject]');
        fetch(url, {
            method: "POST",
            body: new FormData(form)
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (json) {
                const modalEl = document.getElementById('modal-complaint-reject');
                if (json.success) {
                    document.getElementById('complaint-reject-button').remove();
                    document.getElementById('complaint-send-lrp-button').remove();
                    document.getElementById('complaint-reassign-button').remove();
                    const modal = Modal.getInstance(modalEl);
                    modal.hide();
                    modal.dispose();
                    const toast = new Toast(document.querySelector('.toast'));
                    toast.show();
                } else {
                    modalEl.querySelector('form').innerHTML = json.form;
                }
            });
    }
}
