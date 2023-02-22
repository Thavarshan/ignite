import _ from 'lodash';

export default class Repository {
    /**
     * All configurations.
     *
     * @var {object}
     */
    protected items: { [key: string]: any; };

    /**
     * Create new instance of config repository.
     *
     * @param   {object}  items
     *
     * @return {void}
     */
    constructor (items: { [key: string]: any; } = {}) {
        this.items = items;
    }

    /**
     * Create a new instance of config repository.
     *
     * @param   {any}         items
     *
     * @return  {Repository}
     */
    public static create (items: { [key: string]: any; }): Repository {
        return new Repository(items);
    }

    /**
     * Add a new item to config repository.
     *
     * @param   {string}  key
     * @param   {any}     value
     *
     * @return  {void}
     */
    public set (key: string, value: any): void {
        _.set(this.items, key, value);
    }

    /**
     * Get specific value from config repository.
     *
     * @param {string} key
     * @param {any} defaultValue
     *
     * @return {any}
     */
    public get (key: string, defaultValue: any = undefined): any {
        return _.get(this.items, key, defaultValue);
    }

    /**
     * Get all config items from repository.
     *
     * @return  {object}
     */
    public all (): { [key: string]: any; } {
        return this.items;
    }
}
