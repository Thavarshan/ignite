export default class Repository {
    /**
     * All configurations.
     *
     * @var {object}
     */
    protected items: {
        [key: string]: any;
    };
    /**
     * Create new instance of config repository.
     *
     * @param   {object}  items
     *
     * @return {void}
     */
    constructor(items?: {
        [key: string]: any;
    });
    /**
     * Create a new instance of config repository.
     *
     * @param   {any}         items
     *
     * @return  {Repository}
     */
    static create(items: {
        [key: string]: any;
    }): Repository;
    /**
     * Add a new item to config repository.
     *
     * @param   {string}  key
     * @param   {any}     value
     *
     * @return  {void}
     */
    set(key: string, value: any): void;
    /**
     * Get specific value from config repository.
     *
     * @param {string} key
     * @param {any} defaultValue
     *
     * @return {any}
     */
    get(key: string, defaultValue?: any): any;
    /**
     * Get all config items from repository.
     *
     * @return  {object}
     */
    all(): {
        [key: string]: any;
    };
}
//# sourceMappingURL=Repository.d.ts.map