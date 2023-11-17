import {Controller} from "@hotwired/stimulus";

export default class extends Controller
{
    static override values = {
        url: String,
    };

    declare readonly urlValue: string;

    override connect(): void {
        this.getUserTimezone();
    }

    private getUserTimezone(): void {
        const timezone: string = Intl.DateTimeFormat().resolvedOptions().timeZone;

        fetch(this.urlValue, {
            method: "POST",
            body: JSON.stringify({
                timezone: timezone
            }),
        }).then(response => {
            if (!response.ok) {
                throw Error(response.statusText);
            }
        });
    }
}
