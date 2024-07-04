import type { Method } from '@inertiajs/core';
import type { Icon } from '@/types/icon';

export interface NavigationItem {
    name: string;
    route?: string;
    routeParams?: Record<string, string | number>;
    method?: Method;
    icon?: Icon;
    notifications?: number;
    children?: NavigationItem[];
    action?: NavigationAction;
    active?: boolean;
    match?: string | string[];

    onClick?: () => void;
}

export interface NavigationGroup {
    name: string;
    items: NavigationItem[];
}

export interface NavigationAction {
    icon: Icon;
    tooltip?: string;
    onClick: () => void;
}
