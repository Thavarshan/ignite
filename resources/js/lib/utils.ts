import { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import moment from 'moment';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(
    urlToCheck: NonNullable<InertiaLinkProps['href']>,
    currentUrl: string,
) {
    return toUrl(urlToCheck) === currentUrl;
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function excerptText(text: string, length: number = 100) {
    if (text.length <= length) {
        return text;
    }

    const excerpt = text.slice(0, length);
    const lastSpaceIndex = excerpt.lastIndexOf(' ');

    return lastSpaceIndex !== -1
        ? excerpt.slice(0, lastSpaceIndex) + '...'
        : excerpt + '...';
}

export function diffForHumans(date: string | Date) {
    return moment(date).fromNow();
}
