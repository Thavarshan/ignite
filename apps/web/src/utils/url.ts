import _ from 'lodash';
import { config } from '@ignite/scribe';

const makeUrl = (baseUrl: string, path?: string): URL => {
    if (!path) {
        return new URL('/', baseUrl);
    }

    path = _.trimEnd(path, '/');

    return new URL(path, baseUrl);
};

export const url = (path?: string): URL => {
    let baseUrl = config('url');

    if (typeof window !== 'undefined') {
        baseUrl = window.location;
    }

    return makeUrl(baseUrl, path);
};

export const apiUrl = (path?: string): URL => {
    return makeUrl(config('apiUrl'), path);
};
