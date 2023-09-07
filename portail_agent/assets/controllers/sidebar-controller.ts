import {Controller} from "@hotwired/stimulus";
import {Modal} from "bootstrap";
import { HttpMethodsEnum } from "../scripts/utils/HttpMethodsEnum";
import { HttpStatusCodeEnum } from "../scripts/utils/HttpStatusCodeEnum";
import flatpickr from "flatpickr";
import { Instance } from "flatpickr/dist/types/instance";
import { French } from "flatpickr/dist/l10n/fr";

type DelegateFetchResponse = { form: string };

export default class SidebarController extends Controller {
    static override targets: string[] = [
        "sidebar",
        "background",
        "avatar",
        "rightsDelegationModal",
        "delegationForm",
        "delegationDatesCalendarBlock",
        "delegationStartDate",
        "delegationEndDate",
        "delegationDatesSelectedBlock",
        "delegationSelectedStartDate",
        "delegationSelectedEndDate",
        "delegationAgentsBlock",
        "delegationModalValidateButton"
    ];

    declare readonly sidebarTarget: HTMLInputElement;
    declare readonly backgroundTarget: HTMLInputElement;
    declare readonly avatarTarget: HTMLInputElement;
    declare readonly rightsDelegationModalTarget: HTMLInputElement;
    declare readonly hasRightsDelegationModalTarget: boolean;
    declare readonly delegationFormTarget: HTMLFormElement;
    declare readonly delegationDatesCalendarBlockTarget: HTMLInputElement;
    declare readonly delegationStartDateTarget: HTMLInputElement;
    declare readonly delegationEndDateTarget: HTMLInputElement;
    declare readonly delegationDatesSelectedBlockTarget: HTMLInputElement;
    declare readonly delegationSelectedStartDateTarget: HTMLElement;
    declare readonly delegationSelectedEndDateTarget: HTMLElement;
    declare readonly delegationAgentsBlockTarget: HTMLInputElement;
    declare readonly delegationModalValidateButtonTarget: HTMLInputElement;

    static fp: Instance | undefined;

    public override connect(): void {
        this.initFlatpickr();
        this.setDelegationDatesState("edit");
    }

    private initFlatpickr(): void {
        SidebarController.fp = flatpickr("#right_delegation_dateRangePicker", {
            inline: true,
            locale: French,
            weekNumbers: true,
            monthSelectorType: "static",
            mode: "range",
            // https://flatpickr.js.org/examples/#disabling-all-dates-except-select-few
            enable: [
                function (date: Date) {
                    // https://stackoverflow.com/a/10944417
                    const today = new Date();
                    const then = new Date(
                        today.getFullYear(),
                        today.getMonth(),
                        today.getDate(),
                        0,
                        0,
                        0
                    );
                    const diff = today.getTime() - then.getTime();

                    return date.valueOf() >= today.valueOf() - diff;
                },
            ],
        }) as Instance;
    }

    public updateDelegationDates(): void {
        this.setDelegationDatesState("edit");
    }

    public setDelegationDatesState(state: string): void {
        if (state === "edit") {
            this.delegationDatesCalendarBlockTarget.classList.remove("d-none");
            this.delegationDatesSelectedBlockTarget.classList.add("d-none");
            this.delegationAgentsBlockTarget.classList.add("d-none");
            this.delegationModalValidateButtonTarget.setAttribute("disabled", "disabled");
        } else {
            this.delegationDatesCalendarBlockTarget.classList.add("d-none");
            this.delegationDatesSelectedBlockTarget.classList.remove("d-none");
            this.delegationAgentsBlockTarget.classList.remove("d-none");
            this.delegationModalValidateButtonTarget.removeAttribute("disabled");
        }
    }

    public changeDelegationDatesRanges(data: any): void {
        const dates = data.target.value.split(" au ");

        if (dates.length>1) {
            this.delegationStartDateTarget.value = dates[0];
            this.delegationSelectedStartDateTarget.innerText = this.formatDate(dates[0]);

            this.delegationEndDateTarget.value = dates[1];
            this.delegationSelectedEndDateTarget.innerText = this.formatDate(dates[1]);

            this.setDelegationDatesState("show");
        }
    }

    private formatDate(date: string): string {
        return date.split("-").reverse().join("/");
    }

    public openSidebar(): void {
        this.setSidebarState("show");
    }

    public closeSidebar(): void {
        this.setSidebarState("hide");
    }

    private setSidebarState(state: string): void {
        if (this.sidebarTarget && this.backgroundTarget) {
            this.sidebarTarget.classList.toggle("d-none", state === "hide");
            this.backgroundTarget.classList.toggle("d-none", state === "hide");
        }

        if (this.avatarTarget) {
            this.avatarTarget.classList.toggle("z-max", state === "show");
            this.avatarTarget.classList.toggle("background-blue", state === "show");
        }
    }

    public openRightsDelegationModal(): void {
        if (this.hasRightsDelegationModalTarget) {
            const modal: Modal | null = new Modal(this.rightsDelegationModalTarget);

            if (modal) {
                modal.show();
            }
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public delegate({params: {url}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.delegationFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data: DelegateFetchResponse) => {
                            if (response.status === HttpStatusCodeEnum.OK) {
                                Modal.getInstance(this.rightsDelegationModalTarget)?.hide();
                            } else if (data.form) {
                                this.delegationFormTarget.innerHTML = data.form;
                            }
                        });
                });
        }
    }
}
