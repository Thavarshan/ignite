import { Repository as RepositoryInterface, Items } from '@/types/config';
import _ from 'lodash';

export class Repository implements RepositoryInterface {
    /**
     * Create new instance of config repository.
     *
     * @param   {Items}  items
     */
    constructor(private readonly items: Items = {}) {}

    /**
     * Get specific value from config repository.
     *
     * @param {string} key
     * @param {any|undefined} defaultValue
     *
     * @return {any}
     */
    public get(key: string, defaultValue?: any): any {
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
