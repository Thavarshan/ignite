export type Items = Record<string, any>;

export interface Repository {
    /**
     * Get specific value from config repository.
     *
     * @param {string} key
     * @param {any|undefined} defaultValue
     *
     * @return {any}
     */
    get(key: string, defaultValue?: any): any;

    /**
     * Get all config items from repository.
     *
     * @return  {Items}
     */
    all(): Items;
}
