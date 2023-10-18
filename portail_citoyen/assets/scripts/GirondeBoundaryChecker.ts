
const GIRONDE_DEPARTMENT_NUMBER = "33";

export class GirondeBoundaryChecker {
    static isInsideGironde(departmentNumber: string): boolean {
        return GIRONDE_DEPARTMENT_NUMBER === departmentNumber;
    }
}
