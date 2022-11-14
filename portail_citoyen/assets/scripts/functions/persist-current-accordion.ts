/**
 * Persists current accordion.
 */
export const persistCurrentAccordion = (currentAccordionId: number): void => {
    localStorage.setItem("currentAccordionId", String(currentAccordionId));
};
