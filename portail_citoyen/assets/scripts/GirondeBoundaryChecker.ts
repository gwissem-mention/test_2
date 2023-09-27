
export class GirondeBoundaryChecker {
    static isInsideGironde(latitude: number, longitude: number): boolean {
        const girondeBounds = {
            north: 45.035,
            south: 44.587,
            east: -0.442,
            west: -0.809
        };
        return (
            latitude >= girondeBounds.south &&
            latitude <= girondeBounds.north &&
            longitude >= girondeBounds.west &&
            longitude <= girondeBounds.east
        );
    }
}
