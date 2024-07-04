import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

/**
 * Merge class names with tailwindcss classes.
 *
 * @param   {ClassValue[]}  inputs
 *
 * @return  {string}
 */
export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function generateUUIDv4(): string {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        const r = (Math.random() * 16) | 0;
        const v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
}
