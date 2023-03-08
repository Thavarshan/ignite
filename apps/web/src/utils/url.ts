import _ from 'lodash';
import { config } from '@ignite/scribe';

const url = (path?: string): URL => {
    let baseUrl = config('url');

    if (typeof window !== 'undefined') {
        baseUrl = window.location;
    }

    if (!path) {
        return new URL('/', baseUrl);
    }

    path = _.trimEnd(path, '/');

    return new URL(path, baseUrl);
};

export default url;
