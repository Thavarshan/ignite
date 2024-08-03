import { route } from 'ziggy-js';

/**
 * Generate a API route URL.
 *
 * @param name The route name.
 * @param params The route parameters.
 *
 * @return The generated URL.
 */
export function apiRoute(name: string, params?: { [key: string]: unknown; }) {
    return route('v1:' + name, params);
}
