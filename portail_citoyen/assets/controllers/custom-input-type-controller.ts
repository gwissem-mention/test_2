import {Controller} from "@hotwired/stimulus";

export default class extends Controller {

    static override targets: string[] = ["file", "filename"];
    static override values = {
        defaultMessage: String
    };

    declare readonly fileTarget: HTMLInputElement;
    declare readonly filenameTarget: HTMLLabelElement;
    declare readonly defaultMessageValue: string;

    public override connect(): void {
        this.fileTarget.addEventListener("change", this.displayFileName);
    }

    public override disconnect(): void {
        this.fileTarget.removeEventListener("change", this.displayFileName);
    }

    triggerFileInput() {
        this.fileTarget.click();
    }

    displayFileName = (event: Event) => {
        const input = event.target as HTMLInputElement;
        const filenameSpan = this.filenameTarget as HTMLSpanElement;

        if (input.files && input.files.length > 0) {
            const fileNames = Array.from(input.files).map(file => file.name).join(", ");
            filenameSpan.textContent = this.truncateString(fileNames, 30);
        }
        else {
            filenameSpan.textContent = this.defaultMessageValue;
        }
    };

    private truncateString(str: string, num: number): string {
        if (str.length <= num) {
            return str;
        }

        const frontChars = Math.ceil(num / 2);
        const backChars = Math.floor(num / 2);

        return str.substring(0, frontChars) + "..." + str.substring(str.length - backChars);
    }
}
