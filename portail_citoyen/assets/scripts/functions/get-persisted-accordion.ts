/**
 * Returns persisted accordion.
 */
export const getPersistedCurrentAccordion = (): string|null => {
    return localStorage.getItem("currentAccordionId");
};
