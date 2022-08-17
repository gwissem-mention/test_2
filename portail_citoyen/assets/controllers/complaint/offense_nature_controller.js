import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    displayWarningText(event) {
        const element = event.currentTarget;
        const isActive = element.selectedOptions[0].getAttribute('data-complaint-offense-nature-active');
        let dataElement = document.getElementById("warning-text");
        if (dataElement) {
            dataElement.remove();
        }

        if (isActive && this.data.has('url')) {
            fetch(this.data.get('url'), {
                method: 'GET',
            })
                .then((response) => response.text())
                .then((data) => {
                    if (data) {
                        if (!dataElement) {
                            dataElement = document.createElement('div');
                            dataElement.id = 'warning-text';
                        }
                        dataElement.innerHTML = data;
                        this.element.insertAdjacentElement('afterend', dataElement);
                    }
                });
        }
    }

    displayOtherAABText(event) {
        const element = event.currentTarget;
        const selectedOption = element.selectedOptions[0];
        const isActive = selectedOption.getAttribute('data-complaint-offense-nature-other-aab');
        const offenseNatureElem = document.getElementById("aab-text");
        offenseNatureElem.innerHTML = '';

        if (isActive) {
            const form = document.querySelector('form');
            const offenseNatureFieldName = element.getAttribute('name');

            fetch(form.action, {
                method: form.method,
                body: new URLSearchParams([[offenseNatureFieldName, selectedOption.value]]),
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
                    offenseNatureElem.replaceWith(domFragment.getElementById("aab-text"));
                });
        }
    }
}
