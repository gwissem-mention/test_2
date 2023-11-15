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
        "avatar",
        "profileSidebar",
        "profileBackground",
        "bell",
        "notificationsSidebar",
        "notificationsBackground",
        "rightsDelegationModal",
        "delegationForm",
        "delegationDatesCalendarBlock",
        "delegationStartDate",
        "delegationEndDate",
        "delegationDatesSelectedBlock",
        "delegationSelectedStartDate",
        "delegationSelectedEndDate",
        "delegationAgentsBlock",
        "delegationModalValidateButton",
        "rightsDelegationViewModal",
        "rightsDelegationCancelModal",
        "headerContainer",
    ];

    declare readonly profileSidebarTarget: HTMLInputElement;
    declare readonly profileBackgroundTarget: HTMLInputElement;
    declare readonly avatarTarget: HTMLInputElement;
    declare readonly notificationsSidebarTarget: HTMLInputElement;
    declare readonly notificationsBackgroundTarget: HTMLInputElement;
    declare readonly bellTarget: HTMLInputElement;
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
    declare readonly rightsDelegationViewModalTarget: HTMLInputElement;
    declare readonly rightsDelegationCancelModalTarget: HTMLInputElement;
    declare readonly hasRightsDelegationViewModalTarget: boolean;
    declare readonly hasRightsDelegationCancelModalTarget: boolean;
    declare readonly headerContainerTarget: HTMLInputElement;

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
        this.delegationDatesCalendarBlockTarget.classList.toggle("d-none", state !== "edit");
        this.delegationDatesSelectedBlockTarget.classList.toggle("d-none", state === "edit");
        this.delegationAgentsBlockTarget.classList.toggle("d-none", state === "edit");

        if (state === "edit") {
            this.delegationModalValidateButtonTarget.setAttribute("disabled", "disabled");
        } else {
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

    public openProfileSidebar(): void {
        this.setProfileSidebarState("show");
    }

    public closeProfileSidebar(): void {
        this.setProfileSidebarState("hide");
    }

    private setProfileSidebarState(state: string): void {
        if (this.profileSidebarTarget && this.profileBackgroundTarget) {
            this.profileSidebarTarget.classList.toggle("d-none", state === "hide");
            this.profileBackgroundTarget.classList.toggle("d-none", state === "hide");
        }

        if (this.avatarTarget) {
            this.avatarTarget.classList.toggle("z-max", state === "show");
            this.avatarTarget.classList.toggle("background-blue", state === "show");
        }
    }

    public openNotificationsSidebar(): void {
        this.setNotificationsSidebarState("show");
    }

    public closeNotificationsSidebar(): void {
        this.setNotificationsSidebarState("hide");
    }

    private setNotificationsSidebarState(state: string): void {
        if (this.notificationsSidebarTarget && this.notificationsBackgroundTarget) {
            this.notificationsSidebarTarget.classList.toggle("d-none", state === "hide");
            this.notificationsBackgroundTarget.classList.toggle("d-none", state === "hide");
        }

        if (this.bellTarget) {
            this.bellTarget.classList.toggle("z-max", state === "show");
            this.bellTarget.classList.toggle("color-blue", state === "show");
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

    public openRightsDelegationViewModal(): void {
        if (this.hasRightsDelegationViewModalTarget) {
            const modal: Modal | null = new Modal(this.rightsDelegationViewModalTarget);

            if (modal) {
                modal.show();
            }
        }
    }

    public openRightsDelegationModificationModal(): void {
        if (this.hasRightsDelegationModalTarget) {
            const modificationModal: Modal | null = new Modal(this.rightsDelegationModalTarget);
            const viewModal: Modal | null = Modal.getInstance(this.rightsDelegationViewModalTarget);

            if (viewModal) {
                viewModal.hide();
            }

            if (modificationModal) {
                this.initFlatpickr();
                this.setDelegationDatesState("show");

                this.delegationSelectedStartDateTarget.innerText = this.formatDate(this.delegationStartDateTarget.value);
                this.delegationSelectedEndDateTarget.innerText = this.formatDate(this.delegationEndDateTarget.value);

                modificationModal.show();
            }
        }
    }

    public openRightsDelegationCancellationModal(): void {
        if (this.hasRightsDelegationModalTarget) {
            const cancelModal: Modal | null = new Modal(this.rightsDelegationCancelModalTarget);
            const viewModal: Modal | null = Modal.getInstance(this.rightsDelegationViewModalTarget);

            if (viewModal) {
                viewModal.hide();
            }

            if (cancelModal) {
                cancelModal.show();
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
                                this.reloadHeaderContainer();
                            } else if (data.form) {
                                this.delegationFormTarget.innerHTML = data.form;
                            }
                        });
                });
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public cancel({params: {url}}): void {
        if (url) {
            fetch(url, {
                method: HttpMethodsEnum.POST,
                body: new FormData(this.delegationFormTarget)
            })
                .then((response: Response) => {
                    response.json()
                        .then((data: DelegateFetchResponse) => {
                            if (response.status === HttpStatusCodeEnum.OK) {
                                Modal.getInstance(this.rightsDelegationCancelModalTarget)?.hide();
                                location.reload();
                            } else if (data.form) {
                                this.delegationFormTarget.innerHTML = data.form;
                            }
                        });
                });
        }
    }

    private async reloadHeaderContainer(): Promise<void> {
        // Reload the page in ajax, then replace the #header div by the new one
        const response = await fetch(window.location.href, {
            method: HttpMethodsEnum.GET
        });
        const data: string = await response.text();
        const element: HTMLDivElement = document.createElement("div");
        element.innerHTML = data;
        const headerSource: HTMLElement | null = element.querySelector("#header");

        if (this.headerContainerTarget && headerSource) {
            this.headerContainerTarget.innerHTML = headerSource.innerHTML;
        }
    }
}
