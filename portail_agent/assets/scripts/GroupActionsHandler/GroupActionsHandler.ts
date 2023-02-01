export class GroupActionsHandler {
    public bind(): void {
        const checkBox: HTMLElement | null = document.getElementById("checkOrUncheckAll");

        if (checkBox) {
            checkBox.addEventListener("click", () => {
                // Must be ignored because checked attribute does not exist on HTMLElement type.
                // @ts-ignore
                if (checkBox.checked) {
                    this.changeCheckBoxesStatutes(true);
                } else {
                    this.changeCheckBoxesStatutes(false);
                }
            });
        }
    }

    private changeCheckBoxesStatutes(status: boolean): void {
        document.querySelectorAll(".form-check-input").forEach((checkBox: Element) => {
            // Must be ignored because checked attribute does not exist on Element type.
            // @ts-ignore
            checkBox.checked = status;
        });
    }
}
