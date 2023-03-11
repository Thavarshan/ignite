import { NextResponse as Response } from 'next/server';
import type { NextRequest } from 'next/server';
import MiddlewareConditions from '@/contracts/lib/MiddlewareConditions';
import Guard from '@/contracts/auth/Guard';
import UrlPattern from 'url-pattern';
import _ from 'lodash';

export default class Handler {
    public readonly home = '/tickets';

    protected conditions: MiddlewareConditions[] = [];

    protected guardFallbackConditions = {
        auth: '/auth/login',
        guest: this.home,
        '*': '/'
    };

    protected request: NextRequest;

    constructor (request: NextRequest) {
        this.request = request;
    }

    public handle (): any {
        const match = this.conditions.find((route: MiddlewareConditions) => {
            return this.startsWith(route.path);
        });

        if (!match) {
            return;
        }

        const authorized = this.checkAuthorization(match?.guard);


        if (!authorized) {
            return Response.redirect(this.fallbackUrl(match?.guard));
        }

        if (match.redirectTo) {
            return Response.redirect(new URL(match.redirectTo, this.request.url));
        }

        return Response.next();
    }

    protected startsWith (path: string): boolean {
        const pathname = this.request.nextUrl.pathname;
        const pattern = new UrlPattern(path);
        const matches = pattern.match(pathname);

        return !_.isNull(matches);
    }

    protected makeUrl (path: string): URL {
        return new URL(path, this.request.url);
    }

    protected checkAuthorization (guard: string): boolean {
        const token = this.request.cookies.get('token')?.value;

        if (guard === 'auth') {
            return Boolean(token);
        } else if (guard === 'guest') {
            return Boolean(!token);
        }

        return true;
    }

    protected fallbackUrl (guard: Guard): URL {
        const path = this.guardFallbackConditions[guard] || '*';

        return new URL(path, this.request.url);
    }

    public setConditions (conditions: MiddlewareConditions[]): void {
        this.conditions = conditions;
    }
}
