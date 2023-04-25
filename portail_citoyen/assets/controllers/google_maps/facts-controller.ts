import {Controller} from "@hotwired/stimulus";
import {getComponent, Component} from "@symfony/ux-live-component";
import {Loader} from "@googlemaps/js-api-loader";

export default class extends Controller {
    static override targets: string[] = ["map"];

    protected component: Component | undefined | null;

    declare readonly mapTarget: HTMLInputElement;

    private readonly DEFAULT_LATITUDE: number = 46.7107;
    private readonly DEFAULT_LONGITUDE: number = 2.4321;
    private readonly DEFAULT_ZOOM: number = 14;
    private readonly DEFAULT_ZOOM_FRANCE: number = 5;

    private _loader!: Loader;
    private _map!: google.maps.Map;
    private _marker!: google.maps.Marker;

    override async initialize(): Promise<void> {
        this.component = await getComponent(this.element as HTMLElement);

        if (this.component) {
            this.init();
        }
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
                    this.setMap(google).then(() => {
                        this.onClick();
                    });
                })
                .catch((error: any) => {
                    throw error;
                });
        }
    }

    private async setMap(google: any): Promise<void> {
        if (this.mapTarget) {
            const latLng = await this.getLatLng();
            const lat: number = latLng ? latLng.lat() : this.DEFAULT_LATITUDE;
            const lng: number = latLng ? latLng.lng() : this.DEFAULT_LONGITUDE;
            const zoom: number = latLng ? this.DEFAULT_ZOOM : this.DEFAULT_ZOOM_FRANCE;

            this._map = new google.maps.Map(this.mapTarget, {
                center: {
                    lat: lat,
                    lng: lng
                },
                zoom: zoom
            });

            if (latLng) {
                this.addMarker(latLng);
            }

            this.addSearchBox();
            (window as any).map = this._map;
        }
    }

    private addSearchBox(): void {
        const input: HTMLInputElement = document.getElementById("map-search") as HTMLInputElement;
        const searchBox = new google.maps.places.SearchBox(input);
        const controlsTopLeft = this._map.controls[google.maps.ControlPosition.LEFT_TOP];

        if (controlsTopLeft) {
            controlsTopLeft.push(input);

            this._map.addListener("bounds_changed", () => {
                searchBox.setBounds(this._map.getBounds() as google.maps.LatLngBounds);
            });

            searchBox.addListener("places_changed", () => {
                const places: google.maps.places.PlaceResult[] | undefined = searchBox.getPlaces();

                if (!places || places.length == 0) {
                    return;
                }

                const bounds = new google.maps.LatLngBounds();

                places.forEach((place: any) => {
                    if (!place.geometry || !place.geometry.location) {
                        return;
                    }
                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }

                    this.addMarker(place.geometry.location);
                });

                this._map.fitBounds(bounds);
            });
        }
    }

    private onClick(): void {
        if (this._map) {
            this._map.addListener("click", async (e: Event) => {
                // latLng prop is not present in Event type
                // @ts-ignore
                const latLng: google.maps.LatLng | null = await e.latLng;
                if (latLng) {
                    this.addMarker(latLng);

                    const etalabAddress: object | null = await this.getEtalabAddress(latLng);
                    const googleAddress = await this.getGoogleAddress(latLng);

                    // label prop is not present in Event type
                    // @ts-ignore
                    const label = etalabAddress ? etalabAddress.label : googleAddress;

                    // id prop is not present in Event type
                    // @ts-ignore
                    const id: string | null = etalabAddress ? etalabAddress.id : null;

                    if (label) {
                        const addressKnownRadio: HTMLElement | null = document.getElementById("facts_address_addressOrRouteFactsKnown_0");

                        if (addressKnownRadio) {
                            // checked prop is not present in HTMLElement type
                            // @ts-ignore
                            addressKnownRadio.checked = true;
                            addressKnownRadio.dispatchEvent(new Event("change", {bubbles: true}));

                            const componentParent: Component | null | undefined = this.component?.getParent();

                            if (componentParent) {
                                componentParent.set("startAddressEtalabInput.addressSearch", label);
                                componentParent.set("startAddressEtalabInput.addressSearchSaved", label);
                                componentParent.set("startAddressEtalabInput.latitude", latLng.lat());
                                componentParent.set("startAddressEtalabInput.longitude", latLng.lng());

                                const addressInput: HTMLElement | null = document.getElementById("facts-startAddress-address");

                                if (addressInput) {
                                    // value prop is not present in HTMLElement type
                                    // @ts-ignore
                                    addressInput.value = label;
                                }

                                if (id) {
                                    componentParent.set("startAddressEtalabInput.addressId", id);
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    private async getEtalabAddress(latLng: google.maps.LatLng): Promise<object> {
        const response = await fetch(`https://api-adresse.data.gouv.fr/reverse/?lon=${latLng.lng()}&lat=${latLng.lat()}`);
        const data = await response.json();
        let address: object = {};

        if (data) {
            address = data.features[0]?.properties;
        }

        return address;
    }

    private async getGoogleAddress(latLng: google.maps.LatLng) {
        let label = "";
        const geocoder = new google.maps.Geocoder();

        await geocoder.geocode({"location": latLng}, (results, status) => {
            if (status === google.maps.GeocoderStatus.OK && results && results.length > 0) {
                results.forEach((result) => {
                    if (result.types.includes("route")) {
                        label = result.formatted_address.replace("Route sans nom, ", "") ?? "";
                    }
                });
            }
        });

        return label;
    }

    private async getGoogleLatLng(address: string): Promise<google.maps.LatLng | null> {
        let coord = null;
        const geocoder = new google.maps.Geocoder();
        await geocoder.geocode({"address": address}, (results, status) => {
            if (status === google.maps.GeocoderStatus.OK && results && results[0]) {
                coord = results[0].geometry.location;
            }
        }).catch(() => {
            coord = null;
        });

        return coord;
    }

    private addMarker(latLng: google.maps.LatLng): void {
        if (this._marker) {
            this._marker.setMap(null);
        }

        this._marker = new google.maps.Marker({
            map: this._map,
            position: latLng
        });
        (window as any).marker = this._marker;
    }

    private async getLatLng(): Promise<google.maps.LatLng | null> {
        const componentParent: Component | null | undefined = this.component?.getParent();
        let latLng: google.maps.LatLng | null = null;

        if (componentParent) {
            const lat: number = componentParent.getData("startAddressEtalabInput.latitude");
            const lng: number = componentParent.getData("startAddressEtalabInput.longitude");
            // value prop is not present in HTMLElement type
            // @ts-ignore
            const factsAddress: string = document.getElementById("facts-startAddress-address")?.value;
            if (lat && lng) {
                latLng = new google.maps.LatLng(lat, lng);
            } else if (factsAddress) {
                latLng = await this.getGoogleLatLng(factsAddress);
            } else {
                const identityAddress = componentParent.getData("identityAddress")?.label;
                if (identityAddress) {
                    latLng = await this.getGoogleLatLng(identityAddress);
                }
            }
        }

        return latLng;
    }
}
