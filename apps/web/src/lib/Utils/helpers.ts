import { config } from '@ignite/scribe';
import _ from 'lodash';
import Router from 'next/router';

/**
 * Construct the full base API URL.
 *
 * @param   {string}  uri
 *
 * @return  {string}
 */
export const apiUrl = (
    uri?: string,
    params?: { [key: string]: string; }
): string => {
    let baseUrl = _.trimEnd(config('apiUrl'), '/');

    if (uri) {
        baseUrl = `${baseUrl}/${_.trimStart(uri, '/')}`;
    }

    if (params) {
        baseUrl = `${baseUrl}?${new URLSearchParams(params).toString()}`;
    }

    return baseUrl;
};

/**
 * Reedirect to given location.
 *
 * @param   {string}  uri
 *
 * @return  {void}
 */
export const redirect = (uri: string): void => {
    Router.push(uri);
};
