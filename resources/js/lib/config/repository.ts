import { Repository as RepositoryInterface, Items } from '../../types/repository';
import _ from 'lodash';

export class Repository implements RepositoryInterface {
    /**
     * Create new instance of config repository.
     *
     * @param   {Items}  items
     */
    constructor(private readonly items: Items = {}) {}

    /**
     * Import items to config repository.
     *
     * @param {Items} items
     *
     * @return {void}
     */
    public importItems(items: Items): void {
        Object.assign(this.items, items);
    }

    /**
     * Get specific value from config repository.
     *
     * @param {string} key
     * @param {unknown|undefined} defaultValue
     *
     * @return {unknown}
     */
    public get(key: string, defaultValue?: unknown): unknown {
        return _.get(this.items, key, defaultValue);
    }

    /**
     * Get all config items from repository.
     *
     * @return  {Items}
     */
    public all(): Items {
        return this.items;
    }
}
