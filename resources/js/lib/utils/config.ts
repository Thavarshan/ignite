import { Items } from '@/types/repository';
import { Repository } from './../config/repository';
// import items from '@/config/items.json';

/**
 * Create new instance of config repository.
 */
const repository = new Repository({});

/**
 * Define config items.
 *
 * @param   {Items}  items
 *
 * @return  {void}
 */
export function defineConfig(items: Items): void {
    repository.importItems(items);
}

/**
 * Get config value of given key.
 *
 * @param   {string}  key
 * @param   {unknown|null}  defaultValue
 *
 * @return  {unknown}
 */
export function config(key: string, defaultValue: unknown = null): unknown {
    return repository.get(key, defaultValue);
}
