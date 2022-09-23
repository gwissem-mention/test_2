import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    redirectToHomepage() {
        window.location.href = '/';
    }
}
