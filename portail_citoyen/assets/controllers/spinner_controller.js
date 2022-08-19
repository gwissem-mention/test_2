import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    show({detail: {element}}) {
        element.innerHTML = '<div class="spinner round"></div>';
    }
}
