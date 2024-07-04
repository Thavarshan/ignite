import moment from 'moment';

/**
 * Mutate given timestamp to human readable form.
 *
 * @param {string}
 *
 * @return {string}
 */
export function diffForHumans(timestamp: string): string {
    return moment(timestamp).fromNow();
}

/**
 * Mutate given timestamp to human readable form.
 *
 * @param {string}
 *
 * @return {string}
 */
export function simple(timestamp: string): string {
    return moment(timestamp).format('ddd, MMM D');
}

/**
 * Mutate given timestamp to human readable form.
 *
 * @param {string}
 *
 * @return {string}
 */
export function expanded(timestamp: string): string {
    return moment(timestamp).format('dddd, MMMM Do YYYY');
}
