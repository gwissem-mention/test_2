import {Controller} from "@hotwired/stimulus";

export default class extends Controller {
    static override values = {
        accessToken: String,
    };

    declare accessTokenValue: string;

    override connect() {
        // @ts-ignore
        window.Userback = window.Userback || {};
        // @ts-ignore
        Userback.access_token = this.accessTokenValue;
        (function(d) {
            const s = d.createElement("script");
            s.async = true;
            s.src = "https://static.userback.io/widget/v1.js";
            (d.head || d.body).appendChild(s);
        })(document);
    }
}
