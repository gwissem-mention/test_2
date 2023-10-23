import {Controller} from "@hotwired/stimulus";
import {getComponent, Component} from "@symfony/ux-live-component";
import {Loader} from "@googlemaps/js-api-loader";
import {GirondeBoundaryChecker} from "../../scripts/GirondeBoundaryChecker";

export default class extends Controller {
    static override targets: string[] = ["map", "leftMenu", "modal"];

    protected component: Component | undefined | null;

    declare readonly mapTarget: HTMLInputElement;
    declare readonly leftMenuTarget: HTMLInputElement;
    declare readonly modalTarget: HTMLInputElement;
    private readonly DEFAULT_LATITUDE: number = 46.7107;
    private readonly DEFAULT_LONGITUDE: number = 2.4321;
    private readonly DEFAULT_ZOOM_FRANCE: number = 5;
    private readonly DEFAULT_MARKER_WIDTH: number = 29;
    private readonly DEFAULT_MARKER_HEIGHT: number = 40;
    private readonly DEFAULT_LABEL_X: number = 14;
    private readonly DEFAULT_LABEL_Y: number = 16;

    private _loader!: Loader;
    private _map!: google.maps.Map;
    private _markers: google.maps.Marker[] = [];

    override async initialize(): Promise<void> {
        this.component = await getComponent(this.element as HTMLElement);

        if (this.component) {
            this.init();
        }
    }

    public selectUnit(event: Event): void {
        const element: HTMLElement = event.currentTarget as HTMLElement;
        const div: HTMLElement | null | undefined = element.parentElement?.parentElement;

        if (div) {
            this.activeUnitItem(div, div.dataset["unitIdAnonym"] as unknown as number);
        }
    }

    public unselectUnit(event: Event): void {
        const element: HTMLElement = event.currentTarget as HTMLElement;
        const div: HTMLElement | null | undefined = element.parentElement?.parentElement;

        if (div) {
            this.component?.getParent()?.set("unitSelected", null);
            element.classList.add("fr-hidden");
            element.previousElementSibling?.classList.remove("fr-hidden");
            div.classList.remove("active");
            this._markers[div.dataset["unitIdAnonym"] as unknown as number]?.setIcon(this.getMarkerIcon());
        }
    }

    // Must be ignored because we can't type url here.
    // @ts-ignore
    public accessibilityInformation({params: {url}}): void {
        fetch(url, {
            method: "GET",
        }).then((response: Response) => {
            response.json()
                .then(data => {
                    const modalContent: Element | null = this.modalTarget.querySelector(".fr-modal__content");

                    if (modalContent) {
                        modalContent.innerHTML = data.content;
                    }
                });
        });
    }

    private init(): void {
        if (this.component) {
            this.setLoader();
        }
    }

    private setLoader(): void {
        this._loader = new Loader({
            apiKey: this.component?.getData("apiKey"),
            libraries: ["places"]
        });

        if (this._loader) {
            this._loader.load()
                .then((google: any) => {
                    this.setMap(google);
                })
                .catch((error: any) => {
                    throw error;
                });
        }
    }

    private async setMap(google: any): Promise<void> {
        if (this.mapTarget) {
            const lat: number = this.DEFAULT_LATITUDE;
            const lng: number = this.DEFAULT_LONGITUDE;
            const zoom: number = this.DEFAULT_ZOOM_FRANCE;

            this._map = new google.maps.Map(this.mapTarget, {
                center: {
                    lat: lat,
                    lng: lng
                },
                zoom: zoom,
                fullscreenControl: false
            });


            this.addSearchBox();
            (window as any).map = this._map;
        }
    }

    private addSearchBox(): void {
        const input: HTMLInputElement = document.getElementById("map-search") as HTMLInputElement;
        const searchBox = new google.maps.places.SearchBox(input);

        this._map.addListener("bounds_changed", () => {
            searchBox.setBounds(this._map.getBounds() as google.maps.LatLngBounds);
        });
        searchBox.addListener("places_changed", () => this.onPlaceChanged(searchBox));

        input.addEventListener("input", () => {
            input.setAttribute("aria-expanded", input.value !== "" ? "true" : "false");
        });

        input.addEventListener("focus", () => {
            if (input.value !== "") {
                input.setAttribute("aria-expanded", "true");
            }
        });

        input.addEventListener("blur", () => {
            input.setAttribute("aria-expanded", "false");
        });
    }

    private async onPlaceChanged(searchBox: google.maps.places.SearchBox): Promise<void> {
        const places: google.maps.places.PlaceResult[] | undefined = searchBox.getPlaces();

        if (!places || places.length == 0) {
            return;
        }

        const bounds = new google.maps.LatLngBounds();
        const place: google.maps.places.PlaceResult | undefined = places[0];

        if (place === undefined) {
            return;
        }

        if (!place.geometry || !place.geometry.location) {
            return;
        }

        const inseeCode: string = await this.getEtalabInseeCode(new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng()));
        const errorMessageElement: HTMLElement | null = document.getElementById("error-message");

        if (inseeCode && !GirondeBoundaryChecker.isInsideGironde(inseeCode.substring(0, 2))) {
            if (errorMessageElement) {
                errorMessageElement.textContent = "Uniquement les adresses des faits commis en Gironde sont acceptées";
            }
            return;
        }
        if (errorMessageElement) {
            errorMessageElement.textContent = "";
        }

        // @ts-ignore
        const urlSearchParam: string = new URLSearchParams({
            "lat": place.geometry.location.lat(),
            "lng": place.geometry.location.lng(),
            "inseeCode": inseeCode
        }).toString();

        fetch(this.component?.getData("searchBoxActionUrl") + "?" + urlSearchParam, {
            method: "GET",
        })
            .then(response => response.json())
            .then(data => {
                this.clearMarkers();
                if (place.geometry && place.geometry.location) {
                    this.addMarker(place.geometry.location);
                    if (data.units.length === 0) {
                        if (place.geometry.viewport) {
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }

                        this._map.fitBounds(bounds);

                        if (this.leftMenuTarget) {
                            this.leftMenuTarget.innerHTML = data.view;
                        }

                        return;
                    }

                    data.units.forEach((unit: any, index: number) => {
                        const unitLatLng: google.maps.LatLng = new google.maps.LatLng(unit.latitude, unit.longitude);
                        this.addMarker(unitLatLng, index, unit.idAnonym);
                        bounds.extend(unitLatLng);
                    });
                    this._map.fitBounds(bounds);

                    if (this.leftMenuTarget) {
                        this.leftMenuTarget.innerHTML = data.view;
                    }
                }
            });
    }

    private addMarker(latLng: google.maps.LatLng, index: number | null = null, unitId = 0): void {

        if (index) {
            this._markers[unitId] = new google.maps.Marker({
                map: this._map,
                position: latLng,
                label: {text: (index + 1).toString(), color: "white", fontWeight: "bold"},
                icon: this.getMarkerIcon()
            });
        } else {
            this._markers[unitId] = new google.maps.Marker({
                map: this._map,
                position: latLng,
            });
        }

        if (unitId > 0) {
            this._markers[unitId]?.addListener("click", () => {
                this.selectUnitById(unitId);
            });
        }

        (window as any).markers = this._markers;
    }

    private clearMarkers(): void {
        this._markers.forEach((marker: google.maps.Marker) => {
            marker.setMap(null);
        });
    }

    private async getEtalabInseeCode(latLng: google.maps.LatLng): Promise<string> {
        const response = await fetch(`https://api-adresse.data.gouv.fr/reverse/?lon=${latLng.lng()}&lat=${latLng.lat()}`);
        const data = await response.json();
        let address = "";

        if (data) {
            address = data.features[0]?.properties?.citycode;
        }

        return address;
    }

    private selectUnitById(unitId: number): void {
        const listElement: HTMLElement | null = this.leftMenuTarget.querySelector(`li[data-unit-id-anonym="${unitId}"]`);

        if (listElement) {
            this.activeUnitItem(listElement, unitId);
            listElement.scrollIntoView({behavior: "smooth"});
        }
    }

    private activeUnitItem(element: HTMLElement, unitId: number): void {
        const currentUnitSelected: HTMLElement | null = this.leftMenuTarget?.querySelector(".active") as HTMLElement;

        currentUnitSelected?.classList.remove("active");
        currentUnitSelected?.querySelector(".unit-select")?.classList.remove("fr-hidden");
        currentUnitSelected?.querySelector(".unit-unselect")?.classList.add("fr-hidden");

        const unitSelect: HTMLElement | null = element.querySelector(".unit-select") as HTMLElement;
        const unitUnselect: HTMLElement | null = element.querySelector(".unit-unselect") as HTMLElement;

        this.component?.getParent()?.set("unitSelected", unitId.toString());
        unitSelect.classList.add("fr-hidden");
        unitUnselect.classList.remove("fr-hidden");
        element.classList.add("active");
        this.activeUnitMarker(unitId);
    }

    private activeUnitMarker(unitId: number): void {
        this._markers.forEach((marker: google.maps.Marker, index: number) => {
            if (index === 0) {
                return;
            }
            marker.setIcon(this.getMarkerIcon());
        });

        this._markers[unitId]?.setIcon(this.getMarkerIcon(35, 48, 17, 19));
    }

    private getMarkerIcon(
        markerWidth: number = this.DEFAULT_MARKER_WIDTH,
        markerHeight: number = this.DEFAULT_MARKER_HEIGHT,
        labelX: number = this.DEFAULT_LABEL_X,
        labelY: number = this.DEFAULT_LABEL_Y): google.maps.Icon {
        return {
            url: this.mapTarget.getAttribute("data-marker-icon") as string,
            scaledSize: new google.maps.Size(markerWidth, markerHeight),
            labelOrigin: new google.maps.Point(labelX, labelY)
        };
    }
}
