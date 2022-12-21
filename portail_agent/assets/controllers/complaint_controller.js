import {Controller} from '@hotwired/stimulus';
import {Toast, Modal} from 'bootstrap';

export default class extends Controller {
    reject() {
        let modal = Modal.getInstance(document.getElementById('modal-complaint-reject')) // Returns a Bootstrap modal instance
        modal.hide();

        const toast = new Toast(document.querySelector('.toast'));
        toast.show();
    }
}
