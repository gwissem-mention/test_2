import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    toUpperCase() {
        let element = this.element;
        element.value = element.value.toUpperCase();
    }
}
