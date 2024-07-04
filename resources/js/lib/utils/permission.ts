import type { User } from '@/types/user';

/**
 * Check if the user has the given permission.
 *
 * @param {User} user
 * @param {string} permission
 *
 * @return {boolean}
 */
export function can(user: User, permission: string): boolean {
    if (permission === '*') {
        return true;
    }

    return user.permissions.map((p) => p.slug).includes(permission);
}
