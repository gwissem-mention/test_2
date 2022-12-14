export class EtaLabElementState
{
    private _renderedResultContainer!: HTMLUListElement | null;

    get renderedResultContainer(): HTMLUListElement | null {
        return this._renderedResultContainer;
    }

    set renderedResultContainer(value: HTMLUListElement | null) {
        this._renderedResultContainer = value;
    }
}
