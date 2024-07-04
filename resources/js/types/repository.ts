export type Items = Record<string, unknown>;

export interface Repository {
    /**
     * Import items to config repository.
     *
     * @param {Items} items
     *
     * @return {void}
     */
    importItems(items: Items): void;

    /**
     * Get specific value from config repository.
     *
     * @param {string} key
     * @param {unknown|undefined} defaultValue
     *
     * @return {unknown}
     */
    get(key: string, defaultValue?: unknown): unknown;

    /**
     * Get all config items from repository.
     *
     * @return  {Items}
     */
    all(): Items;
}
