import { NextResponse as Response } from 'next/server';
import type { NextRequest } from 'next/server';
import MiddlewareConditions from '@/lib/Contracts/Http/MiddlewareConditions';
import GuardType from '@/lib/Contracts/Auth/Guard';
import UrlPattern from 'url-pattern';
import AuthGuard from '../Auth/AuthGuard';
import GuestGuard from '../Auth/GuestGuard';
import Guard from '../Auth/Guard';

export default class Handler {
    protected readonly ALL_ACCESS = '*';

    protected conditions: MiddlewareConditions[];

    protected redirects = {
        auth: '/auth/login',
        guest: '/tickets',
        '*': '/'
    };

    protected authGuards: { [key: string]: Guard; } = {
        auth: new AuthGuard(),
        guest: new GuestGuard()
    };

    protected request: NextRequest;

    constructor (request: NextRequest, conditions: MiddlewareConditions[] = []) {
        this.request = request;
        this.conditions = conditions;
    }

    public async handle (): Promise<any> {
        const match = this.conditions.find(
            (route: MiddlewareConditions) => this.startsWith(route.path)
        );

        if (!match) {
            return;
        }

        const authorized = await this.isAuthorized(match?.guard);

        if (!authorized) {
            return Response.redirect(this.fallbackUrl(match?.guard));
        }

        if (match.redirectTo) {
            return Response.redirect(
                new URL(match.redirectTo, this.request.url)
            );
        }

        return Response.next();
    }

    protected startsWith (path: string): boolean {
        const pattern = new UrlPattern(path);
        const matches = pattern.match(this.request.nextUrl.pathname);

        return matches !== null;
    }

    protected makeUrl (path: string): URL {
        return new URL(path, this.request.url);
    }

    protected async isAuthorized (guard: string): Promise<boolean> {
        if (this.guardIsAllAccess(guard)) {
            return true;
        }

        const token = this.request.cookies.get('token')?.value;
        const guardInstance = this.authGuards[guard];

        if (!guardInstance) {
            return true;
        }

        return await guardInstance.authorize(token);
    }

    protected fallbackUrl (guard: GuardType): URL {
        const path = this.redirects[guard] || this.ALL_ACCESS;

        return new URL(path, this.request.url);
    }

    public setConditions (conditions: MiddlewareConditions[]): void {
        this.conditions = conditions;
    }

    protected guardIsAllAccess (guard: string): boolean {
        return guard === this.ALL_ACCESS;
    }
}
