export class ResetButtonHandler
{
    public bind(): void
    {
        const resetButton: Element | null = document.getElementById("resetButton");

        if (resetButton) {
            resetButton.addEventListener("click", () => {
                window.location.reload();
            });
        }
    }
}
