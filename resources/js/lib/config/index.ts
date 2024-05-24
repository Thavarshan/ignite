import { Repository } from './../config/repository';
import items from '@/lib/config/items.json';

/**
 * Get config value of given key.
 *
 * @param   {string}  key
 * @param   {any}  defaultValue
 *
 * @return  {any}
 */
export function config(key: string, defaultValue: any = null): any {
    const repository = new Repository(items);

    return repository.get(key, defaultValue);
}
