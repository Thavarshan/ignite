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
