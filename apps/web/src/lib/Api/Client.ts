import ClientContract from '@/lib/Contracts/Api/Client';
import ErrorResponse from '../Contracts/Http/Responses/ErrorResponse';
import { apiUrl } from '../Utils/url';
import Form from '@ignite/formlink';
import Http from '../Http/Client';
import { AxiosStatic } from 'axios';
import Credentials from '../Contracts/Auth/Credentials';

export default class Client implements ClientContract {
    protected resource = '';

    protected token?: string;

    protected errors?: ErrorResponse | ErrorResponse[];

    constructor (token: string) {
        this.setToken(token);
    }

    public resources (name: string): Client {
        this.resource = name;

        return this;
    }

    public async authenticate (credentials: Credentials): Promise<string> {
        this.resource = 'login';

        const http = this.getHttp(credentials);

        const token = await http.post(this.makeUrl(), {
            onSuccess: ({ data }: any) => data.accessToken,
            onFail: (error: any) => {
                const e: any = error.response.data;

                throw new Error(JSON.stringify(e.message));
            }
        });

        return token;
    }

    public async get (id: string): Promise<any> {
        const http = this.getHttp();

        const data = await http.get(this.makeUrl(id), {
            onSuccess: ({ data }: any) => data,
            onFail: (error: any) => {
                const e: any = error.response.data;

                throw new Error(JSON.stringify(e.message));
            }
        });

        return data;
    }

    public async all (query?: string, value?: string): Promise<any[]> {
        const http = this.getHttp();
        let url = undefined;

        if (query && value) {
            url = `?${query}=${value}`;
        }

        const data = await http.get(this.makeUrl(url), {
            onSuccess: ({ data }: any) => data.data,
            onFail: (error: any) => {
                const e: any = error.response.data;

                throw new Error(JSON.stringify(e.message));
            }
        });

        return data;
    }

    public async create (data: { [key: string]: any; }): Promise<any> {
        const http = this.getHttp(data);

        return await http.post(this.makeUrl(), {
            onSuccess: ({ data }: any) => data,
            onFail: (error: any) => {
                const e: any = error.response.data;

                throw new Error(JSON.stringify(e.message));
            }
        });
    }

    public async update (id: string, data: { [key: string]: any; }): Promise<any> {
        const http = this.getHttp(data);

        return await http.patch(this.makeUrl(id), {
            onSuccess: ({ data }: any) => data,
            onFail: (error: any) => {
                const e: any = error.response.data;

                throw new Error(JSON.stringify(e.message));
            }
        });
    }

    public async delete (id: string): Promise<void> {
        const http = this.getHttp();

        return await http.delete(this.makeUrl(id), {
            onFail: (error: any) => {
                throw new Error(JSON.stringify(error.response.data.message));
            }
        });
    }

    protected getHttp (data: { [key: string]: any; } = {}): Form {
        const http = Form.create(data, { token: this.token });

        http.setHttpHandlerInstance(Http.make() as AxiosStatic);

        return http;
    }

    protected makeUrl (url?: string): string {
        url = url ? `${this.resource}/${url}` : this.resource;

        return apiUrl(url).toString();
    }

    public getErrors (): undefined | ErrorResponse | ErrorResponse[] {
        return this.errors;
    }

    public setToken (token: string): void {
        this.token = token;
    }
}
