import { UseToastOptions } from '@chakra-ui/react';
import { config } from '@ignite/scribe';
import _ from 'lodash';
import Router from 'next/router';
import toastConfig from '@/config/toast';

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

/**
 * Default toast configs.
 *
 * @param {string} title
 * @param {string} description
 * @param {string} status
 *
 * @return  {object}
 */
export const toast = (
    title: string,
    description: string,
    status = 'success'
): UseToastOptions => {
    return _.merge({
        title, description, status
    }, toastConfig) as UseToastOptions;
};
