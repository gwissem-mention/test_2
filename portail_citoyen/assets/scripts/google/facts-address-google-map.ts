import {Loader} from "@googlemaps/js-api-loader";
import {Component} from "@symfony/ux-live-component";

export class FactsAddressGoogleMap {
    private readonly DEFAULT_LATITUDE: number = 48.866667;
    private readonly DEFAULT_LONGITUDE: number = 2.333333;
    private readonly DEFAULT_ZOOM: number = 14;

    private _loader!: Loader;
    private _map!: google.maps.Map;
    private readonly _mapElement!: HTMLElement | null;
    private readonly _component!: Component | null;
    private _marker!: google.maps.Marker;

    constructor(mapElement: HTMLElement | null, component: Component) {
        this._mapElement = mapElement;
        this._component = component;

        this.setLoader();
    }

    public get loader(): Loader {
        return this._loader;
    }

    public get map(): google.maps.Map {
        return this._map;
    }

    private setLoader(): void {
        this._loader = new Loader({
            apiKey: this._component?.getData("apiKey"),
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
        if (this._mapElement) {
            const latLng = await this.getLatLng();
            if (latLng) {
                this._map = new google.maps.Map(this._mapElement, {
                    center: {
                        lat: latLng.lat(),
                        lng: latLng.lng()
                    },
                    zoom: this.DEFAULT_ZOOM
                });
                (window as any).map = this._map;
                this.addMarker(latLng);
            }
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

                            const componentParent: Component | null | undefined = this._component?.getParent();

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

    private async getGoogleLatLng(): Promise<google.maps.LatLng | null> {
        const addressInput: HTMLElement | null = document.getElementById("facts-startAddress-address");
        let coord = null;
        if (addressInput) {
            // @ts-ignore
            const address = addressInput.value;
            const geocoder = new google.maps.Geocoder();

            await geocoder.geocode({"address": address}, (results, status) => {
                if (status === google.maps.GeocoderStatus.OK && results && results[0]) {
                    coord = results[0].geometry.location;
                }
            });
        }

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
        const componentParent: Component | null | undefined = this._component?.getParent();
        let latLng: google.maps.LatLng | null = null;

        if (componentParent) {
            const lat: number = componentParent.getData("startAddressEtalabInput.latitude");
            const lng: number = componentParent.getData("startAddressEtalabInput.longitude");
            if (lat && lng) {
                latLng = new google.maps.LatLng(lat, lng);
            } else {
                latLng = await this.getGoogleLatLng();

                if (latLng === null) {
                    latLng = new google.maps.LatLng(this.DEFAULT_LATITUDE, this.DEFAULT_LONGITUDE);
                }
            }
        }

        return latLng;
    }

}
