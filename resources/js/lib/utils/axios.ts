import { get } from 'lodash';

import type { AxiosError } from 'axios';

export function getAxiosErrors(error: AxiosError) {
    let errors = {};
    const response = JSON.parse(error.request.response);

    if (response.errors) {
        const errorKeys = Object.keys(response.errors);

        for (const key of errorKeys) {
            const dots = key.match(/\./g)?.length || 0;

            if (dots > 1) continue;

            errors = { ...errors, [key]: get(response.errors, key)[0] };
        }
    } else if (response.data) {
        errors = { default: response.data };
    }

    return errors;
}
