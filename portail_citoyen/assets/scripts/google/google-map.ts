import {Loader} from "@googlemaps/js-api-loader";

export class GoogleMap {
    private readonly DEFAULT_LATITUDE: number = 48.866667;
    private readonly DEFAULT_LONGITUDE: number = 2.333333;
    private readonly DEFAULT_ZOOM: number = 14;
    private readonly API_KEY: string = "";

    private _loader!: Loader;
    private _map!: google.maps.Map;
    private readonly _mapElement!: HTMLElement | null;

    constructor(mapElement: HTMLElement | null) {
        this._mapElement = mapElement;

        this.setLoader();
    }

    public get loader(): Loader {
        return this._loader;
    }

    private setLoader(): void {
        this._loader = new Loader({
            apiKey: this.API_KEY,
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

    public get map(): google.maps.Map {
        return this._map;
    }

    private setMap(google: any): void {
        if (this._mapElement) {
            this._map = new google.maps.Map(this._mapElement, {
                center: {
                    lat: this.DEFAULT_LATITUDE,
                    lng: this.DEFAULT_LONGITUDE
                },
                zoom: this.DEFAULT_ZOOM
            });
        }
    }
}
