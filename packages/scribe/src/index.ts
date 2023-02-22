import Repository from './Repository';

/**
 * The repository instance is used to store all configurations.
 *
 * @var {Repository}
 */
let configurations: Repository;

/**
 * Create a new instance of config repository and define all configurations.
 *
 * @param   {object}  config
 *
 * @return  {void}
 */
export function defineConfig (config: { [key: string]: any; }): void {
    configurations = Repository.create(config);
}

/**
 * Get a specific value from config repository.
 *
 * @param   {string|undefined}  key
 * @param   {any|undefined}     defaultValue
 *
 * @return  {any}
 */
export function config (key?: string, defaultValue?: any): any {
    return !key ? configurations : configurations.get(key, defaultValue);
}
