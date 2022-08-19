import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    displayForm(event) {
        const element = event.currentTarget;
        const value = element.value;
        const form = document.querySelector('form');
        const offenseNatureFieldName = element.getAttribute('name');
        const identityForm = document.getElementById("form-identity");
        identityForm.innerHTML = '';
        this.dispatch("spinner_show", {detail: {element: identityForm}});
        fetch(form.action, {
            method: form.method,
            body: new URLSearchParams([[offenseNatureFieldName, value]]),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
            .then((response) => response.text())
            .then((data) => {
                const dataElement = document.createElement('div');
                dataElement.innerHTML = data;
                const domFragment = new DocumentFragment();
                domFragment.appendChild(dataElement);
                identityForm.innerHTML = domFragment.getElementById("form-identity").innerHTML;
            });


    }
}
