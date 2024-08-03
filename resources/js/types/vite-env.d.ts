/// <reference types="vite/client" />

import type { PageProps as InertiaPageProps } from '@inertiajs/core';
import type { AxiosInstance } from 'axios';
import type { Config as ZiggyConfig } from 'ziggy-js';
import type { PageProps as AppPageProps } from '@/types/page';
import type { __ } from 'lodash';

declare global {
    interface Window {
        _: __;
        axios: AxiosInstance;
    }

    const route: typeof route;
    const Ziggy: ZiggyConfig;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps { }
}

declare module '*.json' {
    const value: unknown;
    export default value;
}
